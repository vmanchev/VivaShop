<?php

namespace Viva\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Viva\ShopBundle\Entity\Category;
use Viva\ShopBundle\Entity\CategoryRepository;
use Viva\ShopBundle\Form\CategoryType;

/**
 * Categories management
 * 
 * We are using the categories to organise the products catalogue.
 */
class CategoryController extends Controller
{
    /**
     * List all categories
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()
                           ->getRepository('Viva\ShopBundle\Entity\Category');
        
        $categories = $repository->getAll();
        
        return $this->render('VivaShopBundle:Category:index.html.twig', array(
            'categories' => $categories
        ));
    }

    /**
     * Create a new category
     */
    public function createAction()
    {
        /**
         * @var \Viva\ShopBundle\Entity\Category
         */
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);
        
        $form->handleRequest($this->getRequest());
        
        if($form->isValid()){
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add(
                    'notice', '"'.$category->getName().'" category has been created successfully!'
            );           
            
            return $this->redirect($this->generateUrl('viva_shop_category_index'));
        }
        
        return $this->render('VivaShopBundle:Category:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Edit a category
     */
    public function editAction()
    {
        //make sure we have a valid integer
        $id = (int) $this->getRequest()->get('id');
        
        if($id <= 0)
        {
            $this->get('session')->getFlashBag()->add(
                    'error', 'Invalid category id!'
            );           
            
            return $this->redirect($this->generateUrl('viva_shop_category_index'));            
        }
        
        //try find the category
        $repository = $this->getDoctrine()
                           ->getRepository('Viva\ShopBundle\Entity\Category');
        
        $category = $repository->find($id);
        
        if(!$category)
        {
            $this->get('session')->getFlashBag()->add(
                    'error', 'Non-existing category!'
            );           
            
            return $this->redirect($this->generateUrl('viva_shop_category_index'));                        
        }
        
        //initialise the form and bind with the entity
        $form = $this->createForm(new CategoryType, $category);
        
        //handle form submittion
        $form->handleRequest($this->getRequest());
        
        if($form->isValid())
        {
            //update the category
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            
            //create a notice
            $this->get('session')->getFlashBag()->add(
                    'notice', 'The "'.$category->getName().'" category was updated successfully!'
            );       
            
            return $this->redirect($this->generateUrl('viva_shop_category_index'));
        }
        
        return $this->render('VivaShopBundle:Category:edit.html.twig', array(
            'form' => $form->createView(),
            'category' => $category
        ));
    }

    /**
     * Delete a category
     */
    public function deleteAction()
    {
        //make sure we have a valid integer
        $id = (int) $this->getRequest()->get('id');
        
        if($id <= 0)
        {
            $this->get('session')->getFlashBag()->add(
                    'error', 'Invalid category id!'
            );           
            
            return $this->redirect($this->generateUrl('viva_shop_category_index'));            
        }
        
        //try find the category
        $repository = $this->getDoctrine()
                           ->getRepository('Viva\ShopBundle\Entity\Category');
        
        $category = $repository->find($id);
        
        if(!$category)
        {
            $this->get('session')->getFlashBag()->add(
                    'error', 'Non-existing category!'
            );           
            
            return $this->redirect($this->generateUrl('viva_shop_category_index'));                        
        }
        
        //determinate when the form is been submited
        if($this->getRequest()->getMethod() == 'POST')
        {            
            //delete the category only in case the Yes button was clicked
            if($this->getRequest()->get('deleteaction') == 1){
                
                //store the category name temporary, as we want to display 
                //a friendly message on the next page
                $_name = $category->getName();

                //do the actual delete
                $em = $this->getDoctrine()->getEntityManager();
                $em->remove($category);
                $em->flush();

                //create and save the notice
                $this->get('session')->getFlashBag()->add(
                        'notice', '"'.$_name.'" category was deleted successfully!'
                );           
            }
            
            //no matter of the clicked button, go back to the category listing page
            return $this->redirect($this->generateUrl('viva_shop_category_index'));                                    
        }
        
        return $this->render('VivaShopBundle:Category:delete.html.twig', array(
            'category' => $category
        ));
    }

}
