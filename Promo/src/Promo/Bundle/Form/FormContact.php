<?php
namespace Promo\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FormContact extends AbstractType {

	private $options;
	
	public function __construct(array $options = null)
	{
		$this->options = $options;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nom', 'text');
		$builder->add('adreca', 'text', array('required' => false));
		$builder->add('telf', 'text', array('required' => false));
		$builder->add('email', 'email');
		$builder->add('assumpte', 'text');
		$builder->add('missatge', 'textarea',
				array('attr' =>array('rows' => '12' )));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Promo\Bundle\Entity\EntityContact',
		));
	}
	
	
	public function getName()
	{
		return 'contact';
	}
	
}
