<?php
/**
 * Created by Asier Marqués <asiermarques@gmail.com>
 * Date: 4/5/16
 * Time: 17:49
 */

namespace Simettric\SimpleForumBundle\Controller;


use Simettric\SimpleForumBundle\Entity\PostReply;
use Simettric\SimpleForumBundle\Event\PostReplyEvent;
use Simettric\SimpleForumBundle\Form\PostReplyType;
use Simettric\SimpleForumBundle\Repository\PostReplyRepository;
use Simettric\SimpleForumBundle\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/reply")
 */
class ReplyController extends Controller{



    /**
     * @Route("/{slug}-{id}/{reply_id}", name="sim_forum_post_reply_view", requirements={"slug": "[^/\\]+", "id": "\d+" })
     */
    public function indexAction($reply_id, Request $request)
    {

        /**
         * @var $item PostReply
         */
        if(!$item = $this->getRepository()->find($reply_id)){
            throw $this->createNotFoundException();
        }



        $reply = new PostReply();
        $reply->setUser($this->getUser());
        $reply->setPost($item->getPost());
        $reply->setReply($item);
        $reply->setClientIp($request->getClientIp());

        $form = $this->createForm(PostReplyType::class, $reply);

        if($request->getMethod() == Request::METHOD_POST){

            $form->handleRequest($request);
            if($form->isValid()){



                $reply->setCreated(new \DateTime());
                $this->getDoctrine()->getManager()->persist($reply);

                $item->getPost()->setLastReply($reply);
                $item->getPost()->setUpdated(new \DateTime());
                $this->getDoctrine()->getManager()->persist($item->getPost());

                $item->getPost()->getForum()->setUpdated(new \DateTime());
                $this->getDoctrine()->getManager()->persist($item->getPost()->getForum());

                $this->getDoctrine()->getManager()->flush();

                $this->get("event_dispatcher")->dispatch(PostReplyEvent::TYPE_CREATED, new PostReplyEvent($reply));


                $this->addFlash("success", $this->get("translator")->trans("reply_created", array(), "sim_forum"));

                return $this->redirect($this->generateUrl("sim_forum_post_reply_view", ["id"=>$item->getPost()->getId(), "slug"=>$item->getPost()->getSlug(), "reply_id" => $item->getId()]));

            }

        }

        $pagination = $this->getRepository()->createPagination(
            $this->getRepository()->getRepliesQB($item)->getQuery(),
            $this->get('knp_paginator'),
            array("rr.id"=>"ASC"),
            $request->get("page", 1)
        );

        return $this->render('SimettricSimpleForumBundle:Reply:detail.html.twig', array(
            "item"       => $item,
            "pagination" => $pagination,
            "form" => $form->createView()
        ));

    }


    /**
     * @return PostRepository
     */
    private function getPostRepository(){
        return $this->getDoctrine()->getManager()->getRepository("SimettricSimpleForumBundle:Post");
    }

    /**
     * @return PostReplyRepository
     */
    private function getRepository(){
        return $this->getDoctrine()->getManager()->getRepository("SimettricSimpleForumBundle:PostReply");
    }

} 