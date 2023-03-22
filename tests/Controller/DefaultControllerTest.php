<?php

namespace App\tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndexRedirect(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertResponseRedirects();
        
        $client->followRedirect();
        $this->assertRouteSame('app_login');

    }

    public function testIndexWhenLoggedin(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneByUsername('User');

        $client
            ->loginUser($user)
            ->request(Request::METHOD_GET, '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Welcome to Todo List');

    }

} 