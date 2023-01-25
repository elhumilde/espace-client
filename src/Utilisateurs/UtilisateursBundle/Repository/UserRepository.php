<?php
/**
 * Created by PhpStorm.
 * User: a.skalli
 * Date: 05/10/2018
 * Time: 09:23
 */

namespace Utilisateurs\UtilisateursBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getActive()
    {
        // Comme vous le voyez, le délais est redondant ici, l'idéale serait de le rendre configurable via votre bundle
        $delay = new \DateTime();
        $delay->setTimestamp(strtotime('2 minutes ago'));

        $qb = $this->createQueryBuilder('u')
            ->where('u.lastActivity > :delay')
            ->setParameter('delay', $delay)
        ;

        return $qb->getQuery()->getResult();
    }
}