<?php

namespace Success\BannerBundle\Doctrine\ORM;

use Doctrine\ORM\EntityRepository;

class BannerRepository extends EntityRepository
{
    public function findByPlace($place)
    {
        $now = new \DateTime();

        $qb = $this->createQueryBuilder('b');
        $qb
            ->where('b.place = :place')
            ->andWhere('b.active = 1')
            ->andWhere('b.start_date <= :now OR b.start_date IS NULL')
            ->andWhere('b.end_date >= :now OR b.end_date IS NULL')
            ->orderBy('b.position', 'DESC')
            ->setParameter('now', $now)
            ->setParameter('place', $place);
        return $qb->getQuery()->execute();
    }
    
    public function findOneRandom($place)
    {
        $now = new \DateTime();

        $qb = $this->createQueryBuilder('b');
        $qb
            ->where('b.place = :place')
            ->andWhere('b.start_date <= :now OR b.start_date IS NULL')
            ->andWhere('b.end_date >= :now OR b.end_date IS NULL')
            ->setParameter('place', $place)
            ->setParameter('now', $now)
        ;

        $result = $qb->getQuery()->execute();

        if (count($result) > 1) {
            $entities = array();

            foreach ($result as $item) {
                $entities[] = $item;
            }

            $entity = $entities[array_rand($entities)];
        } else {
            $entity = current($result);
        }

        return $entity;
    }

}
