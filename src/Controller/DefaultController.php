<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function indexAction(
        TaskRepository $taskRepository,
       
    ): Response {
        return $this->render('default/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
            'tasks_done_number' => $taskRepository->count(["isDone" => true]),
            'tasks_to_do_number' => $taskRepository->count(["isDone" => false ])
        ]);    
    }
}