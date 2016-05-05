<?php
/**
 * Created by Asier Marqués <asiermarques@gmail.com>
 * Date: 5/5/16
 * Time: 18:49
 */

namespace Simettric\SimpleForumBundle\Event\Subscriber;


use Doctrine\ORM\EntityManager;
use Simettric\SimpleForumBundle\Event\PostEvent;
use Simettric\SimpleForumBundle\Event\PostReplyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class StatsEventSubscriber implements  EventSubscriberInterface{

    /**
     * @var EntityManager
     */
    private $em;


    /**
     * @param EntityManager $em
     */
    function __construct(EntityManager $em){
        $this->em = $em;
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
            PostEvent::TYPE_CREATED => 'onPostNumberUpdates',
            PostReplyEvent::TYPE_CREATED => 'onReplyNumberUpdates'
        );


    }

    /**
     * @param PostEvent $event
     */
    public function onPostNumberUpdates(PostEvent $event){


        $forum = $event->getPost()->getForum();

        $forum->setPostCount(
            $this->em->createQueryBuilder()
                ->select("COUNT(p.id)")
                ->from("SimettricSimpleForumBundle:Post", "p")
                ->innerJoin("p.forum", "f")
                ->where("f.id = :forum_id")
                    ->setParameter("forum_id", $forum->getId())
                ->getQuery()->getSingleScalarResult()
        );

        $this->em->persist($forum);
        $this->em->flush();


    }

    /**
     * @param PostReplyEvent $event
     */
    public function onReplyNumberUpdates(PostReplyEvent $event){

        $post  = $event->getReply()->getPost();
        $forum = $post->getForum();

        $post->setReplyCount(
            $this->em->createQueryBuilder()
                ->select("COUNT(r.id)")
                ->from("SimettricSimpleForumBundle:PostReply", "r")
                ->innerJoin("r.post", "p")
                ->where("p.id = :post_id")
                ->setParameter("post_id", $post->getId())
                ->getQuery()->getSingleScalarResult()
        );

        $this->em->persist($post);

        $forum->setReplyCount(
            $this->em->createQueryBuilder()
                ->select("COUNT(pr.id)")
                ->from("SimettricSimpleForumBundle:PostReply", "pr")
                ->innerJoin("pr.post", "p")
                ->innerJoin("p.forum", "f")
                ->where("f.id = :forum_id")
                ->setParameter("forum_id", $forum->getId())
                ->getQuery()->getSingleScalarResult()
        );

        $this->em->persist($forum);

        $this->em->flush();


    }


}