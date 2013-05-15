<?php
namespace Promo\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="productes")
 * 
 * @author alex
 *
 */
class EntityProducte {
	
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
     * @ORM\JoinColumn(name="imatgeportada", referencedColumnName="id")
     */
	protected $imatgePortada;
	
	/**
     * @ORM\ManyToMany(targetEntity="EntityImatge")
     * @ORM\JoinTable(name="imatges_productes",
     *      joinColumns={@ORM\JoinColumn(name="producte", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="imatge", referencedColumnName="id")}
     *      )
     */
	protected $imatges;	// Owning side of the relationship	
	
	/**
	 * @ORM\ManyToOne(targetEntity="EntityCategoria", inversedBy="productes")
	 * @ORM\JoinColumn(name="categoria", referencedColumnName="id")
	 */
	protected $categoria;  // Inverse side relationship
	
	/**
	 * @ORM\Column(type="text")
	 */
	protected $especificacions;
	
	/**
	 * @ORM\Column(type="text")
	 */
	protected $preus;
	
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->imatges = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return EntityProducte
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
     * Set especificacions
     *
     * @param string $especificacions
     * @return EntityProducte
     */
    public function setEspecificacions($especificacions)
    {
        $this->especificacions = $especificacions;
    
        return $this;
    }

    /**
     * Get especificacions
     *
     * @return string 
     */
    public function getEspecificacions()
    {
        return $this->especificacions;
    }

    /**
     * Set preus
     *
     * @param string $preus
     * @return EntityProducte
     */
    public function setPreus($preus)
    {
        $this->preus = $preus;
    
        return $this;
    }

    /**
     * Get preus
     *
     * @return string 
     */
    public function getPreus()
    {
        return $this->preus;
    }

    /**
     * Set imatgePortada
     *
     * @param Promo\Bundle\Entity\EntityImatge $imatgePortada
     * @return EntityProducte
     */
    public function setImatgePortada(\Promo\Bundle\Entity\EntityImatge $imatgePortada = null)
    {
        $this->imatgePortada = $imatgePortada;
    
        return $this;
    }

    /**
     * Get imatgePortada
     *
     * @return Promo\Bundle\Entity\EntityImatge 
     */
    public function getImatgePortada()
    {
        return $this->imatgePortada;
    }

    /**
     * Add imatges
     *
     * @param Promo\Bundle\Entity\EntityImatge $imatges
     * @return EntityProducte
     */
    public function addImatge(\Promo\Bundle\Entity\EntityImatge $imatges)
    {
        $this->imatges[] = $imatges;
    
        return $this;
    }

    /**
     * Remove imatges
     *
     * @param Promo\Bundle\Entity\EntityImatge $imatges
     */
    public function removeImatge(\Promo\Bundle\Entity\EntityImatge $imatges)
    {
        $this->imatges->removeElement($imatges);
    }

    /**
     * Get imatges
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getImatges()
    {
        return $this->imatges;
    }

    /**
     * Set categoria
     *
     * @param Promo\Bundle\Entity\EntityCategoria $categoria
     * @return EntityProducte
     */
    public function setCategoria(\Promo\Bundle\Entity\EntityCategoria $categoria = null)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Get categoria
     *
     * @return Promo\Bundle\Entity\EntityCategoria 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }
}