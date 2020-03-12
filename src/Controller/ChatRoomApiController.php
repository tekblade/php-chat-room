<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;




class ChatRoomApiController extends AbstractController {
    
    
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request){
        
        $user=new User();
        $user->setUser('');
        $form=$this->createFormBuilder($user)
                ->add('user',TextType::class,['label'=>'UserName:'])
                ->add('save',SubmitType::class,['label'=>'Submit'])
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->get('session')->set('user', $data->getUser());
            //$session->set('user', $data->getUser());
            return $this->redirectToRoute('chat_room');
        }
        return $this->render('login.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/chat", name="chat_room")    * 
     */
    public function chatRoom(Request $request){
        
        return $this->render('chat_room.html.twig',[
            'user'=>$this->get('session')->get('user')
        ]);
    }
    /**
     * @Route("/save_ajax")
     */
    public function saveToFile(Request $request){
        $time = date('H:i:s \O\n d/m/Y');
        $message='<p><b>'.$time.'</b> <b>'.$this->get('session')->get('user').':</b><br /> '.$_GET['message'].'</p>';
        $filesystem = new Filesystem();  
        $filesystem->appendToFile('logs/logs.txt', $message);  
        return new Response();
    }
    /**
     * @Route("/load_ajax")
     */
    public function loadFromFile(){
        $finder = new Finder();
        $webPath = $this->getParameter('kernel.project_dir'). '/public/logs';
        $finder->files()->in($webPath);
        foreach ($finder as $file) {
            $contents = $file->getContents();
        return new Response($contents);
        }
            
            
        
        
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//<p><b>time</b> <b>user:</b>text</p>