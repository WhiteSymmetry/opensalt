<?php

namespace Salt\UserBundle\Repository;

use CftfBundle\Entity\LsDoc;
use Doctrine\ORM\EntityRepository;
use Salt\UserBundle\Entity\User;

/**
 * UserDocAclRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class UserDocAclRepository extends EntityRepository
{
    /**
     * Find an ACL by the document and user
     *
     * @param \CftfBundle\Entity\LsDoc $lsDoc
     * @param \Salt\UserBundle\Entity\User $user
     *
     * @return null|object
     */
    public function findByDocUser(LsDoc $lsDoc, User $user)
    {
        return $this->findOneBy(['lsDoc' => $lsDoc->getId(), 'user' => $user->getId()]);
    }
}
