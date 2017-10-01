<?php

namespace Application\Utility;

use GifCreator\GifCreator;
use Intervention\Image\ImageManagerStatic as Image;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ImageUtility
{
    // 画像の保存先ディレクトリ
    const IMAGE_SAVE_DIR = '/data';
    const GIF_SAVE_DIR = '/public/img/result';

    public static function convertGif($insertItemId, $baseFullFileName, $outImageWidth, $moveSize, $loopSpeed){
        $log = new Logger('convertGif');
        $log->pushHandler(new StreamHandler(getcwd().'/data/log/ImageUtility.log', Logger::WARNING));

        // 画像のアップロード
        $baseImg = Image::make(getcwd() . self::IMAGE_SAVE_DIR . '/' . $baseFullFileName);
        $imageWidth = $baseImg->getWidth();
        $imageHeight = $baseImg->getHeight();

        // 画像の高さを400px以下にする
        if($imageHeight > 400){
            $scale = 400 / $imageHeight;
            $baseImg->resize(floor($imageWidth * $scale), floor($imageHeight * $scale));
            $imageWidth = $baseImg->getWidth();
            $imageHeight = $baseImg->getHeight();
        }

        // 出力画像幅より元画像幅が小さい場合は、元画像幅にする
        if($imageWidth < $outImageWidth){
            $outImageWidth = $imageWidth;
        }

        // 拡張子なしファイル名
        $baseFileName = self::getFileExtension($baseFullFileName);

        $cropX = floor($imageWidth / $moveSize);

        $frames = [];
        $durations = [];
        for ($i=0; $i < $moveSize; $i++){
            $img = clone $baseImg;

            if($cropX * $i > $imageWidth - $outImageWidth && $i !== 0){
                // 最初と最後のフレームで一時停止
                $durations[0] = 50;
                $durations[$i-1] = 50;
                break;
            }
            $after = $img->crop($outImageWidth, $imageHeight, $cropX * $i, 0);
            $fileName = getcwd() . self::IMAGE_SAVE_DIR . '/' . $baseFileName .  "_" . $i . ".png";
            $after->save($fileName);
            $frames[] = imagecreatefrompng($fileName);
            $durations[] = $loopSpeed;
        }


        // 画像ファイルの削除
        foreach(glob(getcwd() . self::IMAGE_SAVE_DIR . '/' . $baseFileName . '*.png') as $file) {
            unlink($file);
        }

        // GIFファイルの作成
        $gc = new GifCreator();
        $gc->create($frames, $durations, 0); // 0:None stop
        $gifBinary = $gc->getGif();

        // GIFファイルの保存
        $resultGifFileName = $insertItemId . $baseFileName .'.gif';
        file_put_contents(getcwd() . self::GIF_SAVE_DIR . '/' . $resultGifFileName, $gifBinary);

        return $resultGifFileName;
    }

    /**
     * 拡張子なしファイル名取得
     * @param $fileBaseName
     * @return mixed
     */
    public static function getFileExtension($fileBaseName){
        return pathinfo($fileBaseName, PATHINFO_FILENAME);
    }

    public static function getTransSpeed($speedParam){
        $speedTable = self::getTransSpeedTableArray();
        return $speedTable[$speedParam];
    }

    public static function getTransSpeedTableArray(){
        return [
            '0' => 5,
            '10' => 10,
            '20' => 20,
        ];
    }

    public static function getTransSpeedKeysArray(){
        $speedTable = self::getTransSpeedTableArray();
        return array_keys($speedTable);
    }


}