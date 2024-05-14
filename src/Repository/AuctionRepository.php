<?php

namespace App\Repository;

use App\Entity\Auction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Auction>
 *
 * @method Auction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Auction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Auction[]    findAll()
 * @method Auction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuctionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auction::class);
    }

    public function numberOfAuctions(){
        $entitymanager=$this->getEntityManager();
        $query= $entitymanager->createQuery("SELECT COUNT(a) FROM APP\Entity\Auction a");
        return $query->getSingleScalarResult();

    }

    public function findByUserId(int $userId): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT p FROM App\Entity\Product p WHERE p.idUser = :userId'
        )->setParameter('userId', $userId);

       
        $products = $query->getResult();

        $productsArray = [];
        foreach ($products as $product) {
            $productsArray[$product->getTitle()] = $product->getIdProduct();
        }

        return $productsArray;
    }

    public function SEARCH(string $nom): array{
        $manager = $this->getEntityManager();
        $req = $manager->createQuery('SELECT a FROM App\Entity\Auction a WHERE a.nom LIKE :idF')
        ->setParameter('idF','%' . $nom . '%');
        $result = $req->getResult();
    
        if (empty($result)) {
            //$manager = $this->getEntityManager();
            //$req = $manager->createQuery('SELECT f FROM App\Entity\Forum f');
            //$result = $req->getResult();
           
            $auction = new Auction();
            $auction->setNom("No records");
            $result[] = $auction;
        }

        return $result;
    }


//    /**
//     * @return Auction[] Returns an array of Auction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Auction
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
