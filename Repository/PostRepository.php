<?php

namespace Simettric\SimpleForumBundle\Repository;
use Simettric\SimpleForumBundle\Entity\Forum;
use Simettric\SimpleForumBundle\Traits\PaginationTrait;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends \Doctrine\ORM\EntityRepository
{

    use PaginationTrait;

    const DEFAULT_LIST_LIMIT = 10;

    function getBaseQB(Forum $forum){

        return $this->createQueryBuilder("p")
            ->innerJoin("p.forum", "f")
            ->where("f.id = :forum_id")
            ->setParameter("forum_id", $forum->getId());

    }


}
