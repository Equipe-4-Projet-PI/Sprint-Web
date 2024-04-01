<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\SignupType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserController extends AbstractController
{
    #[Route('/user/login', name: 'login')]
    public function login(Request $req ): Response
    {
        $error = $req->query->get('error');

        return $this->render('user/Login.html.twig', [
            'controller_name' => 'UserController',
            'error' => $error
        ]);
    }

    #[Route('/user/authenticate', name: 'authenticate')]
    public function authenticate(Request $request, UserRepository $repo): Response
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');
    
        
        if ($username === null || $username === '') {
          
            return $this->redirectToRoute('login', ['error' => 'Username is required']);
        }
    
       
        $user = $repo->findOneByUsername($username);
    
        if (!$user) {
           
            return $this->redirectToRoute('login', ['error' => 'Couldnt find that username']);
        }
        if ($user->getRole() === "Admin") {
            
            return $this->redirectToRoute('Admin');
        }else{
            if ($user->getPassword() === $password) {
            
                return $this->redirectToRoute('home');
            } else {
                
                return $this->redirectToRoute('login', ['error' => 'Invalid Username or Password']);
            }
        }
    
      
      
    }


    #[Route('/user/signup', name: 'signup', methods: ['GET', 'POST'])]
    public function Opensignup(): Response
    {
        return $this->render('user/SignUp_Ui/SignUp.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/signup/step2', name: 'signup2' , methods: ['GET', 'POST'])]
    public function Opensignup2(Request $req): Response
    {
        // Get username and email from query parameters
    $username = $req->query->get('username');
    $email = $req->query->get('email');
    $password = $req->query->get('password');
    $Role = $req->query->get('role');

    
    
        return $this->render('user/SignUp_Ui/SignUpStep2.html.twig', [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'Role' => $Role,
            'controller_name' => 'UserController',
           
        ]

    );
    }
    #[Route('/user/signup/step3', name: 'signup3', methods: ['GET', 'POST'])]
    public function Opensignup3(Request $req ,ManagerRegistry $manager): Response
    {
        $username = $req->query->get('username');
    $email = $req->query->get('email');
    $password = $req->query->get('password');
    $Role = $req->query->get('Role');
    $FirstName = $req->query->get('FirstName');
    $LastName = $req->query->get('LastName');
    $Adress = $req->query->get('adress');
    $Phone = $req->query->get('Phone');
  
    $Gender = $req->query->get('Gender');
    $ph = $req->query->get('Phone1');
  

    $user = new User();
    $form = $this->createForm(SignupType::class,$user);
    $form -> handleRequest($req);
    if ($form->isSubmitted())  
    {  

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRole($Role);
        $user->setFirstname($FirstName);
        $user->setLastname($LastName);
        $user->setAdress($Adress);
        $user->setDob($ph);
        $user->setPhone($Phone);
        $user->setGender($Gender);
        $entityManager= $manager->getManager(); 
        $entityManager->persist($user);  
        $entityManager->flush();  
         return $this->redirectToRoute('welcome');
    }
    
        return $this->render('user/SignUp_Ui/SignUpStep3.html.twig', [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'Role' => $Role,
            'FirstName' => $FirstName,
            'LastName' => $LastName,
            'Adress' => $Adress,
            'Phone' => $Phone,
            'Gender' => $Gender,
           

            'controller_name' => 'UserController',
            'form'=>$form->createView()

        ]);
    }

    #[Route('/user/welcome', name: 'welcome')]
    public function Welcome(): Response
    {
        return $this->render('user/SignUp_Ui/SignupStep4.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/Home', name: 'home')]
    public function Home(): Response
    {
        return $this->render('Home.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/Admin', name: 'Admin')]
    public function Admin( UserRepository $repo): Response
    {
        $users = $repo->findAll() ; 
        $usernumbers = $repo ->numberOfUsers();
        return $this->render('admin/UsersAdmin.html.twig', [
            'users' => $users ,
            'usernumber'=> $usernumbers,
        ]);
    }
    #[Route('/user/delete/{id}', name: 'user_delete')]
    public function deleteAuthor(ManagerRegistry $manager,UserRepository $repo,$id){
        $user = $repo->find($id);
        $manager->getManager()->remove($user);
        $manager->getManager()->flush();
        return $this->redirectToRoute('Admin');
    }


   
    
   

}
