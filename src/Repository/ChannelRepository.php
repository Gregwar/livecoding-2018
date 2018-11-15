<?php

namespace App\Repository;

use App\Entity\Channel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Category;
use App\Entity\User;

/**
 * @method Channel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Channel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Channel[]    findAll()
 * @method Channel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChannelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Channel::class);
    }

    public function getChannelsForCategory(Category $category)
    {
        return $this->createQueryBuilder('c')
            ->andWhere(':category MEMBER OF c.categories')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllWithLastView(User $user)
    {
        $connection = $this->getEntityManager()->getConnection();
        $query = $connection->prepare(
            'SELECT channel.*, (
                SELECT COUNT(*)
                FROM last_view 
                JOIN message 
                WHERE last_view.user_id = :user 
                AND message.channel_id = channel.id
                AND last_view.channel_id = channel.id
                AND (message.id > last_view.message_id)
                ) as news
            FROM channel
            '
        );
        
        $query->execute(['user' => $user->getId()]);
        return $query->fetchAll();
    }

    // /**
    //  * @return Channel[] Returns an array of Channel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Channel
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
