<?php

namespace Viva\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Viva\ShopBundle\Entity\Item;

/**
 * Category
 *
 * @ORM\Entity(repositoryClass="Viva\ShopBundle\Entity\CategoryRepository")
 * @ORM\Table(name="category")
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="category")
     */
    private $items;
    
    
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
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    public function getAll()
    {
        
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add items
     *
     * @param \Viva\ShopBundle\Entity\Item $items
     * @return Category
     */
    public function addItem(\Viva\ShopBundle\Entity\Item $items)
    {
        $this->items[] = $items;
    
        return $this;
    }

    /**
     * Remove items
     *
     * @param \Viva\ShopBundle\Entity\Item $items
     */
    public function removeItem(\Viva\ShopBundle\Entity\Item $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }
}