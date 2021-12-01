<?php


namespace FileHandler\Bundle\FileHandlerBundle\Model;


class UploadUrl
{
    private string $relative;

    private string $absolute;

    public function __construct(string $relative, string $absolute)
    {
        $this->relative = $relative;
        $this->absolute = $absolute;
    }

    public function getRelative(): string
    {
        return $this->relative;
    }

    public function setRelative(string $relative): UploadUrl
    {
        $this->relative = $relative;
        return $this;
    }

    public function getAbsolute(): string
    {
        return $this->absolute;
    }

    public function setAbsolute(string $absolute): UploadUrl
    {
        $this->absolute = $absolute;
        return $this;
    }




}
