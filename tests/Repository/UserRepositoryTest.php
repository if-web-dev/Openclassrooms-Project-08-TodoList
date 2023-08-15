<?php 

namespace App\tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{

    public function testUserCount() {
        
        self::bootKernel();
        $users = static::getContainer()->get(UserRepository::class)->count([]);
        $this->assertEquals(3, $users);
    }

    public function testUserRemove(): void
    {
        self::bootKernel();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['username' => 'User3']);
        $this->assertInstanceOf(User::class, $user);

        $userRepository->remove($user, true);

        $this->assertNull($userRepository->findOneByUsername("User3"));
    }
}

