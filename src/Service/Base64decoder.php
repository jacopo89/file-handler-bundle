<?php


namespace FileHandler\Bundle\FileHandlerBundle\Service;


class Base64decoder
{
    public static function decode(string $source): string
    {
        $encode = strpos($source, ',') !== FALSE ? explode(',', $source)[1] : $source;
        return base64_decode($encode);
    }
}
