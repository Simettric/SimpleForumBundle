<?php
/**
 * Created by Asier Marqués <asiermarques@gmail.com>
 * Date: 11/5/16
 * Time: 0:53
 */

namespace Simettric\SimpleForumBundle\Event\Subscriber;


use Doctrine\ORM\EntityManager;
use Simettric\SimpleForumBundle\Event\PostReplyEvent;
use Simettric\SimpleForumBundle\Repository\PostRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\TranslatorInterface;

class NotifyOnReplySubscriber implements  EventSubscriberInterface{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var TranslatorInterface
     */
    private $translator;


    private $from_email_address;

    /**
     * @param EntityManager $em
     */
    function __construct(ContainerInterface $container){

        $this->em                   = $container->get("doctrine.orm.entity_manager");
        $this->postRepository       = $this->em->getRepository("SimettricSimpleForumBundle:Post");
        $this->twig                 = $container->get("twig");
        $this->mailer               = $container->get("mailer");
        $this->translator           = $container->get("translator");
        $this->from_email_address   = $container->getParameter("sim_forum.mailing.from_address");

    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {

        return array(
            PostReplyEvent::TYPE_CREATED => 'onReplyCreated'
        );


    }

    public function onReplyCreated(PostReplyEvent $event){



        /**
         * @var $message \Swift_Message
         */
        $message = \Swift_Message::newInstance($this->translator->trans("email.subject.new_reply_in_post", array("%post_title%"=>$event->getReply()->getPost()->getTitle()), "sim_forum"))
            ->setFrom($this->from_email_address)
            ->setBody($this->twig->render("SimettricSimpleForumBundle:Email:replyOnSubscribedPost.txt.twig", array(
                "reply" => $event->getReply()
            )));


        $subscribers = false;

        if($event->getReply()->getPost()->getUser()->getId() != $event->getReply()->getUser()->getId()){

            $message->setBcc(array($event->getReply()->getPost()->getUser()->getEmail() => $event->getReply()->getPost()->getUser()->getDisplayName()));

        }

        if($event->getReply()->getReply()){

            if($event->getReply()->getReply()->getUser()->getId() != $event->getReply()->getUser()->getId()){

                $message->setBcc(array($event->getReply()->getReply()->getUser()->getEmail() => $event->getReply()->getReply()->getUser()->getDisplayName()));

            }
        }


        if($subscribers){
           $this->mailer->send($message);
        }

    }

} 