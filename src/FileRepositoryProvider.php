<?php


namespace FileHandler\Bundle\FileHandlerBundle;


use FileHandler\Bundle\FileHandlerBundle\Exception\AlreadyDefinedFileRepositoryException;
use FileHandler\Bundle\FileHandlerBundle\Exception\UndefinedFileRepositoryException;
use FileHandler\Bundle\FileHandlerBundle\Repository\FileRepositoryInterface;

class FileRepositoryProvider
{

    /**
     * @var FileRepositoryInterface[]
     */
    private array $fileRepositories;

    /**
     * @param iterable $fileRepositories
     */
    public function __construct(iterable $fileRepositories)
    {
        foreach ($fileRepositories as $fileRepository) {
            if (isset($this->fileRepositories[$fileRepository->getName()])) {
                throw new AlreadyDefinedFileRepositoryException($fileRepository);
            }
            $this->fileRepositories[$fileRepository->getName()] = $fileRepository;
        }
    }

    /**
     * @param string $name
     * @return FileRepositoryInterface
     */
    public function get(string $name): FileRepositoryInterface
    {
        if (!isset($this->resources[$name])) {
            throw new UndefinedFileRepositoryException($name);
        }
        return $this->fileRepositories[$name];
    }

    /**
     * @return FileRepositoryInterface[]
     */
    public function getResources(): array
    {
        return $this->fileRepositories;
    }

}
