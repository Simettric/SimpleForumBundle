<?php
/**
 * Created by Asier Marqués <asiermarques@gmail.com>
 * Date: 5/5/16
 * Time: 18:40
 */

namespace Simettric\SimpleForumBundle\Event;


use Simettric\SimpleForumBundle\Entity\Forum;
use Symfony\Component\EventDispatcher\Event as BaseEvent;

class ForumEvent extends BaseEvent{

    const TYPE_CREATED = "sim_forum.forum_created";
    const TYPE_UPDATED = "sim_forum.forum_updated";
    const TYPE_DELETED = "sim_forum.forum_deleted";

    /**
     * @var Forum
     */
    private $forum;

    function __construct(Forum $forum){
        $this->forum = $forum;
    }

    /**
     * @return Forum
     */
    function getPost(){
        return $this->forum;
    }

} 