<?php
namespace Promo\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FormImatge extends AbstractType {

	protected $options;
	
	public function __construct(array $options = null)
	{
		$this->options = $options;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		
		$builder->add('id', 'hidden');
		
		$builder->add('titol', 'text', array(
				            'required' => false));
		
		$builder->add('file', 'file');

	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Promo\Bundle\Entity\EntityImatge',
		));
	}
	
	public function getName()
	{
		return 'imatge';
	}

}
