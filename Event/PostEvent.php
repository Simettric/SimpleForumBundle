<?php
/**
 * Created by Asier Marqués <asiermarques@gmail.com>
 * Date: 5/5/16
 * Time: 18:40
 */

namespace Simettric\SimpleForumBundle\Event;


use Simettric\SimpleForumBundle\Entity\Post;
use Symfony\Component\EventDispatcher\Event as BaseEvent;

class PostEvent extends BaseEvent{


    const TYPE_CREATED = "sim_forum.post_created";


    /**
     * @var Post
     */
    private $post;

    function __construct(Post $post){
        $this->post = $post;
    }

    /**
     * @return Post
     */
    function getPost(){
        return $this->post;
    }

} 