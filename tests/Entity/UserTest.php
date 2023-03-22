<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testUserEntity()
    {
        $user = (new User())
            ->setUsername('userTest')
            ->setEmail('userTest@gmail.fr')
            ->setPassword('password')
            ->setRoles(['ROLE_USER']);

        $this->assertEquals('userTest', $user->getUsername());
        $this->assertEquals('userTest@gmail.fr', $user->getEmail());
        $this->assertEquals('password', $user->getPassword());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }
}