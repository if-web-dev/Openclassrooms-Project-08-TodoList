<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testListNoAuth(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/tasks');
        //expected a redirection
        $this->assertResponseRedirects();
        $client->followRedirect();
        //expected a redirection to /login
        $this->assertRouteSame('app_login');
    }

    public function testListAsUser(): Void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('User');
        //log a user
        $client
            ->loginUser($user)
            ->request(Request::METHOD_GET, '/tasks');
        //Expected a successful response
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Tasks List');
    }

  
    public function testTaskCreate(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $user = $userRepository->findOneByUsername('User');
        //log a user
        $client
            ->loginUser($user)
            ->request('GET', '/tasks/create');
        //Create a task
        $client->submitForm(
                'Create', [
                    'task[title]' => 'Task test',
                    'task[content]' => 'Task test'
                ]
            );
        $client->followRedirects();
        //Expected a redirection to tasks list
        $this->assertResponseRedirects('/tasks', 302);
        $this->assertNotNull($taskRepository->findOneBy(['title' => 'Task test']));
    }

  
    public function testTaskEditWithPermission(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        //Get a user
        $taskOwner = $userRepository->findOneByUsername('User');
        //log a user
        $client->loginUser($taskOwner);
        //Get his own task
        $taskOwned = $taskOwner->getTasks()->first();
        $client->request('GET', '/tasks/'.$taskOwned->getId().'/edit');
        //Edit his own task
        $client->submitForm(
            'Edit', [
            'task[title]' => 'Task edited',
            'task[content]' => 'Task edited'
            ]
        );
        //Expected a redirection to tasks list
        $client->followRedirects();
        $this->assertResponseRedirects('/tasks', 302);
        $this->assertNotNull($taskRepository->findOneByTitle('Task edited'));
        
    }

   
    public function testTaskEditWithoutPermission(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        //find a user
        $user = $userRepository->findOneByUsername('User');
        //find an another user
        $admin = $userRepository->findOneByUsername('Admin');
        //log the first
        $client->loginUser($user);
        $client->followRedirects();
        //get a second user task
        $adminTask = $admin->getTasks()->first();
        //edit this task
        $client->request('GET', '/tasks/'.$adminTask->getId().'/edit');
        //Expect the user id is diferent than the second task user id
        $this->assertNotEquals($user->getId(), $adminTask->getUser()->getId());
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

  
    public function testToggleTask(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        //find a user
        $user = $userRepository->findOneByUsername('User');
        //log a user
        $client->loginUser($user);
        //get a task
        $testTask = $user->getTasks()->first();
        //Expect a task not marqued as done yet
        $this->assertEquals(false, $testTask->isDone());
        //Change task status
        $client->request('GET', '/tasks/'.$testTask->getId().'/toggle');
        $testTask = $taskRepository->findOneById($testTask->getId());
        //Expect the task marqued as done
        $this->assertEquals(true, $testTask->isDone());
    }

   
   public function testDeleteTaskAllowed(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        //Get a user
        $taskOwner = $userRepository->findOneByUsername('User3');
        //log a user
        $client->loginUser($taskOwner);
        //Get his own task
        $taskOwned = $taskOwner->getTasks()[0];
        $taskOwnedId = $taskOwned->getId();
        //Delete his own task
        $client->request('GET', '/tasks/'.$taskOwnedId.'/delete');
        //Expect a redirection to tasks list
        $client->followRedirects();
        $this->assertResponseRedirects('/tasks', 302);
        //Expect task is deleted
        $this->assertEquals(null, $taskRepository->findOneById($taskOwnedId));
        
    }

    public function testDeleteTaskAnonymous(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taskRepository = static::getContainer()->get(TaskRepository::class);
         //Get a user Admin
         $admin = $userRepository->findOneByUsername('Admin');
         //log a user
         $client->loginUser($admin);
         //Get an anonymous task
         $anonymousTask = $taskRepository->findOneByUser(null);
         $anonymousTaskId = $anonymousTask->getId();
         //Delete the anonymous task
         $client->request('GET', '/tasks/'.$anonymousTaskId.'/delete');
         //Expect a redirection to tasks list
         $client->followRedirects();
         $this->assertResponseRedirects('/tasks', 302);
         //Expect task is deleted
         $this->assertEquals(null, $taskRepository->findOneById($anonymousTaskId));
    }

    
    public function testDeleteTaskNotAllowed(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
         //Get different users
         $user1 = $userRepository->findOneByUsername('User3');
         $user2 = $userRepository->findOneByUsername('User');
         //log a user
         $client->loginUser($user1);
         //Get an anonymous task
         $task = $user2->getTasks()->first();
         $taskId = $task->getId();
         //Delete the anonymous task
         $client->request('GET', '/tasks/'.$taskId.'/delete');
         //Expect a redirection
         $this->assertResponseStatusCodeSame(403);
         //Expect a difference between user id and task user id
         $this->assertNotEquals($user1->getId(), $task->getUser()->getId());
    }

    public function testDisplayTaskToDo(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('User');
        //log a user
        $client
            ->loginUser($user)
            ->request(Request::METHOD_GET, '/tasks-to-do');
        //Expect HTTP reponse 200
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Tasks to do');
    }

    public function testDisplayTaskCompleted(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('User');
        //log a user
        $client
            ->loginUser($user)
            ->request(Request::METHOD_GET, '/tasks-completed');
        //Expect HTTP reponse 200
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Tasks completed');
    }

}