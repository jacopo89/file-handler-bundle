<?php
declare(strict_types=1);

namespace FileHandler\Bundle\FileHandlerBundle\Service;

use FileHandler\Bundle\FileHandlerBundle\FileRepositoryProvider;
use FileHandler\Bundle\FileHandlerBundle\Model\FileInterface;
use FileHandler\Bundle\FileHandlerBundle\Model\FileToUpload;
use FileHandler\Bundle\FileHandlerBundle\Service\Upload\FileUploadService;

class FileFactory
{
    private FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function create(FileToUpload $fileToUpload, $fileRepository, string $type, string $title = null, string $description = null): FileInterface
    {
        $existingFile = $fileRepository->findOneByMd5($fileToUpload->getMd5());
        if($existingFile instanceof FileInterface)
            return $existingFile;

        $url = $this->fileUploadService->upload($fileToUpload);

        return ($fileRepository->getClassName())::createFromFileModel($fileToUpload, $url->getRelative(), $url->getAbsolute(), $title, $description);
    }

}
