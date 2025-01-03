<?php

namespace App\Repository;

use App\Entity\Mission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mission>
 */
class MissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mission::class);
    }

    public function missionFilterStatus($statusSelected = null): mixed
    {
        $qb = $this->createQueryBuilder('m');

        if ($statusSelected == 'historique') {
            $qb->andWhere('m.status IN (:statuses)')
               ->setParameter('statuses', ['TERMINÉ', 'ÉCHOUÉ']);
        } else if ($statusSelected == 'actuel' || $statusSelected === null) {
            $qb->andWhere('m.status IN (:statuses)')
               ->setParameter('statuses', ['EN ATTENTE', 'EN COURS']);
        }

        return $qb->orderBy('m.startAt', 'desc')
                  ->getQuery()
                  ->getResult();
    }

    public function currentOrPendingMission($status): mixed
    {
        $qb = $this->createQueryBuilder('m')
                    ->leftJoin('m.assignedTeam', 't')
                    ->andWhere('t.id is not null');

        if ($status == 'EN ATTENTE') {
            $qb->andWhere('m.status = :status')
               ->setParameter('status',$status );
        } else if ($status == 'EN COURS') {
            $qb->andWhere('m.status = :status')
               ->setParameter('status',$status );
        }

        return $qb->orderBy('m.startAt', 'desc')
                  ->getQuery()
                  ->getResult();
    }
}