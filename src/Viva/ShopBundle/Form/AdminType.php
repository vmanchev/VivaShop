<?php

namespace Viva\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdminType extends AbstractType {

    private $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * Bulld the form
     * 
     * Add all of the bind entity properies to the form. Assign validation groups 
     * to different submit buttons as a way to determinate the form type, e.g. 
     * when the submit_login button is clicked, run the validation process, using 
     * the "admin_login" validation group.
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username')
                ->add('name')
                ->add('submit_login', 'submit', array(
                    'validation_groups' => array('admin_login')
                ))
                ->add('submit_forgot', 'submit', array(
                    'validation_groups' => array('admin_forgot')
                ))
                ->add('submit_profile', 'submit', array(
                    'validation_groups' => array('admin_profile')
        ));

        $userRoles = $this->securityContext->getToken()->getRoles();

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use($userRoles) {

                    $form = $event->getForm();

                    $isAdmin = false;

                    if ($userRoles)
                    {

                        foreach ($userRoles as $role)
                        {
                            if ('ROLE_ADMIN' == $role->getRole())
                            {
                                $isAdmin = true;
                                break;
                            }
                        }
                    }

                    if ($isAdmin)
                    {
                        /**
                         * The logged in user will access this form only on the 
                         * edit profile page. Add special password confirmation field.
                         */
                        $form->add('password', 'repeated', array(
                            'type' => 'password',
                            'first_options' => array('label' => 'Password'),
                            'second_options' => array('label' => 'Repeat Password'),
                        ));
                    } else
                    {
                        /**
                         * The user is not logged in. 
                         * Add a single password field, 
                         * this form is part of the login process.
                         */
                        $form->add('password', 'password');
                    }
                });
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Viva\ShopBundle\Entity\Admin',
            'validation_groups' => array('admin_login', 'admin_forgot', 'admin_profile')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'viva_shopbundle_admin';
    }

}
