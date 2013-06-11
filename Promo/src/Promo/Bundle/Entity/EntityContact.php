<?php
namespace Promo\Bundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\MaxLength;

class EntityContact {
	protected $nom;

	protected $adreca;
	
	protected $telf;
	
	protected $email;

	protected $assumpte;

	protected $missatge;
	
	public function getNom()
	{
		return $this->nom;
	}
	
	public function setNom($nom)
	{
		$this->nom = $nom;
	}
	
	public function getAdreca()
	{
		return $this->adreca;
	}
	
	public function setAdreca($adreca)
	{
		$this->adreca = $adreca;
	}

	public function getTelf()
	{
		return $this->telf;
	}
	
	public function setTelf($telf)
	{
		$this->telf = $telf;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function setEmail($email)
	{
		$this->email = $email;
	}
	
	public function getAssumpte()
	{
		return $this->assumpte;
	}
	
	public function setAssumpte($assumpte)
	{
		$this->assumpte = $assumpte;
	}
	
	public function getMissatge()
	{
		return $this->missatge;
	}
	
	public function setMissatge($missatge)
	{
		$this->missatge = $missatge;
	}
	
	public static function loadValidatorMetadata(ClassMetadata $metadata)
	{
		$metadata->addPropertyConstraint('nom', new NotBlank());
		/*$metadata->addPropertyConstraint('telf', new MaxLength(250));*/
		$metadata->addPropertyConstraint('email', new Email(array(
    	'message' => 'Indica una dirección de correo correcta'
		)));
		$metadata->addPropertyConstraint('assumpte', new NotBlank());
		$metadata->addPropertyConstraint('assumpte', new MaxLength(250));
		$metadata->addPropertyConstraint('missatge', new MinLength(2));
	}
}
