<?php
declare(strict_types=1);

namespace FileHandler\Bundle\FileHandlerBundle\Service;

use Symfony\Component\String\Slugger\AsciiSlugger;

class FileNameGenerator
{
    public static function generate(string $origin = null): string
    {
        $name = uniqid();
        if ($origin)
        {
            $slugger = new AsciiSlugger();
            $origin = $slugger->slug($origin);
            $name .= "_{$origin}";
        }

        return $name;
    }
}
