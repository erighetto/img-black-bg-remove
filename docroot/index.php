<?php

$path = __DIR__ . '/pub/media/catalog/product/';

foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $image) {

    if (strpos($image, 'cache') || !exif_imagetype($image)) {
        continue;
    }

    try {

        $imagick = new \Imagick();
        $imagick->readImage(realpath($image));

        $pixel = $imagick->getImagePixelColor(1, 1);
        $color = $pixel->getColorAsString();
        if ($color !== "srgb(0,0,0)") continue;

        print $image;
        print "\n";

        $imagick->opaquePaintImage(
            $pixel, "rgb(255, 255, 255)", 0.05 * \Imagick::getQuantum(), false
        );

        $imagick->setFormat('jpeg');

        $imagick->writeImage($image);

    } catch (\ImagickException $e) {
        print $e->getMessage();
        print "\n";
    }

}