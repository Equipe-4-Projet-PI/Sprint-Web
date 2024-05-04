<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Discussion;
use App\Entity\Message;
use App\Entity\User;
use App\Form\DiscussionType;
use App\Form\MessageType;
use App\Repository\UserRepository;
use App\Repository\DiscussionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessageRepository;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\This;
use App\Form\SignalType;

#[Route('/discussion')]
class DiscussionController extends AbstractController
{

    /*#[Route('_', name: 'app_discussion_index', methods: ['GET'])]
    public function index(DiscussionRepository $discussionRepository): Response
    {
        return $this->render('discussion/afficherDis.html.twig', [
            'discussions' => $discussionRepository->findAll(),
        ]);
    }*/

    #[Route('_', name: 'app_discussion_index', methods: ['GET'])]
public function index(DiscussionRepository $discussionRepository, UserRepository $userRepository): Response
{
    // Récupérer les discussions avec les informations sur le destinataire
    $discussions = $discussionRepository->findAllWithReceiver();

    // Récupérer les noms des destinataires
    foreach ($discussions as $discussion) {
        $receiver = $discussion->getReceiver();
        $receiverName = $receiver ? $receiver->getUsername() : 'Destinataire inconnu';
        $discussion->setReceiver($receiver);
    }

    return $this->render('discussion/afficherDis.html.twig', [
        'discussions' => $discussions,
    ]);
}


    #[Route('_new', name: 'app_discussion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $discussion = new Discussion();
        $idCurrentUser = 1 ;
        $discussion->setIdsender($idCurrentUser);
        $discussion->setSig("");
        $form = $this->createForm(DiscussionType::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($discussion);
            $entityManager->flush();
            $iddis = $discussion->getIddis();
            return $this->redirectToRoute('/{iddis}/messages', [$iddis], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('discussion/new.html.twig', [
            'discussion' => $discussion,
            'form' => $form,
        ]);
    }

    #[Route('_{iddis}', name: 'app_discussion_show', methods: ['GET'])]
    public function show(Discussion $discussion): Response
    {
        return $this->render('discussion/show.html.twig', [
            'discussion' => $discussion,
        ]);
    }

    #[Route('/discussionlist', name: 'app_list_disc')]
    public function getAll(DiscussionRepository $repo): Response
    {
        $discussions = $repo->findAll();
        return $this->render('discussion/Admin.html.twig', [
            'discussions' => $discussions
        ]);
    }

    #[Route('-{iddis}_edit', name: 'app_discussion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Discussion $discussion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DiscussionType::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_discussion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('discussion/edit.html.twig', [
            'discussion' => $discussion,
            'form' => $form,
        ]);
    }
    
    #[Route('_{iddis}', name: 'app_discussion_delete', methods: ['POST'])]
    public function delete(Request $request, Discussion $discussion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $discussion->getIddis(), $request->request->get('_token'))) {
            $entityManager->remove($discussion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_discussion_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('-messages_{iddis}', name: 'app_discussion_show_messages', methods: ['GET', 'POST'])]
    public function showMessages($iddis, Request $request, MessageRepository $messageRepository, UserRepository $userRepository , EntityManagerInterface $entityManager): Response
    {
        $discussion = $this->getDoctrine()->getRepository(Discussion::class)->find($iddis);
        
       // $idCurrentUser = User.get_current_user();
        $user = null ;
        $idCurrentUser = 1 ; //$user.getIdUser();
        //$sender = $userRepository->findUserById($idCurrentUser);

        $message = new Message();
        $message->setIdsender($idCurrentUser);
        $message->setIddis($iddis);
    
        $messages = $messageRepository->findBy(['iddis' => $iddis]);

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_discussion_show_messages', ['iddis'=>$iddis], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('message/messages.html.twig', [
            'discussion' => $discussion,
            'messages' => $messages,
            //'sender' => $sender ,
            'form' => $form->createView(),
        ]);
    }


    #[Route('-{iddis}-signaler', name: 'app_discussion_signal', methods: ['GET', 'POST'])]
    public function signaler(Request $request, Discussion $discussion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SignalType::class);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $discussion->setSig($data['motif']);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_discussion_show_messages', ['iddis' => $discussion->getIddis()]);
        }
    
        return $this->render('discussion/signaler.html.twig', [
            'form' => $form->createView(),
            'discussion' => $discussion,
        ]);
    }



    #[Route('/searchdiscussion', name: 'search_discussion', methods: ['GET'])]    
    public function searchDiscussionByUsername(Request $request, DiscussionRepository $discussionRepository): Response
    {
        $username = $request->query->get('username');

        // Recherche des discussions par nom d'utilisateur du destinataire
        $discussions = $discussionRepository->findByReceiverUsername($username);

        // Redirection vers la page des messages de la première discussion trouvée
        if (!empty($discussions)) {

            $firstDiscussionId = $discussions[0]->getIddis();
            return $this->redirectToRoute('app_discussion_show_messages', ['iddis' => $firstDiscussionId]);
        } else {
        // Redirection vers une page par défaut si aucune discussion n'a été trouvée
            return $this->redirectToRoute('app_discussion_index');
    }
}


    
}