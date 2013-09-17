<?php

namespace Viva\ShopBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/login');
    }

    public function testLogout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/logout');
    }

    public function testForgot()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/forgot');
    }

    public function testProfile()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/profile');
    }

}
