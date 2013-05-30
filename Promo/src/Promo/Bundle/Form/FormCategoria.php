<?php
namespace Promo\Bundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Promo\Bundle\Entity\EntityImatge;

class FormCategoria extends AbstractType {

	protected $options;
	
	public function __construct(array $options = null)
	{
		$this->options = $options;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		
		$builder->add('id', 'hidden');
		
		$builder->add('nom', 'text');
		
		$builder->add('pare', 'entity', array(
    		'class' => 'PromoBundle:EntityCategoria',
			'query_builder' => function(EntityRepository $er) {
				return $er->createQueryBuilder('c')
				->orderBy('c.nom', 'ASC');
			},
			'required' => false,
			'expanded' => false,
			'multiple' => false,
		));
		
		$atributs = array('accept' => 'image/*');
		$builder->add('portada', 'file', array('property_path' => false, 'attr' => $atributs));
		
		/*	$builder->add('imatge', new FormImatge()); */
		
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Promo\Bundle\Entity\EntityCategoria',
		));
	}
	
	public function getName()
	{
		return 'categoria';
	}

}
