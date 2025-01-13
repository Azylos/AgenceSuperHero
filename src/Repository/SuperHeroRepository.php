<?php

namespace App\Repository;

use App\Entity\SuperHero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\AST\WhereClause;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SuperHero>
 */
class SuperHeroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuperHero::class);
    }

    public function indexHeroFilter($filter, $orderBy, $powerSelected = null): mixed
    {
        $qb = $this->createQueryBuilder('s');

        if($powerSelected){
            $qb->join('s.powers', 'p')
                ->andWhere('p.id = :val')
                ->setParameter('val', $powerSelected);
        }

        $qb->orderBy($filter, $orderBy);

        return $qb->getQuery()
                  ->getResult();
    }
}
