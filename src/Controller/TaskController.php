<?php

namespace App\Controller;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }
    #[Route('/tasks', name: 'task_list')]
    public function list(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findAll();
        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/tasks/add', name: 'task_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('task_list');
        }
        return $this->render('task/add.html.twig', [
            'form' =>$form->createView(),
        ]);
    }
    #[Route('/tasks/{id}/done', name: 'task_done')]
    public function done(Task $task, EntityManagerInterface $em): Response
    {
        $task->setIsDone(true);
        $em->flush();
        return $this->redirectToRoute('task_list');
    }
#[Route('/tasks/{id}/delete', name: 'task_delete')]
public function delete(Task $task, EntityManagerInterface $em): Response
{
    $em->remove($task);
    $em->flush();
    return $this->redirectToRoute('task_list');
}
}
