<?php
/**
 * Created by Asier Marqués <asiermarques@gmail.com>
 * Date: 11/5/16
 * Time: 17:44
 */

namespace Simettric\SimpleForumBundle\Controller;


use Simettric\SimpleForumBundle\Entity\Post;
use Simettric\SimpleForumBundle\Event\PostSubscriptionEvent;
use Simettric\SimpleForumBundle\Interfaces\UserInterface;
use Simettric\SimpleForumBundle\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/user/subscriptions")
 */
class UserSubscriptionController extends Controller{

    /**
     * @Route("/",  name="sim_forum_post_subscriptions")
     */
    public function indexAction( Request $request){

        $pagination = $this->getRepository()->createPagination(
            $this->getRepository()->getSubscribedToQB($this->getUser())->getQuery(),
            $this->get('knp_paginator'),
            array("p.id"=>"ASC"),
            $request->get("page", 1)
        );



        return $this->render('SimettricSimpleForumBundle:User:subscriptions.html.twig', array(
            "pagination" => $pagination
        ));

    }



    /**
     * @Route("/to-{id}",  name="sim_forum_post_subscribe")
     */
    public function subscribeAction($id, Request $request){


        /**
         * @var $item Post
         */
        if(!$item = $this->getRepository()->find($id))
            throw $this->createNotFoundException();

        if(!$this->getUser() instanceof UserInterface)
            throw $this->createAccessDeniedException();


        $em = $this->getDoctrine()->getManager();

        if(!$this->getRepository()->isSubscribed($this->getUser(), $item)){

            $item->addSubscriber($this->getUser());
            $event_name = PostSubscriptionEvent::TYPE_SUBSCRIBED;
            $flash_message = $this->get("translator")->trans("post_subscription_created", array(), "sim_forum");

        }else{

            $item->removeSubscriber($this->getUser());
            $event_name = PostSubscriptionEvent::TYPE_UNSUBSCRIBED;
            $flash_message = $this->get("translator")->trans("post_subscription_removed", array(), "sim_forum");

        }

        $em->persist($item);
        $em->flush();



        $this->get("event_dispatcher")
            ->dispatch($event_name, new PostSubscriptionEvent($item, $this->getUser()));


        $this->addFlash("success", $flash_message);

        return $this->redirect($request->headers->get("referer"));


    }

    /**
     * @return PostRepository
     */
    private function getRepository(){
        return $this->getDoctrine()->getManager()->getRepository("SimettricSimpleForumBundle:Post");
    }

} 