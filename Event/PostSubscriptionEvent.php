<?php
/**
 * Created by Asier Marqués <asiermarques@gmail.com>
 * Date: 5/5/16
 * Time: 18:40
 */

namespace Simettric\SimpleForumBundle\Event;


use Simettric\SimpleForumBundle\Entity\Post;
use Simettric\SimpleForumBundle\Interfaces\UserInterface;
use Symfony\Component\EventDispatcher\Event as BaseEvent;

class PostSubscriptionEvent extends BaseEvent{


    const TYPE_SUBSCRIBED   = "sim_forum.post_subscribed";
    const TYPE_UNSUBSCRIBED = "sim_forum.post_unsubscribed";

    /**
     * @var Post
     */
    private $post;

    /**
     * @var UserInterface
     */
    private $user;

    function __construct(Post $post, UserInterface $user){
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * @return Post
     */
    function getPost(){
        return $this->post;
    }

    /**
     * @return UserInterface
     */
    function getUser(){
        return $this->user;
    }

} 