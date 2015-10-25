<?php 
namespace UserBundle\Entity;

use Doctrine\ORM\EntityRepository;


class UserRepository extends EntityRepository implements \Symfony\Component\Security\Core\User\UserProviderInterface
{

    public function loadUserByUsername($username)
    {
        $user = $this->getAdmin($username);
        
        return $user;
    }

    public function refreshUser(\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        $user = $this->find($user->getId());

        return $user;
    }

    public function getAdmin($username)
    {
        $qb = $this->createQueryBuilder('u');
        try{
            $out = $qb
                ->where($qb->expr()->eq('u.email', ':username'))
                ->andWhere($qb->expr()->eq('u.status', true))
                ->setParameter('username',$username)
                ->getQuery()
                ->useResultCache(true)
                ->getSingleResult();

        }catch (\Exception $e){
            return null;
        }

        return $out;
    }
    public function supportsClass($class)
    {
        return is_subclass_of($class, 'UserBundle\Entity\User');
    }
	
	
}