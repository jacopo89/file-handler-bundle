<?php


namespace FileHandler\Bundle\FileHandlerBundle\Model;

use FileHandler\Bundle\FileHandlerBundle\Service\Base64decoder;
use FileHandler\Bundle\FileHandlerBundle\Service\FileInfoGuesser;
use FileHandler\Bundle\FileHandlerBundle\Service\FileNameGenerator;

class FileToUpload
{
    private ?string $filename;

    private ?string $subDir;

    private string $content;

    private FileInfo $fileInfo;

    public function __construct($filename, $content, $subDir = '/')
    {
        $this->content = $content;
        $this->filename = $filename;
        $this->subDir = $subDir;
        $this->fileInfo = FileInfoGuesser::getContentInfo($content);
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): FileToUpload
    {
        $this->filename = $filename;
        return $this;
    }

    public function getSubDir(): string
    {
        return $this->subDir;
    }

    public function setSubDir(string $subDir): FileToUpload
    {
        $this->subDir = $subDir;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): FileToUpload
    {
        $this->content = $content;
        return $this;
    }

    public function getMd5(): string
    {
        return md5($this->content);
    }

    public function setContentFromBase64($base64): void
    {
        $this->content = Base64decoder::decode($base64);
    }

    public function getFileInfo(): FileInfo
    {
        return $this->fileInfo;
    }

    public function setFileInfo(FileInfo $fileInfo): FileToUpload
    {
        $this->fileInfo = $fileInfo;
        return $this;
    }


    public static function createFromBase64File(UploadedBase64File $base64file): self
    {
        $content = Base64decoder::decode($base64file->getBase64());
        $filename = FileNameGenerator::generate($base64file->getFilename());
        return new self($filename, $content);
    }

    public static function createImageFromBase64File(UploadedBase64File $base64file): self
    {
        $content = Base64decoder::decode($base64file->getBase64());
        $filename = FileNameGenerator::generate($base64file->getFilename());
        return new self($filename, $content, 'images');
    }

    public static function createPDFFromBase64File(UploadedBase64File $base64file): self
    {
        $content = Base64decoder::decode($base64file->getBase64());
        $filename = FileNameGenerator::generate($base64file->getFilename());
        return new self($filename, $content, 'pdf');
    }

    public static function createFileFromBase64File(UploadedBase64File $base64file): self
    {
        $content = Base64decoder::decode($base64file->getBase64());
        $filename = FileNameGenerator::generate($base64file->getFilename());
        return new self($filename, $content, 'files');
    }

}
