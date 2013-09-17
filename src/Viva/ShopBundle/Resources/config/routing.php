<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

/**
 * Admin user functionality
 */
$collection->add('viva_shop_homepage', new Route('/', array(
    '_controller' => 'VivaShopBundle:Default:index',
)));

$collection->add('viva_shop_admin_login', new Route('/admin/login', array(
    '_controller' => 'VivaShopBundle:Admin:login',
)));

$collection->add('login_check', new Route('/admin/login_check', array()));
$collection->add('logout', new Route('/admin/logout', array()));

$collection->add('viva_shop_admin_logout', new Route('/admin/logout', array(
    '_controller' => 'VivaShopBundle:Admin:logout',
)));

$collection->add('viva_shop_admin_forgot', new Route('/admin/forgot', array(
    '_controller' => 'VivaShopBundle:Admin:forgot',
)));

$collection->add('viva_shop_admin_profile', new Route('/admin/profile', array(
    '_controller' => 'VivaShopBundle:Admin:profile',
)));

$collection->add('viva_shop_admin_homepage', new Route('/admin', array(
    '_controller' => 'VivaShopBundle:Admin:index',
)));

/**
 * Categories
 */
$collection->add('viva_shop_category_index', new Route('/admin/category', array(
    '_controller' => 'VivaShopBundle:Category:index'
)));

$collection->add('viva_shop_category_create', new Route('/admin/category/create', array(
    '_controller' => 'VivaShopBundle:Category:create'
)));

$collection->add('viva_shop_category_edit', new Route('/admin/category/edit/{id}', array(
    '_controller' => 'VivaShopBundle:Category:edit'
)));

$collection->add('viva_shop_category_delete', new Route('/admin/category/delete/{id}', array(
    '_controller' => 'VivaShopBundle:Category:delete'
)));

return $collection;
