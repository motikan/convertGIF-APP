<?php

namespace Application\Utility;

use GifCreator\GifCreator;
use Intervention\Image\ImageManagerStatic as Image;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ImageUtility
{
    public static function convertGif($fileName, $widthSize = 600, $moveSize = 60, $loopSpeed = 10){
        $log = new Logger('convertGif');
        $log->pushHandler(new StreamHandler(getcwd().'/data/log/ImageUtility.log', Logger::WARNING));

        // upload image file
        $baseImg = Image::make(getcwd() . '/data/' . $fileName);
        $imageWidth = $baseImg->getWidth();
        $imageHeight = $baseImg->getHeight();

        $cropX = $imageWidth / $moveSize;

        $frames = [];
        $durations = [];
        for ($i=0; $i < $moveSize; $i++){
            $img = clone $baseImg;
            if($cropX * $i > $imageWidth - $widthSize){
                break;
            }
            $after = $img->crop($widthSize, $imageHeight, $cropX * $i, 0);
            $fileName = getcwd() . '/data/' .  "result_" . $i . ".png";
            $after->save($fileName);
            $frames[] = imagecreatefrompng($fileName);
            $durations[] = $loopSpeed;
        }

        $gc = new GifCreator();
        $gc->create($frames, $durations, 0); // 0:None stop
        $gifBinary = $gc->getGif();
        file_put_contents(getcwd() . '/data/' . 'result.gif', $gifBinary);
        header('Content-type: image/gif');
        header('Content-Disposition: filename="butterfly.gif"');
        echo $gifBinary;
    }

}