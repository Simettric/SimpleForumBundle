<?php
/**
 * Created by Asier Marqués <asiermarques@gmail.com>
 * Date: 5/5/16
 * Time: 23:24
 */

namespace Simettric\SimpleForumBundle\Interfaces;


interface SearchRepositoryInterface {


    function getPage();

    function getLimit();

    function getTotalResults();

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    function getResults();

    /**
     * @param $search_pattern
     * @param int $page
     * @param int $limit
     * @return $this
     */
    function search($search_pattern, $page=1, $limit=10);


} 