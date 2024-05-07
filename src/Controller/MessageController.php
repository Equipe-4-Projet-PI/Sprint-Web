<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\DiscussionRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\NotificationSender;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
use App\Service\MessageSender;


#[Route('/message')]
class MessageController extends AbstractController
{
    
    #[Route('', name: 'app_message_index', methods: ['GET'])]
    public function index(MessageRepository $messageRepository): Response
    {
        return $this->render('message/afficherMsg.html.twig', [
            'messages' => $messageRepository->findAll(),
        ]);
    }

    /*#[Route('/new', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();
            return $this->redirectToRoute('app_discussion_show_messages', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }*/


    private $messageSender;
    private $notificationSender;

    public function __construct(MessageSender $messageSender , NotificationSender $notificationSender)
    {
        $this->messageSender = $messageSender;
        $this->notificationSender = $notificationSender;
    }
    
    #[Route('/new', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , DiscussionRepository $discussionRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        $discussion=$discussionRepository->findDiscussionById($message->getIddis());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();
            
            // Envoyer la notification
            $this->notificationSender->sendNotification($discussion->getReceiver(), $message->getContent());

            return $this->redirectToRoute('app_discussion_show_messages', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{idmsg}', name: 'app_message_show', methods: ['GET'])]
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('{idmsg}/edit', name: 'app_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager ): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{idmsg}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getIdmsg(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
        }

        $iddis = $message->getIddis();

        return $this->redirectToRoute('app_discussion_show_messages', ['iddis' => $iddis], Response::HTTP_SEE_OTHER);
    }
}
