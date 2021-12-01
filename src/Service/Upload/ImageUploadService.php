<?php
declare(strict_types=1);

namespace FileHandler\Bundle\FileHandlerBundle\Service\Upload;

use Gaufrette\Filesystem;
use Gaufrette\Extras\Resolvable\ResolvableFilesystem;
use Gaufrette\Extras\Resolvable\Resolver\StaticUrlResolver;

class ImageUploadService extends FileUploadService
{
    protected ResolvableFilesystem $filesystem;

    protected string $appBaseUrl;

    public function __construct(Filesystem $pmsFilesystemImages, string $appBaseUrl)
    {
        $this->filesystem = new ResolvableFilesystem($pmsFilesystemImages, new StaticUrlResolver('images'));
        $this->appBaseUrl = $appBaseUrl;
    }


}
