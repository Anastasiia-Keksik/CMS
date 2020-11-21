<?php

namespace App\Repository;

use App\Entity\Favourites;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Favourites|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favourites|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favourites[]    findAll()
 * @method Favourites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavouritesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favourites::class);
    }

    /**
     * @return Favourites[] Returns an array of Favourites objects
     */

    public function getAll($user)
    {
        return $this->createQueryBuilder('al')
            ->select( 'sp.content as spContent', 'sp.createdAt as spCreatedAt', 'sp.id as spId' ,
                'sp_author.firstName as spFirstName', 'sp_author.lastName as spLastName', 'sp_author.username as spUsername',
                'sp_author.Occupation as spOccupation','sp_author.avatarFileName as spAvatarFileName' , 'sp.likes as spLikes',
                'spc.content as spcContent', 'spc.createdAt as spcCreatedAt', 'spc.id as spcId' ,
                'spc_author.firstName as spcFirstName', 'spc_author.lastName as spclastName', 'spc_author.username as spcUsername',
                'spc_author.avatarFileName as spcAvatarFileName',
                'fp.content as fpContent', 'fp.createdAt as fpCreatedAt', 'fp.id as fpId',
                'fp_author.firstName as fpFirstName', 'fp_author.lastName as fplastName', 'fp_author.username as fpUsername',
                'fp_author.avatarFileName as fpAvatarFileName',
                'ft.createdAt as ftCreatedAt', 'ft.title as ftTitle', 'ft.id as ftId',
                'ft_author.firstName as ftFirstName', 'ft_author.lastName as ftlastName', 'ft_author.username as ftUsername',
                'ft_author.avatarFileName as ftAvatarFileName',
                'ce.id', 'ce.title', 'ce_comic.title',
                'ce_comic_author.firstName as ce_comicFirstName', 'ce_comic_author.lastName as ce_comiclastName', 'ce_comic_author.username as ce_comicUsername',
                'al.createdAt as CreatedAt')
            ->andWhere('al.User = :user')
            ->andWhere('al.SocialPost IS NOT NULL')
            ->orWhere('al.SocialPostComment IS NOT NULL')
            ->orWhere('al.ForumPosts IS NOT NULL')
            ->orWhere('al.ForumTopic IS NOT NULL')
            ->orWhere('al.GalleryPhotos IS NOT NULL')
            ->orWhere('al.ComicEpisodes IS NOT NULL')
            ->leftJoin('al.SocialPost', 'sp')
            ->leftJoin('sp.Account', 'sp_author')
            ->leftJoin('al.SocialPostComment', 'spc')
            ->leftJoin('spc.author', 'spc_author')
            ->leftJoin('al.ForumPosts', 'fp')
            ->leftJoin('fp.Author', 'fp_author')
            ->leftJoin('al.ForumTopic', 'ft')
            ->leftJoin('ft.author', 'ft_author')
            ->leftJoin('al.ComicEpisodes', 'ce')
            ->leftJoin('ce.comic', 'ce_comic')
            ->leftJoin('ce_comic.Author','ce_comic_author')
            ->leftJoin('al.GalleryPhotos', 'gp')//?????? co tu trzeba bedzie dodac? Galerie Usera?
            ->leftJoin('al.PostsLikes', 'pl')
            ->leftJoin('pl.Post', 'likes_post')
            ->setParameter('user', $user)
            ->orderBy('al.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

     /**
      * @return Favourites[] Returns an array of Favourites objects
      */

    public function getSM($user)
    {
        return $this->createQueryBuilder('sm')
            ->andWhere('sm.User = :user')
            ->andWhere('sm.SocialPost IS NOT NULL')
            ->orWhere('sm.SocialPostComment IS NOT NULL')
            ->setParameter('user', $user)
            ->orderBy('sm.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Favourites[] Returns an array of Favourites objects
     */

    public function getGallery($user)
    {
        return $this->createQueryBuilder('sm')
            ->andWhere('sm.User = :user')
            ->andWhere('sm.GalleryPhotos IS NOT NULL')
            ->setParameter('user', $user)
            ->orderBy('sm.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Favourites[] Returns an array of Favourites objects
     */

    public function getForums($user)
    {
        return $this->createQueryBuilder('fr')
            ->select( 'fp.content as fpContent', 'fp.createdAt as fpCreatedAt', 'fp.id as fpId',
                'fp_author.firstName as fpFirstName', 'fp_author.lastName as fplastName', 'fp_author.username as fpUsername',
                'fp_author.avatarFileName as fpAvatarFileName',
                'ft.createdAt as ftCreatedAt', 'ft.title as ftTitle', 'ft.id as ftId',
                'ft_author.firstName as ftFirstName', 'ft_author.lastName as ftlastName', 'ft_author.username as ftUsername',
                'ft_author.avatarFileName as ftAvatarFileName',
                'fr.createdAt as CreatedAt')
            ->andWhere('fr.User = :user')
            ->andWhere('fr.ForumPosts IS NOT NULL')
            ->orWhere('fr.ForumTopic IS NOT NULL')
            ->leftJoin('fr.ForumPosts', 'fp')
            ->leftJoin('fp.Author', 'fp_author')
            ->leftJoin('fr.ForumTopic', 'ft')
            ->leftJoin('ft.author', 'ft_author')
            ->setParameter('user', $user)
            ->orderBy('fr.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Favourites[] Returns an array of Favourites objects
     */

    public function getArt($user)
    {
        return $this->createQueryBuilder('sm')
            ->andWhere('sm.User = :user')
            ->andWhere('sm.ComicEpisodes IS NOT NULL')
            ->setParameter('user', $user)
            ->orderBy('sm.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Favourites
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
