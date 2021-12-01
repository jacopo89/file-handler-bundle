<?php
declare(strict_types=1);

namespace FileHandler\Bundle\FileHandlerBundle\Model;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Service\Base64decoder;
use App\Service\File\FileInfoGuesser;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ApiResource()
 */
class UploadedBase64File
{
    /**
     * @Groups ({"base64file:write"})
     */
    private ?int $id = null;

    /**
     * @Groups ({"base64file:write"})
     */
    private string $base64 = "";

    /**
     * @Groups ({"base64file:write"})
     */
    private string $filename;

    /**
     * @Groups ({"base64file:write"})
     */
    private ?string $title = null;

    /**
     * @Groups ({"base64file:write"})
     */
    private ?string $description = null;

    public static function createFromURL(string $title, string $url): self
    {
        $file = new UploadedBase64File();
        $file->setTitle($title);
        $file->setBase64(base64_encode(file_get_contents($url)));

        $linkParts = explode('/', $url);
        $file->setFilename(end($linkParts));

        return $file;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBase64(): string
    {
        return $this->base64;
    }

    public function setBase64(string $base64): void
    {
        $this->base64 = $base64;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @Assert\Callback
     * @param UploadedBase64File $uploadedBase64File
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public static function isImage(?UploadedBase64File $uploadedBase64File,ExecutionContextInterface $context, $payload): void
    {
        if (is_null($uploadedBase64File)) return;

        if(empty($uploadedBase64File->getId())){
            $fileInfo = FileInfoGuesser::getContentInfo(Base64decoder::decode($uploadedBase64File->getBase64()));
            if(!$fileInfo->isImage()){
                $context->buildViolation("The file is not an image but a ". $fileInfo->getMimeType())->addViolation();
            }
        }
    }

    /**
     * @Assert\Callback
     * @param UploadedBase64File $uploadedBase64File
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public static function isDocument(?UploadedBase64File $uploadedBase64File,ExecutionContextInterface $context, $payload): void
    {
        if (is_null($uploadedBase64File)) return;

        if(empty($uploadedBase64File->getId())){
            $fileInfo = FileInfoGuesser::getContentInfo(Base64decoder::decode($uploadedBase64File->getBase64()));
            if(!$fileInfo->isDocument()){
                $context->buildViolation("The file is not a document but a ". $fileInfo->getMimeType())->addViolation();
            }
        }
    }

    /**
     * @Assert\Callback
     * @param UploadedBase64File|null $uploadedBase64File
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public static function isPDF(?UploadedBase64File $uploadedBase64File,ExecutionContextInterface $context, $payload): void
    {
        if (is_null($uploadedBase64File)) return;

        if(empty($uploadedBase64File->getId())){
            $fileInfo = FileInfoGuesser::getContentInfo(Base64decoder::decode($uploadedBase64File->getBase64()));
            if(!$fileInfo->isPDF()){
                $context->buildViolation("The file is not a document but a ". $fileInfo->getMimeType())->addViolation();
            }
        }
    }
}
