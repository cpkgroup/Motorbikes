<?php
namespace AuthBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of LoginType
 *
 * @author sr_hosseini <rassoulhosseini at gmail.com>
 */
class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('attr' => array('class' => 'form-control')))
            ->add('password', 'password', array('attr' => array('class' => 'form-control')))
            ->add('submit', 'submit', array('attr' => array('class' => 'btn btn-primary')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

    }

    public function getName()
    {
        return 'authbundle_authentication_login';
    }
}