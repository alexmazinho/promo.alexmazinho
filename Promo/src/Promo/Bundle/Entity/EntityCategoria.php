<?php
namespace Promo\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 * 
 * @author alex
 *
 */
class EntityCategoria {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;	
	
	/**
	 * @ORM\Column(type="string", length=50)
	 */
	protected $nom;
		
	/**
     * @ORM\OneToOne(targetEntity="EntityImatge")
     * @ORM\JoinColumn(name="imatge", referencedColumnName="id")
     */
	protected $imatge;

	/**
	 * @ORM\OneToMany(targetEntity="EntityCategoria", mappedBy="pare")
	 */
	private $fills;		// Categories filles
	
	/**
	 * @ORM\ManyToOne(targetEntity="EntityCategoria", inversedBy="fills")
	 * @ORM\JoinColumn(name="pare", referencedColumnName="id")
	 */
	private $pare;
	
	/**
	 * @ORM\OneToMany(targetEntity="EntityProducte", mappedBy="categoria")
	 */
	protected $productes;	// Owning side of the relationship

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fills = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return EntityCategoria
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
     * Set imatge
     *
     * @param Promo\Bundle\Entity\EntityImatge $imatge
     * @return EntityCategoria
     */
    public function setImatge(\Promo\Bundle\Entity\EntityImatge $imatge = null)
    {
        $this->imatge = $imatge;
    
        return $this;
    }

    /**
     * Get imatge
     *
     * @return Promo\Bundle\Entity\EntityImatge 
     */
    public function getImatge()
    {
        return $this->imatge;
    }

    /**
     * Add fills
     *
     * @param Promo\Bundle\Entity\EntityCategoria $fills
     * @return EntityCategoria
     */
    public function addFill(\Promo\Bundle\Entity\EntityCategoria $fills)
    {
        $this->fills[] = $fills;
    
        return $this;
    }

    /**
     * Remove fills
     *
     * @param Promo\Bundle\Entity\EntityCategoria $fills
     */
    public function removeFill(\Promo\Bundle\Entity\EntityCategoria $fills)
    {
        $this->fills->removeElement($fills);
    }

    /**
     * Get fills
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFills()
    {
        return $this->fills;
    }

    /**
     * Set pare
     *
     * @param Promo\Bundle\Entity\EntityCategoria $pare
     * @return EntityCategoria
     */
    public function setPare(\Promo\Bundle\Entity\EntityCategoria $pare = null)
    {
        $this->pare = $pare;
    
        return $this;
    }

    /**
     * Get pare
     *
     * @return Promo\Bundle\Entity\EntityCategoria 
     */
    public function getPare()
    {
        return $this->pare;
    }

    /**
     * Add productes
     *
     * @param Promo\Bundle\Entity\EntityProducte $productes
     * @return EntityCategoria
     */
    public function addProducte(\Promo\Bundle\Entity\EntityProducte $productes)
    {
        $this->productes[] = $productes;
    
        return $this;
    }

    /**
     * Remove productes
     *
     * @param Promo\Bundle\Entity\EntityProducte $productes
     */
    public function removeProducte(\Promo\Bundle\Entity\EntityProducte $productes)
    {
        $this->productes->removeElement($productes);
    }

    /**
     * Get productes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProductes()
    {
        return $this->productes;
    }
}