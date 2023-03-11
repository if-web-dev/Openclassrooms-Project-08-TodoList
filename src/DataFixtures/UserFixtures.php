<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'username' => 'Admin',
                'email' => 'Admin@gmail.com',
                'password' => 'password',
                'role' => ['ROLE_ADMIN']
            ],
            [
                'username' => 'User',
                'email' => 'User@gmail.com',
                'password' => 'password',
                'role' => ['ROLE_USER']
            ],
        ];

        foreach ($users as $index => $userData) {
           
            $user = (new User())
                ->setUsername($userData['username'])
                ->setEmail($userData['email'])
                ->setRoles($userData['role']);

                $password = $this->hasher->hashPassword($user, $userData['password']);
                $user->setPassword($password);
            $manager->persist($user);

            $this->addReference(self::USER_REFERENCE . $index, $user);
        }

        $manager->flush();
    }
}