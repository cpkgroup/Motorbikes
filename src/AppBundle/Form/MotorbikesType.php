<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MotorbikesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model')
            ->add('cc')
            ->add('color')
            ->add('weight')
            ->add('price')
			->add('image', 'file', array('label' => 'Image (JPG file)' , 'required'=>false ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Motorbikes',
            'validation_groups' => array('default')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_motorbikes';
    }
}
