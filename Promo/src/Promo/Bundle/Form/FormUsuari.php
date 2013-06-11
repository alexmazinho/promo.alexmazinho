<?php
namespace Promo\Bundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Promo\Bundle\Entity\EntityUsuari;

class FormUsuari extends AbstractType {

	protected $options;
	
	public function __construct(array $options = null)
	{
		$this->options = $options;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		
		$builder->add('usuari', 'hidden');

		$builder->add('mail', 'hidden');
		
		$builder->add('pwd', 'repeated', array(
    			 'type' => 'password',
    			 'required' => true,
				 'invalid_message' => 'Las contraseñas no coinciden'
		));
		
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Promo\Bundle\Entity\EntityUsuari',
		));
	}
	
	public function getName()
	{
		return 'usuari';
	}

}
