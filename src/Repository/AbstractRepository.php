<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public function refresh(object $entity): void
    {
        $this->getEntityManager()->refresh($entity);
    }

    public function save(object $entity, bool $flush = true): void
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        if ($flush) {
            $em->flush();
        }
    }

    public function remove(object $entity, bool $flush = true): void
    {
        $em = $this->getEntityManager();
        $em->remove($entity);
        if ($flush) {
            $em->flush();
        }
    }
}
