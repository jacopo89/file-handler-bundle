<?php


namespace FileHandler\Bundle\FileHandlerBundle\Service;


use FileHandler\Bundle\FileHandlerBundle\Model\FileInfo;
use Symfony\Component\Mime\MimeTypes;

class FileInfoGuesser
{
    public static function getContentInfo($file_content)
    {
        $fileInfo = new FileInfo();
        $mt = new MimeTypes();

        $file = self::createFile($file_content);
        $fileInfo->setWeight(filesize($file)); // Weight

        $mime = $mt->guessMimeType($file);
        $fileInfo->setMimeType($mime);

        $mimeExt = $mt->getExtensions($mime)[0];
        $fileInfo->setExt($mimeExt); // Extension

        if ($fileInfo->isImage())
        {
            $imageInfo = getimagesize($file);
            list($width, $height) = $imageInfo;
            $fileInfo->setWidth($width)->setHeight($height); // Sizes
        }

        unlink($file);
        return $fileInfo;
    }

    private static function createFile($content): string
    {
        $tmpPath = tempnam(sys_get_temp_dir(), '');
        $handle = fopen($tmpPath, 'w');
        fwrite($handle, $content);
        fclose($handle);

        return $tmpPath;
    }
}
