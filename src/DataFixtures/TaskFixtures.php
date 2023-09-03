<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Task;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public const TASK_REFERENCE = 'task';

    public function load(ObjectManager $manager): void
    {
        $tasks = [
            [
                'title' => 'sport',
                'content' => 'go to the gym',
            ],
            [
                'title' => 'school',
                'content' => 'monday at 9am',
            ],
            [
                'title' => 'sport',
                'content' => 'legs day',
            ],
            [
                'title' => 'sport',
                'content' => 'bust day',
            ],
            [
                'title' => 'shopping',
                'content' => 'hygienic product',
            ],
            [
                'title' => 'shopping',
                'content' => 'food race',
            ],
            [
                'title' => 'sport',
                'content' => 'legs day',
            ],
            [
                'title' => 'sport',
                'content' => 'baseball',
            ],
            [
                'title' => 'entertainment',
                'content' => 'film show',
            ],
        ];

        //Create a anonymous task
        $task = (new Task())
            ->setTitle("anonymous task")
            ->setContent("anonymous task")
            ->setDeadline(new \DateTimeImmutable())
            ->setUser(null);

        $manager->persist($task);

        foreach ($tasks as $index => $userData) {

            $randNbr = rand(0, 2);
            $user = $this->getReference(UserFixtures::USER_REFERENCE . $randNbr);

            $task = (new Task())
                ->setTitle($userData['title'])
                ->setContent($userData['content'])
                ->setUser($user);

            $manager->persist($task);

            $this->addReference(self::TASK_REFERENCE . $index, $task);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}