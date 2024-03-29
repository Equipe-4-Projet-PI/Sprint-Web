<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/login', name: 'login')]
    public function Openlogin(): Response
    {
        return $this->render('user/Login.html.twig', [
            'controller_name' => 'UserController',
        ]);
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
    public function Opensignup3(Request $req): Response
    {
        $username = $req->query->get('username');
    $email = $req->query->get('email');
    $password = $req->query->get('password');
    $Role = $req->query->get('role');
    $FirstName = $req->query->get('FirstName');
    $LastName = $req->query->get('LastName');
    $Adress = $req->query->get('Adress');
    $Phone = $req->query->get('Phone');
    $Genre = $req->query->get('Grenre');
        return $this->render('user/SignUp_Ui/SignUpStep3.html.twig', [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'Role' => $Role,
            'FirstName' => $FirstName,
            'LastName' => $LastName,
            'Adress' => $Adress,
            'Phone' => $Phone,
            'Genre' => $Genre,

            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/welcome', name: 'welcome')]
    public function Welcome(): Response
    {
        return $this->render('user/SignUp_Ui/SignupStep4.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
   
    
   

}
