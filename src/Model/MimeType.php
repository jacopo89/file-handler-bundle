<?php


namespace FileHandler\Bundle\FileHandlerBundle\Model;


class MimeType
{
    public const TYPE_IMAGE = 'image';
    public const INVALID_MIME_TYPE = 'INVALID FORMAT';

    const MIMES = [
        'image/jpeg' => ['type' => self::TYPE_IMAGE, 'ext' => '.jpg'],
        'image/png' =>  ['type' => self::TYPE_IMAGE, 'ext' => '.png']
    ];

    /**
     * @return false|string
     */
    public static function getExtension(string $mime): string {
        if (array_key_exists($mime, self::MIMES))
            return self::MIMES[$mime]['ext'];

        return self::INVALID_MIME_TYPE;
    }

    public static function isImage(string $mime): bool
    {
        if (array_key_exists($mime, self::MIMES) && self::MIMES[$mime]['type'] == self::TYPE_IMAGE)
            return true;

        return false;
    }
}
