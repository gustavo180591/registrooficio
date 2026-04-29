<?php

namespace App\Repository;

use App\Entity\Registro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Registro>
 */
class RegistroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registro::class);
    }
/**
     * Método personalizado para buscar registros por oficio y delegación.
     */
    public function buscar($oficio, array $delegaciones, $page = 1, $limit = 12)
    {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.status = :status')
            ->setParameter('status', true);

        // Solo filtrar por oficio si se seleccionó uno
        if ($oficio !== null) {
            $qb->andWhere('r.oficio = :oficio')
               ->setParameter('oficio', $oficio);
        }

        // Solo filtrar por delegación si se seleccionó al menos una
        if (!empty($delegaciones)) {
            $qb->join('r.delegacion', 'd')
               ->andWhere('d.id IN (:delegaciones)')
               ->setParameter('delegaciones', $delegaciones);
        }

        $qb->orderBy('r.name', 'ASC')
           ->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    public function countResults($oficio, array $delegaciones)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.status = :status')
            ->setParameter('status', true);

        if ($oficio !== null) {
            $qb->andWhere('r.oficio = :oficio')
               ->setParameter('oficio', $oficio);
        }

        if (!empty($delegaciones)) {
            $qb->join('r.delegacion', 'd')
               ->andWhere('d.id IN (:delegaciones)')
               ->setParameter('delegaciones', $delegaciones);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Buscar delegaciones donde hay registros con un oficio específico.
     * Útil para mostrar alternativas cuando no hay resultados en la delegación seleccionada.
     */
    public function findDelegacionesWithOficio($oficio): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('DISTINCT d.id, d.name')
            ->join('r.delegacion', 'd')
            ->andWhere('r.status = :status')
            ->andWhere('r.oficio = :oficio')
            ->setParameter('status', true)
            ->setParameter('oficio', $oficio)
            ->orderBy('d.name', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Contar registros por oficio sin filtrar por delegación.
     * Útil para verificar si hay registros de un oficio en cualquier delegación.
     */
    public function countByOficioOnly($oficio): int
    {
        $qb = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.status = :status')
            ->andWhere('r.oficio = :oficio')
            ->setParameter('status', true)
            ->setParameter('oficio', $oficio);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    //    /**
    //     * @return Registro[] Returns an array of Registro objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Registro
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

}
