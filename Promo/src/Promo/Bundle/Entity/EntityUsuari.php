<?php
namespace Promo\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuaris")
 * 
 * @author alex
 *
 */
class EntityUsuari {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */	
	protected $usuari;	
	
	/**
	 * @ORM\Column(name="mail",type="string", length=50)
	 * @Assert\Email()
	 */
	protected $mail;	// Mail 

	/**
	 * @ORM\Column(type="string", length=40)
	 * @Assert\NotBlank()
	 */
	protected $pwd;
	
	/**
	 * @ORM\Column(type="string", length=40, nullable=true)
	 */
	protected $recoverytoken;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $recoveryexpiration;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $lastaccess;

	
	public function __construct() {
		$this->forceupdate = true;
	}
	
	public function __toString() {
		return $this->user;
	}

    /**
     * Set usuari
     *
     * @param integer $usuari
     * @return integer
     */
    public function setUsuari($usuari)
    {
        $this->usuari = $usuari;
    
        return $this;
    }

    /**
     * Get usuari
     *
     * @return integer 
     */
    public function getUsuari()
    {
        return $this->usuari;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return EntityUsuari
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    
        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set pwd
     *
     * @param string $pwd
     * @return EntityUsuari
     */
    public function setPwd($pwd)
    {
        $this->pwd = $pwd;
    
        return $this;
    }

    /**
     * Get pwd
     *
     * @return string 
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * Set recoverytoken
     *
     * @param string $recoverytoken
     * @return string
     */
    public function setRecoverytoken($recoverytoken)
    {
        $this->recoverytoken = $recoverytoken;
    
        return $this;
    }

    /**
     * Get recoverytoken
     *
     * @return string 
     */
    public function getRecoverytoken()
    {
        return $this->recoverytoken;
    }

    /**
     * Set recoveryexpiration
     *
     * @param \DateTime $recoveryexpiration
     * @return \DateTime
     */
    public function setRecoveryexpiration($recoveryexpiration)
    {
        $this->recoveryexpiration = $recoveryexpiration;
    
        return $this;
    }

    /**
     * Get recoveryexpiration
     *
     * @return \DateTime 
     */
    public function getRecoveryexpiration()
    {
        return $this->recoveryexpiration;
    }

    /**
     * Set lastaccess
     *
     * @param \DateTime $lastaccess
     * @return \DateTime
     */
    public function setLastaccess($lastaccess)
    {
        $this->lastaccess = $lastaccess;
    
        return $this;
    }

    /**
     * Get lastaccess
     *
     * @return \DateTime 
     */
    public function getLastaccess()
    {
        return $this->lastaccess;
    }
}