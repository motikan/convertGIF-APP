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

    public static function convertGif($baseFullFileName, $widthSize = 600, $moveSize = 60, $loopSpeed = 10){
        $log = new Logger('convertGif');
        $log->pushHandler(new StreamHandler(getcwd().'/data/log/ImageUtility.log', Logger::WARNING));

        // 画像のアップロード
        $baseImg = Image::make(getcwd() . self::IMAGE_SAVE_DIR . '/' . $baseFullFileName);
        $imageWidth = $baseImg->getWidth();
        $imageHeight = $baseImg->getHeight();

        // 拡張子なしファイル名
        $baseFileName = self::getFileExtension($baseFullFileName);

        $cropX = floor($imageWidth / $moveSize);

        $frames = [];
        $durations = [];
        for ($i=0; $i < $moveSize; $i++){
            $img = clone $baseImg;
            if($cropX * $i > $imageWidth - $widthSize){
                // 最初と最後のフレームで一時停止
                $durations[0] = 50;
                $durations[$i-1] = 50;
                break;
            }
            $after = $img->crop($widthSize, $imageHeight, $cropX * $i, 0);
            $fileName = getcwd() . self::IMAGE_SAVE_DIR . '/' . $baseFileName .  "_" . $i . ".png";
            $after->save($fileName);
            $frames[] = imagecreatefrompng($fileName);
            $durations[] = $loopSpeed;
        }


        // 画像ファイルの削除
        foreach(glob(getcwd() . self::IMAGE_SAVE_DIR . '/' . $baseFileName . '*.png') as $file) {
            unlink($file);
        }

        $gc = new GifCreator();
        $gc->create($frames, $durations, 0); // 0:None stop
        $gifBinary = $gc->getGif();
        $resultGifFileName = $baseFileName .'.gif';
        file_put_contents(getcwd() . self::IMAGE_SAVE_DIR . '/' . $resultGifFileName, $gifBinary);
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


}