<?php

namespace Simettric\SimpleForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PostReply
 *
 * @ORM\Table(name="post_reply")
 * @ORM\Entity(repositoryClass="Simettric\SimpleForumBundle\Repository\PostReplyRepository")
 */
class PostReply
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="string", length=255)
     */
    private $body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


    /**
     * @ORM\ManyToOne(targetEntity="\Simettric\SimpleForumBundle\Interfaces\UserInterface")
     */
    protected $user;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return PostReply
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return PostReply
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set user
     *
     * @param \Simettric\SimpleForumBundle\Interfaces\UserInterface $user
     *
     * @return PostReply
     */
    public function setUser(\Simettric\SimpleForumBundle\Interfaces\UserInterface $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Simettric\SimpleForumBundle\Interfaces\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}
