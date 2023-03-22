<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginwithGoodCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/login');
        /**
         * test login with good credentials
         */
        $form = $crawler->selectButton('Get Started')->form([
            '_username' => 'User',
            '_password' => 'password']);
        $client->submit($form);
        $client->followRedirect();
        $this->assertRouteSame('homepage');
    }

    public function testLoginWithBadCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/login');
        /**
         * test login with bad credentials
         */
        $form = $crawler->selectButton('Get Started')->form([
            '_username' => 'john@doe.fr',
            '_password' => 'Fakepassword']);
        $client->submit($form);
        //$this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

}