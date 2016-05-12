<?php
/**
 * Created by Asier Marqués <asiermarques@gmail.com>
 * Date: 11/5/16
 * Time: 17:47
 */

namespace Simettric\SimpleForumBundle\Twig;


use Doctrine\ORM\EntityManager;
use Simettric\SimpleForumBundle\Entity\Post;
use Simettric\SimpleForumBundle\Interfaces\UserInterface;
use Simettric\SimpleForumBundle\Repository\PostRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ForumExtension extends \Twig_Extension
{

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var PostRepository
     */
    private $postRepo;


    function __construct(TokenStorageInterface $storage, EntityManager $em){

        if($storage->getToken()){
            $this->user = $storage->getToken()->getUser() instanceof UserInterface ? $storage->getToken()->getUser() : null;
        }

        $this->postRepo = $em->getRepository("SimettricSimpleForumBundle:Post");
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('is_subscribed_to_forum_post', array($this, 'isSubscribedToPost')),
        );
    }

    public function isSubscribedToPost(Post $post)
    {
        //static $subscribed;

        //if($subscribed===null && $this->user){

            $subscribed = $this->postRepo->isSubscribed($this->user, $post);
        //}

        return $subscribed;
    }

    public function getName()
    {
        return 'forum_extension';
    }
}