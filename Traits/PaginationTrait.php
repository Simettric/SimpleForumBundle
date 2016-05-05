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
                              $order_by=array(),
                              $page=1,
                              $limit=10){

        $options = array();
        if(count($order_by)){
            $fields=array_keys($order_by);
            $options["defaultSortFieldName"]     = array_pop($fields);
            $options["defaultSortDirection"] = array_pop($order_by);
        }

        return $paginator->paginate(
            $query,
            $page,
            $limit,
            $options
        );

    }

} 