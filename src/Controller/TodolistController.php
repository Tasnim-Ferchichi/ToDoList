<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/todolist')]
class TodolistController extends AbstractController
{   #[Route('/')]
public function indexAction(SessionInterface $session):Response{
    //new ToDoList exists but still empty
    if(($session->has('todo')) &&(count($session->get('todo')) == 0)){
        $this->addFlash('info','your ToDo list is empty ! add some Tasks !');
    }

    if(!$session->has("todo")){
        $todo = array(
            'achat'=>'acheter clé usb',
            'cours'=>'Finaliser mon cours',
            'correction'=>'corriger mes examens'
        );
        $session->set("todo",$todo);
        $this->addFlash('info','you have created a new ToDo list ! put some Tasks !');
    }
    return $this->render('todolist/index.html.twig',[
        'session'=>$session,

    ]);
}
    #[Route('/addToDo/{task}/{description}',name:"addToDo")]

    public function addToDo($task,$description,SessionInterface $session):Response{

        if(!$session->has("todo")){
            $this->addFlash('error','your ToDo list is not yet initialised !');
//            return $this->forward('App\\Controller\\ToDoController::indexAction');

        }
        else{

            $array=$session->get('todo');

            $array[$task]=$description;

            $session->set('todo',$array);
//            array_push($session['todo']['task'],'description');
            $this->addFlash('success','new task is added !');
        }
        return $this->render('todolist/index.html.twig',[
            'session'=>$session,

        ]);

    }
    #[Route('/deleteToDo/{task}')]

    public function deleteToDo($task,SessionInterface $session):Response{
        //if the ToDoList exists
        if($session->has('todo')){
            //if the task doesn't exist
            if (!array_key_exists($task, $session->get('todo'))) {
                $this->addFlash('error', 'the task ' . $task . ' is not found');

            }
            else {
                $array = $session->get('todo');
                unset($array[$task]);
                $session->set('todo', $array);
                $this->addFlash('success', 'the task " ' . $task . ' " has been successfully deleted !');
            }
            return $this->render('todolist/index.html.twig',[
                'session'=>$session,
            ]);

        }
        else{
            $this->addFlash('error', 'your list is empty');
            return $this->render('todolist/index.html.twig');
        }


    }
    #[Route('/resetToDo')]
    public function resetToDo(SessionInterface $session):Response{


        $session->clear();
        $array = array(
            'achat'=>'acheter clé usb',
            'cours'=>'Finaliser mon cours',
            'correction'=>'corriger mes examens'
        );

        $this->addFlash('success', 'you have successfully reset your ToDo List');
        $session->set('todo',$array);

        return $this->render('todolist/index.html.twig',[
            'session'=>$session,
        ]);

    }

}