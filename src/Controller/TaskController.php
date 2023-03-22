<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'tasks_list')]
    public function tasksList(
        TaskRepository $taskRepository,

    ): Response {
        return $this->render('task/list.html.twig', [
            'tasks' => $taskRepository->findAll(),
            'tasks_done_number' => $taskRepository->count(["isDone" => true]),
            'tasks_to_do_number' => $taskRepository->count(["isDone" => false])
        ]);
    }

    #[Route('/tasks-to-do', name: 'tasks_to_do')]
    public function tasksToDo(
        TaskRepository $taskRepository,

    ): Response {
        return $this->render('task/todo.html.twig', [
            'tasks' => $taskRepository->findAll(),
            'tasks_to_do' => $taskRepository->findBy(["isDone" => false]),
            'tasks_done_number' => $taskRepository->count(["isDone" => true]),
            'tasks_to_do_number' => $taskRepository->count(["isDone" => false])
        ]);
    }

    #[Route('/tasks-completed', name: 'tasks_completed')]
    public function tasksCompleted(
        TaskRepository $taskRepository,

    ): Response {
        return $this->render('task/completed.html.twig', [
            'tasks' => $taskRepository->findAll(),
            'tasks_completed' => $taskRepository->findBy(["isDone" => true]),
            'tasks_done_number' => $taskRepository->count(["isDone" => true]),
            'tasks_to_do_number' => $taskRepository->count(["isDone" => false])
        ]);
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function createAction(
        Request $request,
        TaskRepository $repository
    ): Response {
        $task = new Task();
        $form = $this
            ->createForm(TaskType::class, $task)
            ->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $this->getUser()->addTask($task);
            $repository->save($task, true);


            $this->addFlash('success', 'The task has been successfully added.');

            return $this->redirectToRoute('tasks_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function editAction(
        Task $task,
        Request $request,
        TaskRepository $repository

    ): Response {
        $form = $this
            ->createForm(TaskType::class, $task)
            ->handleRequest($request);

        $this->denyAccessUnlessGranted('TASK_MODIFY', $task);

        if ($form->isSubmitted() and $form->isValid()) {

            $repository->save($task, true);

            $this->addFlash('success', 'The task has been modified');

            return $this->redirectToRoute('tasks_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }


    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(
        Task $task,
        EntityManagerInterface $em
    ): Response {
        $task->toggle(!$task->isDone());
        $em->persist($task);
        $em->flush();

        if ($task->isDone() === true) {
            $this->addFlash('success', sprintf('The task "%s" has been marked as done', $task->getTitle()));
            return $this->redirectToRoute('tasks_list');
        }

        $this->addFlash('success', sprintf('The task "%s" has been marked as to do', $task->getTitle()));

        return $this->redirectToRoute('tasks_list');
    }


    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(
        Task $task,
        TaskRepository $repository
    ): Response {

        $this->denyAccessUnlessGranted('TASK_MODIFY', $task);

        $repository->remove($task, true);

        $this->addFlash('success', 'The task has been deleted.');

        return $this->redirectToRoute('tasks_list');
    }
}