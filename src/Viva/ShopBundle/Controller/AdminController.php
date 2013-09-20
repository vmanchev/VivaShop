<?php

namespace Viva\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Viva\ShopBundle\Entity\Admin;
use Viva\ShopBundle\Form\AdminType;

/**
 * Admin area entry point
 * 
 * Admin user login, logout, forgot password, profile update, admin area home 
 * page (dashboard)
 */
class AdminController extends Controller {

    /**
     * Admin user login
     */
    public function loginAction()
    {
        //initialise the Admin entity
        $admin = new Admin();

        //create the form and bind the entity
        $form = $this->createForm(new AdminType($this->get('security.context')), $admin, array(
            'action' => '/admin/login_check'
        ));

        //remove the unnecessary fields
        $form->remove('name')
                ->remove('submit_forgot')
                ->remove('submit_profile');

        //get the request
        $request = $this->getRequest();

        //get the session
        $session = $request->getSession();


        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
        {
            $error = $request->attributes->get(
                    SecurityContext::AUTHENTICATION_ERROR
            );
        } else
        {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        //render the login template, pass the form and error, if any
        return $this->render(
                        'VivaShopBundle:Admin:login.html.twig', array(
                    'error' => ($error) ? $error->getMessage() : '',
                    'form' => $form->createView()
                        )
        );
    }

    /**
     * Admin user - forgot password
     */
    public function forgotAction()
    {
        //initialise the Admin entity
        $admin = new Admin();

        //create the form and bind the entity
        $form = $this->createForm(new AdminType($this->get('security.context')), $admin, array(
            'action' => $this->generateUrl('viva_shop_admin_forgot')
        ));

        //remove the unnecessary fields
        $form->remove('name')
                ->remove('password')
                ->remove('submit_login')
                ->remove('submit_profile');

        //handle the form submittion
        $form->handleRequest($this->getRequest());

        /**
         * If the form is valid:
         * 1. Lookup the user account
         * 2. Generate a new password.
         * 3. Encode the password.
         * 4. Assign the password to the user.
         * 5. Send an email message
         */
        if ($form->isValid())
        {

            //1. Lookup the user
            $admin = $this->getDoctrine()
                    ->getRepository('VivaShopBundle:Admin')
                    ->findOneBy(
                    array(
                        'username' => $admin->getUsername()
                    )
            );

            if (empty($admin))
            {
                $errors = array(array('message' => 'Invalid username'));
            } else
            {
                $encoder_factory = $this->get('security.encoder_factory');
                $encoder = $encoder_factory->getEncoder($admin);

                //2. generate new password
                $plain_password = $admin->generateUniqueString(8);

                //3. encode the password
                $encoded_password = $encoder->encodePassword($plain_password, $admin->getSalt());

                //4. assign the new password to the user and update the user record
                $admin->setPassword($encoded_password);

                $em = $this->getDoctrine()->getManager();
                $em->persist($admin);
                $em->flush();

                //send an email message
                $message = \Swift_Message::newInstance()
                        ->setSubject('[VivaShop] New password')
                        ->setFrom($this->container->parameters['mailer_user'])
                        ->setTo($admin->getUsername())
                        ->setBody(
                        $this->renderView(
                                'VivaShopBundle:Emails:admin_forgot_password.txt.twig', array(
                            'name' => $admin->getName(),
                            'password' => $plain_password
                                )
                        )
                        )
                ;

                $this->get('mailer')->send($message);

                $this->get('session')->getFlashBag()->add(
                        'notice', "Your password has been changed successfully!<br />Check your Inbox for details."
                );

                return $this->redirect($this->generateUrl('viva_shop_admin_login'));
            }
        } else
        {
            $errors = $form->getErrors();
        }

        //render the login template, pass the form and error, if any
        return $this->render(
                        'VivaShopBundle:Admin:forgot.html.twig', array(
                    'errors' => (isset($errors)) ? $errors : '',
                    'form' => $form->createView()
                        )
        );
    }

    /**
     * Admin area homepage
     */
    public function indexAction()
    {
        return $this->render('VivaShopBundle:Admin:index.html.twig');
    }

    /**
     * Admin user - edit profile
     */
    public function profileAction()
    {
        /**
         * @var \Viva\ShopBundle\Entity\Admin
         */
        $admin = $this->getUser();

        $form = $this->createForm(new AdminType($this->get('security.context')), $admin);

        $form->remove('submit_login')
                ->remove('submit_forgot');

        $form->handleRequest($this->getRequest());

        if ($form->isValid())
        {
            $encoder_factory = $this->get('security.encoder_factory');
            $encoder = $encoder_factory->getEncoder($admin);

            //encode the password
            $encoded_password = $encoder->encodePassword($admin->getPassword(), $admin->getSalt());

            //assign the new password to the user and update the user record
            $admin->setPassword($encoded_password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();

            //refresh the entity to see the new data
            $em->refresh($admin);

            $this->get('session')->getFlashBag()->add(
                    'notice', 'Your profile was updated successfully!'
            );

            return $this->redirect($this->generateUrl('viva_shop_admin_profile'));
        }

        return $this->render('VivaShopBundle:Admin:profile.html.twig', array(
                    'form' => $form->createView()
        ));
    }

}