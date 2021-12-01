<?php
declare(strict_types=1);

namespace FileHandler\Bundle\FileHandlerBundle\Repository;

use App\Entity\File;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use FileHandler\Bundle\FileHandlerBundle\Model\FileInterface;

class FileRepository extends EntityRepository implements FileRepositoryInterface
{
    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
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
