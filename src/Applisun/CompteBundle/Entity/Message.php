<?php

namespace Applisun\CompteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

use Applisun\CompteBundle\Entity\Category;

/**
 * comment
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="Applisun\CompteBundle\Repository\MessageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Message
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
     * @ORM\Column(name="texte", type="text")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "10",
     *      max = "500",
     *      minMessage = "Votre message doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre message ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $texte;
    
    /**
     * @var Category $category
     *
     * @ORM\ManyToOne(
     *     targetEntity="Applisun\CompteBundle\Entity\Category",
     *     inversedBy="messages"
     * )
     * @ORM\JoinColumn(
     *     name="category_id",
     *     referencedColumnName="id",
     *     onDelete="CASCADE"
     * )
     */
    private $category;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
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
     * Set texte
     *
     * @param string $texte
     *
     * @return comment
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string
     */
    public function getTexte()
    {
        return $this->texte;
    }
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * Set category
     *
     * @param Category $category
     *
     * @return Message
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }
}

