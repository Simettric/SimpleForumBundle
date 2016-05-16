<?php
/**
 * Created by Asier Marqués <asiermarques@gmail.com>
 * Date: 11/5/16
 * Time: 17:44
 */

namespace Simettric\SimpleForumBundle\Controller;


use Simettric\SimpleForumBundle\Entity\Post;
use Simettric\SimpleForumBundle\Event\PostEvent;
use Simettric\SimpleForumBundle\Event\PostSubscriptionEvent;
use Simettric\SimpleForumBundle\Form\PostType;
use Simettric\SimpleForumBundle\Interfaces\UserInterface;
use Simettric\SimpleForumBundle\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/user")
 */
class UserPostController extends Controller{


    /**
     * @Route("/create-in-{forum_id}", name="sim_forum_post_create")
     */
    public function createAction($forum_id, Request $request)
    {

        if(!$forum = $this->getForumRepository()->find($forum_id)){
            throw $this->createNotFoundException();
        }

        $item = new Post();
        $item->setForum($forum);
        $item->setUser($this->getUser());
        $item->setClientIp($request->getClientIp());

        $form = $this->createForm(PostType::class, $item);



        if($request->getMethod() == Request::METHOD_POST){

            $form->handleRequest($request);
            if($form->isValid()){

                $item->setCreated(new \DateTime());
                $item->setSlug($this->get("sim_forum.slugify")->slugify($item->getTitle()));
                $item->setUpdated(new \DateTime());



                $this->getDoctrine()->getManager()->persist($item);

                $item->getForum()->setUpdated(new \DateTime());

                $this->getDoctrine()->getManager()->persist($item->getForum());

                $this->getDoctrine()->getManager()->flush();

                $this->get("event_dispatcher")->dispatch(PostEvent::TYPE_CREATED, new PostEvent($item));


                $this->addFlash("success", $this->get("translator")->trans("post_created", array(), "sim_forum"));

                return $this->redirect(
                    $this->generateUrl("sim_forum_post",
                        ["slug"=>$item->getSlug(), "id"=>$item->getId()])
                );

            }

        }

        return $this->render('SimettricSimpleForumBundle:Post:create.html.twig', array(
            "forum" => $forum,
            "item" => $item,
            "form"  => $form->createView()
        ));

    }

    /**
     * @return PostRepository
     */
    private function getRepository(){
        return $this->getDoctrine()->getManager()->getRepository("SimettricSimpleForumBundle:Post");
    }

    /**
     * @return ForumRepository
     */
    private function getForumRepository(){
        return $this->getDoctrine()->getManager()->getRepository("SimettricSimpleForumBundle:Forum");
    }

    /**
     * @return PostReplyRepository
     */
    private function getReplyRepository(){
        return $this->getDoctrine()->getManager()->getRepository("SimettricSimpleForumBundle:PostReply");
    }

} 