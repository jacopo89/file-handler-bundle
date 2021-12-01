<?php


namespace FileHandler\Bundle\FileHandlerBundle\Model;


class FileInfo
{

    private string $ext;

    private int $weight;

    private ?int $width = null;

    private ?int $height = null;

    private string $mimeType;

    public function getExt(): string
    {
        return $this->ext;
    }

    public function setExt(string $ext): FileInfo
    {
        $this->ext = $ext;
        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): FileInfo
    {
        $this->weight = $weight;
        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): FileInfo
    {
        $this->width = $width;
        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): FileInfo
    {
        $this->height = $height;
        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): FileInfo
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    public function isImage(): bool
    {
        return strpos($this->mimeType, "image/") === 0;
    }

    public function isDocument(): bool
    {
        return strpos($this->mimeType, "application/") === 0;
    }

    public function isPDF(): bool
    {
        return strpos($this->mimeType, "application/pdf") === 0;
    }
}
