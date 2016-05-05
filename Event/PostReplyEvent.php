<?php
/**
 * Created by Asier Marqués <asiermarques@gmail.com>
 * Date: 5/5/16
 * Time: 18:40
 */

namespace Simettric\SimpleForumBundle\Event;


use Simettric\SimpleForumBundle\Entity\PostReply;
use Symfony\Component\EventDispatcher\Event as BaseEvent;

class PostReplyEvent extends BaseEvent{

    const TYPE_CREATED = "sim_forum.reply_created";

    /**
     * @var PostReply
     */
    private $reply;

    function __construct(PostReply $reply){
        $this->reply = $reply;
    }

    /**
     * @return PostReply
     */
    function getReply(){
        return $this->reply;
    }

} 