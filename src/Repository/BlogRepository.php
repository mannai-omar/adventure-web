<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blog>
 *
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }
    /**
     * Find blogs by titre.
     *
     * @param string $titre
     * @return Blog[] Returns an array of Blog objects
     */
    public function findByTitre(string $titre): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.titre LIKE :titre')
            ->setParameter('titre', '%' . $titre . '%')
            ->orderBy('b.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
