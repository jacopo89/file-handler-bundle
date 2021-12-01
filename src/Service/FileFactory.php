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
    private FileRepositoryProvider $fileRepositoryProvider;


    public function __construct(FileUploadService $fileUploadService, FileRepositoryProvider $fileRepositoryProvider)
    {
        $this->fileUploadService = $fileUploadService;
        $this->fileRepositoryProvider = $fileRepositoryProvider;
    }

    public function create(FileToUpload $fileToUpload, string $type, string $title = null, string $description = null): FileInterface
    {
        $fileRepository = $this->fileRepositoryProvider->get($type);
        $existingFile = $fileRepository->findOneByMd5($fileToUpload->getMd5());
        if($existingFile instanceof FileInterface)
            return $existingFile;

        $url = $this->fileUploadService->upload($fileToUpload);

        return $type::createFromFileModel($fileToUpload, $url->getRelative(), $url->getAbsolute(), $title, $description);
    }

}
