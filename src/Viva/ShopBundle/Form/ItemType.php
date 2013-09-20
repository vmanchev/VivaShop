<?php

namespace Viva\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemType extends AbstractType {


    /**
     * Bulld the form
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text')
                ->add('description', 'textarea')
                ->add('category', 'entity', array(
                    'class' => 'VivaShopBundle:Category',
                    'property' => 'name',
                    'empty_value' => '---select---',
                    'label' => 'Category'
                ))
                ->add('price', 'money', array(
                    'currency' => 'false'
                ))
                ->add('file', 'file', array(
                    'label' => 'Item image'
                ))
                ->add('submit', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Viva\ShopBundle\Entity\Item'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'viva_shopbundle_item';
    }

}
