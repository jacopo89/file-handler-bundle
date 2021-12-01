<?php
declare(strict_types=1);

namespace FileHandler\Bundle\FileHandlerBundle\Service;


use App\Service\FileFactory;
use FileHandler\Bundle\FileHandlerBundle\Model\FileInterface;
use FileHandler\Bundle\FileHandlerBundle\Model\FileToUpload;
use FileHandler\Bundle\FileHandlerBundle\Model\UploadedBase64File;

class Base64Uploader
{
    private FileFactory $fileFactory;

    public function __construct(FileFactory $fileFactory)
    {
        $this->fileFactory = $fileFactory;
    }

    public function fromBase64ImageFile(UploadedBase64File $uploadedBase64File): FileInterface
    {
        return $this->createFile(FileToUpload::createImageFromBase64File($uploadedBase64File), $uploadedBase64File);
    }

    public function fromBase64PDFFile(UploadedBase64File $uploadedBase64File): FileInterface
    {
        return $this->createFile(FileToUpload::createPDFFromBase64File($uploadedBase64File), $uploadedBase64File);
    }

    public function fromBase64File(UploadedBase64File $uploadedBase64File): FileInterface
    {
        return $this->createFile(FileToUpload::createFileFromBase64File($uploadedBase64File), $uploadedBase64File);
    }

    private function createFile(FileToUpload $fileToUpload, UploadedBase64File $uploadedBase64File): FileInterface
    {
        return $this->fileFactory->create($fileToUpload, $uploadedBase64File->getTitle(), $uploadedBase64File->getDescription());
    }

    public function getMD5(UploadedBase64File $uploadedBase64File): string
    {
        $fileToUpload = FileToUpload::createFileFromBase64File($uploadedBase64File);
        return $fileToUpload->getMd5();
    }
}
