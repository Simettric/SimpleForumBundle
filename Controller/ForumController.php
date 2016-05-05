<?php

namespace Simettric\SimpleForumBundle\Controller;

use Simettric\SimpleForumBundle\Repository\ForumRepository;
use Simettric\SimpleForumBundle\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ForumController extends Controller
{
    /**
     * @Route("/", name="sim_forum_index")
     */
    public function indexAction()
    {

        $items = $this->getRepository()->findBy(array(), array("updated"=>"DESC"));

        return $this->render('SimettricSimpleForumBundle:Forum:index.html.twig', array(
            "items" => $items
        ));

    }


    /**
     * @Route("/{slug}", name="sim_forum_detail")
     */
    public function detailAction($slug, Request $request)
    {

        if(!$item = $this->getRepository()->findOneBy(array("slug"=>$slug))){
            throw $this->createNotFoundException();
        }

        $posts_pagination = $this->getPostRepository()->createPagination(
            $this->getPostRepository()->getBaseQB($item)->getQuery(),
            $this->get('knp_paginator'),
            array("p.updated"=>"DESC"),
            $request->get("page", 1)
        );

        return $this->render('SimettricSimpleForumBundle:Forum:detail.html.twig', array(
            "item"       => $item,
            "pagination" => $posts_pagination
        ));

    }


    /**
     * @return ForumRepository
     */
    private function getRepository(){
        return $this->getDoctrine()->getManager()->getRepository("SimettricSimpleForumBundle:Forum");
    }

    /**
     * @return PostRepository
     */
    private function getPostRepository(){
        return $this->getDoctrine()->getManager()->getRepository("SimettricSimpleForumBundle:Post");
    }


}
