<?php 

namespace App\test\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function testTaskEntity(): void
    {

        $user = (new User())
            ->setUsername('userTest')
            ->setEmail('userTest@gmail.fr')
            ->setPassword('password')
            ->setRoles(['ROLE_USER']);
           
        $task = (new Task())
            ->setTitle('titleTest')
            ->setContent('contentTest')
            ->setCreatedAt(new \DateTimeImmutable)
            ->setUser($user);
        
        $this->assertEquals('titleTest', $task->getTitle());
        $this->assertEquals('contentTest', $task->getContent());
        $this->assertEquals(false, $task->isDone());
        $this->assertEquals(true, $task->getUser() instanceof User);
        $this->assertEquals(true, $task->getCreatedAt() instanceof DateTimeImmutable);

    }
}