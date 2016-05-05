<?php
/**
 * Created by Asier Marqués <asiermarques@gmail.com>
 * Date: 5/5/16
 * Time: 12:50
 */

namespace Simettric\SimpleForumBundle\Traits;


use Doctrine\ORM\Query;
use Knp\Component\Pager\PaginatorInterface;

trait PaginationTrait {

    /**
     * @param Query $query
     * @param PaginatorInterface $paginator
     * @param int $page
     * @param int $limit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    function createPagination(Query $query,
                              PaginatorInterface $paginator,
                              $page=1,
                              $limit=10){

        return $paginator->paginate(
            $query,
            $page,
            $limit
        );

    }

} 