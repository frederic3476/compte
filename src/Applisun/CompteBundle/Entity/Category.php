<?php

namespace Applisun\CompteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
/**
 * ville
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Applisun\CompteBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;
    
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Applisun\CompteBundle\Entity\Message",
     *     mappedBy="category",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     *
     * @Assert\Valid()    
     */
    private $messages;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return ville
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
    
    /**
     * Add Message
     *
     * @param Message $message
     */
    public function addMesssage(Message $message)
    {
        if ( ! $this->messages->contains($message) ) {
	        $this->messages->add($message);
        }
    }
    
    /**
     * Remove Message
     *
     * @param Message $message
     */
    public function removeMessage(Message $message)
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
        }
    }

    /**
     * Get Messages
     *
     * @return ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }
    
    public function __toString()
    {
        return $this->nom;
    }
}

