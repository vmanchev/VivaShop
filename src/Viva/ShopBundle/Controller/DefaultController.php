<?php

namespace Viva\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VivaShopBundle:Default:index.html.twig');
    }
}
