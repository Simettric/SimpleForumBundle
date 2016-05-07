<?php
/**
 * Created by Asier Marqués <asiermarques@gmail.com>
 * Date: 5/5/16
 * Time: 23:35
 */

namespace Simettric\SimpleForumBundle\Repository;


use Doctrine\ORM\EntityManager;
use Simettric\SimpleForumBundle\Interfaces\SearchRepositoryInterface;

class DoctrineSearchRepository implements SearchRepositoryInterface{


    private $page=1;

    private $limit=10;

    private $total=0;

    private $items=array();

    /**
     * @var EntityManager
     */
    private $em;


    function __construct(EntityManager $entityManager){
        $this->em = $entityManager;
    }


    function getPage()
    {
        return $this->page;
    }

    function getLimit()
    {
        return $this->limit;
    }

    function getTotalResults()
    {
        return $this->total;
    }

    function getResults()
    {
        return $this->items;
    }

    /**
     * @param $search_pattern
     * @param int $page
     * @param int $limit
     * @return $this
     */
    function search($search_pattern, $page=1, $limit=10)
    {

        $this->page  = $page;
        $this->limit = $limit;

        $offset = ($page*$limit)-$limit;
        if($offset<0) $offset = 0;

        $this->total = $this->em->createQueryBuilder()
            ->select("COUNT(p.id)")->from("SimettricSimpleForumBundle:Post", "p")
            ->where("p.title LIKE :search")->setParameter("search", '%'.$search_pattern.'%')
            ->getQuery()->getSingleScalarResult();

        if($this->total){

            $this->items = $this->em->createQueryBuilder()
                ->select("p")->from("SimettricSimpleForumBundle:Post", "p")
                ->innerJoin("p.user", "u")
                ->orderBy("p.updated", "DESC")
                ->where("p.title LIKE :search")->setParameter("search", '%'.$search_pattern.'%')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()->getResult();

        }

        return $this;


    }
}