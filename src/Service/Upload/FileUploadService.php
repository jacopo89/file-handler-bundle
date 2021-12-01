<?php
declare(strict_types=1);

namespace FileHandler\Bundle\FileHandlerBundle\Service\Upload;

use FileHandler\Bundle\FileHandlerBundle\Model\FileToUpload;
use FileHandler\Bundle\FileHandlerBundle\Model\UploadUrl;
use Gaufrette\Filesystem;
use Gaufrette\Extras\Resolvable\ResolvableFilesystem;
use Gaufrette\Extras\Resolvable\Resolver\StaticUrlResolver;

class FileUploadService
{
    protected ResolvableFilesystem $filesystem;

    protected string $appBaseUrl;

    public function __construct(FileSystem $pmsFilesystem, string $appBaseUrl)
    {
        $this->filesystem = new ResolvableFilesystem($pmsFilesystem, new StaticUrlResolver(''));
        $this->appBaseUrl = $appBaseUrl;
    }

    public function upload(FileToUpload $file)
    {
        $path = sprintf("%s/%s.%s", $file->getSubDir(), $file->getFilename(), $file->getFileInfo()->getExt());
        $this->filesystem->write($path, $file->getContent());

        $relativeUrl = $this->filesystem->resolve($path);
        $absoluteUrl = sprintf("%s/%s", rtrim($this->appBaseUrl, '/'), ltrim($relativeUrl, '/'));
        return new UploadUrl($relativeUrl, $absoluteUrl);
    }

}
