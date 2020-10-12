<?php

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Account) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @return Account[] Returns an array of Account objects
     */
    public function findBySearch($value='', $column, $dir, $start, $length)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.username like :val')
            ->orWhere('a.city like :val')
            ->orWhere('a.country like :val')
            ->orWhere('a.email like :val')
            ->orWhere('a.firstName like :val')
            ->orWhere('a.lastName like :val')
            ->orWhere('a.lastOnline like :val')
            ->orWhere('a.createdAt like :val')
            ->orWhere('a.roles like :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('a.'.$column, $dir)
            ->setFirstResult($start)
            ->setMaxResults($length)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Account[] Returns an array of Account objects
     */
    public function findJustUsernameAndAvatar($id)
    {
        return $this->createQueryBuilder('a')
            ->select(['a.username', 'a.firstName', 'a.lastName', 'a.avatarFileName'])
            ->andWhere('a.id like :id')
            ->setParameter('id', $id)

            ->getQuery()
            ->getOneOrNullResult(1)
            ;
    }

}
