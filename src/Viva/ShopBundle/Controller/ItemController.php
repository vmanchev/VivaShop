<?php

namespace Viva\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Viva\ShopBundle\Entity\Item;
use Viva\ShopBundle\Entity\ItemRepository;
use Viva\ShopBundle\Form\ItemType;

class ItemController extends Controller
{
    public function indexAction()
    {
        $repository = $this->getDoctrine()
                           ->getRepository('Viva\ShopBundle\Entity\Item');
        
        $items = $repository->getAll();
        
        return $this->render('VivaShopBundle:Item:index.html.twig', array(
            'items' => $items
        ));
        
        return $this->render('VivaShopBundle:Item:index.html.twig');
    }
    
    public function createAction()
    {
        /**
         * @var \Viva\ShopBundle\Entity\Item
         */
        $item = new Item();
        $form = $this->createForm(new ItemType(), $item);
        
        $form->handleRequest($this->getRequest());
        
        if($form->isValid()){

            $em = $this->getDoctrine()->getManager();
            
            $em->persist($item);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add(
                    'notice', '"'.$item->getTitle().'" item has been added successfully!'
            );           
            
            return $this->redirect($this->generateUrl('viva_shop_catalog_index'));
        }
        
        
        return $this->render('VivaShopBundle:Item:create.html.twig', array(
            'form' => $form->createView()
        ));        
        
    }
    
    public function editAction()
    {
        //make sure we have a valid integer
        $id = (int) $this->getRequest()->get('id');
        
        if($id <= 0)
        {
            $this->get('session')->getFlashBag()->add(
                    'error', 'Invalid item id!'
            );           
            
            return $this->redirect($this->generateUrl('viva_shop_catalog_index'));            
        }
        
        //try find the item
        $repository = $this->getDoctrine()
                           ->getRepository('Viva\ShopBundle\Entity\Item');
        
        $item = $repository->find($id);
        
        if(!$item)
        {
            $this->get('session')->getFlashBag()->add(
                    'error', 'Non-existing item!'
            );           
            
            return $this->redirect($this->generateUrl('viva_shop_catalog_index'));                        
        }
        
        //initialise the form and bind with the entity
        $form = $this->createForm(new ItemType, $item);
        
        //handle form submittion
        $form->handleRequest($this->getRequest());
        
        if($form->isValid())
        {
            //update the category
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();
            
            //create a notice
            $this->get('session')->getFlashBag()->add(
                    'notice', $item->getTitle().'" was updated successfully!'
            );       
            
            return $this->redirect($this->generateUrl('viva_shop_catalog_index'));
        }
        
        return $this->render('VivaShopBundle:Item:edit.html.twig', array(
            'form' => $form->createView(),
            'item' => $item
        ));
    }
    
    public function deleteAction()
    {
        //make sure we have a valid integer
        $id = (int) $this->getRequest()->get('id');
        
        if($id <= 0)
        {
            $this->get('session')->getFlashBag()->add(
                    'error', 'Invalid item id!'
            );           
            
            return $this->redirect($this->generateUrl('viva_shop_catalog_index'));            
        }
        
        //try find the item
        $repository = $this->getDoctrine()
                           ->getRepository('Viva\ShopBundle\Entity\Item');
        
        $item = $repository->find($id);
        
        if(!$item)
        {
            $this->get('session')->getFlashBag()->add(
                    'error', 'Non-existing item!'
            );           
            
            return $this->redirect($this->generateUrl('viva_shop_catalog_index'));                        
        }
        
        //determinate when the form is been submited
        if($this->getRequest()->getMethod() == 'POST')
        {            
            //delete the category only in case the Yes button was clicked
            if($this->getRequest()->get('deleteaction') == 1){
                
                //store the item name temporary, as we want to display 
                //a friendly message on the next page
                $_name = $item->getTitle();

                //do the actual delete
                $em = $this->getDoctrine()->getEntityManager();
                $em->remove($item);
                $em->flush();

                //create and save the notice
                $this->get('session')->getFlashBag()->add(
                        'notice', '"'.$_name.'" was deleted successfully!'
                );           
            }
            
            //no matter of the clicked button, go back to the items listing page
            return $this->redirect($this->generateUrl('viva_shop_catalog_index'));                                    
        }
        
        return $this->render('VivaShopBundle:Item:delete.html.twig', array(
            'item' => $item
        ));
    }    
    
    
}
