<?php
// src/AppBundle/Entity/User.php

namespace TicketBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="TicketBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var answers
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="user")
     */
    private $answers;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Add answer
     *
     * @param \TicketBundle\Entity\Answer $answer
     *
     * @return User
     */
    public function addAnswer(\TicketBundle\Entity\Answer $answer)
    {
        $this->answers[] = $answer;

        return $this;
    }

    /**
     * Remove answer
     *
     * @param \TicketBundle\Entity\Answer $answer
     */
    public function removeAnswer(\TicketBundle\Entity\Answer $answer)
    {
        $this->answers->removeElement($answer);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}
