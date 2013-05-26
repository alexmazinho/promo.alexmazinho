<?php
namespace Promo\Bundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Promo\Bundle\Entity\EntityImatge;

class FormProducte extends AbstractType {

	protected $options;
	
	public function __construct(array $options = null)
	{
		$this->options = $options;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		
		$builder->add('id', 'hidden');
		
		$builder->add('imatgeportada', new FormImatge());
		
		$builder->add('nom', 'text');
		
		$builder->add('categoria', 'entity', array(
    		'class' => 'PromoBundle:EntityCategoria',
			'query_builder' => function(EntityRepository $er) {
				return $er->createQueryBuilder('c')
				->orderBy('c.nom', 'ASC');
			},
			'required' => true,
			'expanded' => false,
			'multiple' => false,
		));

		$builder->add('especificacions', 'textarea',
				array('attr' =>array('rows' => '8' )));
		
		$builder->add('preus', 'textarea',
				array('attr' =>array('rows' => '8' )));
		
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Promo\Bundle\Entity\EntityProducte',
		));
	}
	
	public function getName()
	{
		return 'producte';
	}

}
