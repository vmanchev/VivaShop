<?php

namespace Viva\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Viva\ShopBundle\Entity\Category;
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
        return $this->render('VivaShopBundle:Category:index.html.twig');
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
        return $this->render('VivaShopBundle:Category:edit.html.twig');
    }

    /**
     * Delete a category
     */
    public function deleteAction()
    {
        return $this->render('VivaShopBundle:Category:delete.html.twig');
    }

}
