<?php

namespace Simettric\SimpleForumBundle\Controller;

use Simettric\SimpleForumBundle\Entity\Forum;
use Simettric\SimpleForumBundle\Event\ForumEvent;
use Simettric\SimpleForumBundle\Form\ForumType;
use Simettric\SimpleForumBundle\Repository\ForumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin-forum")
 */
class AdminForumController extends Controller
{
    /**
     * @Route("/create", name="sim_forum_create")
     */
    public function createAction(Request $request)
    {


        $item = new Forum();

        $form = $this->createForm(ForumType::class, $item);

        if($request->getMethod() == Request::METHOD_POST){

            $form->handleRequest($request);
            if($form->isValid()){

                $item->setCreated(new \DateTime());
                $item->setUpdated(new \DateTime());

                $this->getDoctrine()->getManager()->persist($item);
                $this->getDoctrine()->getManager()->flush();

                $this->get("event_dispatcher")->dispatch(ForumEvent::TYPE_CREATED, new ForumEvent($item));


                $this->addFlash("success", $this->get("translator")->trans("forum_created", "sim_forum"));

                return $this->redirect(
                    $this->generateUrl("sim_forum_index")
                );

            }

        }


        return $this->render('SimettricSimpleForumBundle:Admin:create_forum.html.twig', array(
            "item" => $item,
            "form" => $form->createView()
        ));

    }

    /**
     * @Route("/{id}/edit", name="sim_forum_edit")
     */
    public function editAction($id, Request $request)
    {

        /**
         * @var $item Forum
         */
        if(!$item = $this->getRepository()->find($id)){
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ForumType::class, $item);

        if($request->getMethod() == Request::METHOD_POST){

            $form->handleRequest($request);
            if($form->isValid()){

                $item->setUpdated(new \DateTime());

                $this->getDoctrine()->getManager()->persist($item);
                $this->getDoctrine()->getManager()->flush();

                $this->get("event_dispatcher")->dispatch(ForumEvent::TYPE_UPDATED, new ForumEvent($item));

                $this->addFlash("success", $this->get("translator")->trans("forum_updated", "sim_forum"));

                return $this->redirect(
                    $this->generateUrl("sim_forum_index")
                );

            }

        }


        return $this->render('SimettricSimpleForumBundle:Admin:edit_forum.html.twig', array(
            "item" => $item,
            "form" => $form->createView()
        ));

    }

    /**
     * @Route("/{id}/remove", name="sim_forum_remove")
     */
    public function removeAction($id, Request $request)
    {

        $items = array();
        return $this->render('SimettricSimpleForumBundle:Admin:create.html.twig', array(
            "items" => $items
        ));

    }

    /**
     * @return ForumRepository
     */
    private function getRepository(){
        return $this->getDoctrine()->getManager()->getRepository("SimettricSimpleForumBundle:Forum");
    }
}
