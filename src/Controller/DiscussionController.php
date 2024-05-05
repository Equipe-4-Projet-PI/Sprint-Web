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

    private $currentUserId;

    public function __construct(UserRepository $userRepository)
    {
        // Récupérer l'ID de l'utilisateur actuel (à partir de la session par exemple)
        $this->currentUserId = 6; // Exemple : récupérez l'ID de l'utilisateur actuel selon votre logique
    }


    /*#[Route('_', name: 'app_discussion_index', methods: ['GET'])]
    public function index(DiscussionRepository $discussionRepository): Response
    {
        return $this->render('discussion/afficherDis.html.twig', [
            'discussions' => $discussionRepository->findAll(),
        ]);
    }*/

    /*#[Route('_', name: 'app_discussion_index', methods: ['GET'])]
public function index(DiscussionRepository $discussionRepository, UserRepository $userRepository): Response
{

    $currentIdUser = 1;
    // Récupérer les discussions avec les informations sur le destinataire
    $discussions = $discussionRepository->findAllWithReceiver();

    $discussions = $discussionRepository->findBy(['idsender' => $currentIdUser]);
    // Récupérer les noms des destinataires
    foreach ($discussions as $discussion) {
        $receiver = $discussion->getReceiver();
        $receiverName = $receiver ? $receiver->getUsername() : 'Destinataire inconnu';
        $discussion->setReceiver($receiver);
    }

    return $this->render('discussion/afficherDis.html.twig', [
        'discussions' => $discussions,
    ]);
}*/

#[Route('_', name: 'app_discussion_index', methods: ['GET'])]
public function index(DiscussionRepository $discussionRepository, UserRepository $userRepository): Response
{

    // Récupérer les discussions où l'utilisateur courant est soit l'expéditeur (sender) ou le destinataire (receiver)
    $discussions = $discussionRepository->findDiscussionsByUser($this->currentUserId);

    return $this->render('discussion/afficherDis.html.twig', [
        'discussions' => $discussions,
    ]);
}



    /*#[Route('_new', name: 'app_discussion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $discussion = new Discussion();
        $idCurrentUser = 15 ;
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
    }*/

    /*#[Route('_new', name: 'app_discussion_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $discussion = new Discussion();
    $form = $this->createForm(DiscussionType::class, $discussion);
    $form->handleRequest($request);

    /*if ($form->isSubmitted() && $form->isValid()) {

        // Récupérer l'ID du destinataire depuis le formulaire
        $receiverId = $discussion->getReceiver()->getIdUser();

        // Vérifier si une discussion existe déjà entre l'utilisateur courant et le destinataire
        $existingDiscussion = $entityManager->getRepository(Discussion::class)->findExistingDiscussion($this->currentUserId, $receiverId);

        if ($existingDiscussion) {
            // Une discussion existe déjà, rediriger vers cette discussion
            $iddis = $existingDiscussion->getIddis();
            return $this->redirectToRoute('app_discussion_show_messages', ['iddis' => $iddis], Response::HTTP_SEE_OTHER);
        }

        // Aucune discussion existante, procéder à la création de la nouvelle discussion
        $discussion->setIdsender($this->currentUserId);

        $discussion->setSig("");

        $entityManager->persist($discussion);
        $entityManager->flush();

        $iddis = $discussion->getIddis();
        return $this->redirectToRoute('app_discussion_show_messages', ['iddis' => $iddis], Response::HTTP_SEE_OTHER);
    }

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifier si le destinataire a été sélectionné
        if ($discussion->getReceiver() !== null) {
            // Récupérer l'ID du destinataire depuis le formulaire
            $receiverId = $discussion->getReceiver()->getIdUser();
    
            // Vérifier si une discussion existe déjà entre l'utilisateur courant et le destinataire
            $existingDiscussion = $entityManager->getRepository(Discussion::class)->findExistingDiscussion($this->currentUserId, $receiverId);
    
            if ($existingDiscussion) {
                // Une discussion existe déjà, rediriger vers cette discussion
                $iddis = $existingDiscussion->getIddis();
                return $this->redirectToRoute('app_discussion_show_messages', ['iddis' => $iddis], Response::HTTP_SEE_OTHER);
            }
    
            // Aucune discussion existante, procéder à la création de la nouvelle discussion
            $discussion->setIdsender($this->currentUserId);
            $discussion->setSig("");
    
            $entityManager->persist($discussion);
            $entityManager->flush();
    
            $iddis = $discussion->getIddis();
            return $this->redirectToRoute('app_discussion_show_messages', ['iddis' => $iddis], Response::HTTP_SEE_OTHER);
        } else {
            // Si aucun destinataire n'a été sélectionné, afficher un message d'erreur ou rediriger vers une page appropriée
            // Ici, je redirige vers la page de création de discussion avec un message d'erreur
            $this->addFlash('error', 'Veuillez sélectionner un destinataire.');
            return $this->redirectToRoute('app_discussion_new');
        }
    }
    

    return $this->renderForm('discussion/new.html.twig', [
        'discussion' => $discussion,
        'form' => $form,
    ]);
}*/

#[Route('_new', name: 'app_discussion_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $discussion = new Discussion();
    $form = $this->createForm(DiscussionType::class, $discussion);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

dump($this->currentUserId);
        // Le formulaire est soumis et valide
        // Procéder à la vérification de l'existence de la discussion
        $receiver = $discussion->getReceiver();
        if ($receiver !== null) {
            $receiverId = $receiver->getIdUser();
            $existingDiscussion = $entityManager->getRepository(Discussion::class)->findExistingDiscussion($this->currentUserId, $receiverId);
//dump($receiverId);
            if ($existingDiscussion) {
                // Une discussion existe déjà, rediriger vers cette discussion
//dump($existingDiscussion);
                return $this->redirectToRoute('app_discussion_show_messages', ['iddis' => $existingDiscussion->getIddis()], Response::HTTP_SEE_OTHER);
            }

            // Aucune discussion existante, procéder à la création de la nouvelle discussion
            $discussion->setIdsender($this->currentUserId);
            $discussion->setSig("");
    
            $entityManager->persist($discussion);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_discussion_show_messages', ['iddis' => $discussion->getIddis()], Response::HTTP_SEE_OTHER);
        } else {
            // Si aucun destinataire n'a été sélectionné, afficher un message d'erreur ou rediriger vers une page appropriée
            // Ici, je redirige vers la page de création de discussion avec un message d'erreur
            $this->addFlash('error', 'Veuillez sélectionner un destinataire.');
            return $this->redirectToRoute('app_discussion_new');
        }
    }

    // Le formulaire n'est pas soumis ou n'est pas valide
    // Afficher le formulaire de création de discussion
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
        //$sender = $userRepository->findUserById($idCurrentUser);

        $message = new Message();
        $message->setIdsender($this->currentUserId);
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
            'currentUserId' => $this->currentUserId,

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