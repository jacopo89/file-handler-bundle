<?php
declare(strict_types=1);

namespace FileHandler\Bundle\FileHandlerBundle\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FileHandler\Bundle\FileHandlerBundle\Model\FileInterface;


/**
 * Class FileRepository
 * @package FileHandler\Bundle\FileHandlerBundle\Repository
 * @template T
 * @template-extends ServiceEntityRepository<T>
 */
class FileRepository extends ServiceEntityRepository implements FileRepositoryInterface
{
    /**
     * @param ManagerRegistry $managerRegistry
     * @param string $entityClass The class name of the entity this repository manages
     * @psalm-param class-string<T> $entityClass
     */
    public function __construct(ManagerRegistry $managerRegistry, string $entityClass)
    {
        parent::__construct($managerRegistry, $entityClass);
    }

    public function remove(FileInterface $file): void
    {
        $this->_em->remove($file);
        $this->_em->flush();
    }

    public function findOneByMd5($value): ?FileInterface
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.md5 = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByName(string $name): ?FileInterface
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.filename = :val')
            ->setParameter('val', $name)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getName()
    {
        return static::class;
    }
}
