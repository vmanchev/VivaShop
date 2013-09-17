<?php

namespace Viva\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('password')
            ->add('name')
            ->add('submit', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Viva\ShopBundle\Entity\Client'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'viva_shopbundle_client';
    }
    
    public static function loadValidatorMetadata(ClassMetadata $metadata) {
        $metadata->addPropertyConstraint('email',    new NotBlank("Enter your email address, please!"));
        $metadata->addPropertyConstraint('email',    new Email("Enter a valid email address, please!"));
        $metadata->addPropertyConstraint('password', new NotBlank("Enter your password, please!"));
        $metadata->addPropertyConstraint('name',     new NotBlank("Enter your name, please!"));
    }        
}
