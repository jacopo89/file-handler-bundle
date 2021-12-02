<?php


namespace FileHandler\Bundle\FileHandlerBundle\Repository;


interface FileRepositoryInterface
{
    public function getName();
    public function getSubDir(): ?string;
}
