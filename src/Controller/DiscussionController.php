<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Form\DiscussionType;
use App\Repository\DiscussionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/discussion')]
class DiscussionController extends AbstractController
{
    #[Route('/', name: 'app_discussion_index', methods: ['GET'])]
    public function index(DiscussionRepository $discussionRepository): Response
    {
        return $this->render('discussion/afficherDis.html.twig', [
            'discussions' => $discussionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_discussion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $discussion = new Discussion();
        $form = $this->createForm(DiscussionType::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($discussion);
            $entityManager->flush();

            return $this->redirectToRoute('app_discussion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('discussion/new.html.twig', [
            'discussion' => $discussion,
            'form' => $form,
        ]);
    }

    #[Route('/{iddis}', name: 'app_discussion_show', methods: ['GET'])]
    public function show(Discussion $discussion): Response
    {
        return $this->render('discussion/show.html.twig', [
            'discussion' => $discussion,
        ]);
    }

    #[Route('/discussionlist',name:'app_list_disc')]
    public function getAll(DiscussionRepository $repo){
        $discussions = $repo->findAll();
        return $this->render('discussion/Admin.html.twig',[
            'discussions'=>$discussions
        ]);
    }

    #[Route('/{iddis}/edit', name: 'app_discussion_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{iddis}', name: 'app_discussion_delete', methods: ['POST'])]
    public function delete(Request $request, Discussion $discussion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$discussion->getIddis(), $request->request->get('_token'))) {
            $entityManager->remove($discussion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_discussion_index', [], Response::HTTP_SEE_OTHER);
    }

    // Action pour afficher les messages d'une discussion spécifique
    #[Route('/discussion/{iddis}/messages', name: 'app_discussion_show_messages',  methods: ['GET', 'POST'])]
    public function showMessages($iddis , Request   $request, EntityManagerInterface $entityManager): Response {

        $message = new Message();
        $message->setIdsender(1);
        $message->setIddis($iddis);

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

    $discussion = $this->getDoctrine()->getRepository(Discussion::class)->find($iddis);
    $messages = $discussion->getMessages(); 

    return $this->render('message/messages.html.twig', [
        'discussion' => $discussion,
        'messages' => $messages,
        'form' => $form->createView(),
    ]);
}
/*#[Route('/discussion/{iddis}/messages', name: 'app_discussion_show_messages',  methods: ['GET', 'POST'])]
public function showMessages($iddis , Request $request, EntityManagerInterface $entityManager): Response 
{
    // Récupérer la discussion à partir de son ID
    $discussion = $this->getDoctrine()->getRepository(Discussion::class)->find($iddis);

    // Récupérer les messages associés à cette discussion
    $messages = $discussion->getMessages(); 

    // Créer un nouveau message pour le formulaire
    $newMessage = new Message();
    $newMessage->setIddis($discussion); // Associer le message à la discussion
    
    $form = $this->createForm(MessageType::class, $newMessage);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Si le formulaire est soumis et valide, persister le nouveau message
        $entityManager->persist($newMessage);
        $entityManager->flush();

        // Rediriger vers une autre page (peut-être la liste des messages)
        return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
    }

    // Rendre la vue avec les données nécessaires
    return $this->render('message/messages.html.twig', [
        'discussion' => $discussion,
        'messages' => $messages,
        'form' => $form->createView(),
    ]);
}*/
/*#[Route('/discussion/{iddis}/messages', name: 'app_discussion_show_messages',  methods: ['GET', 'POST'])]
    public function showMessages(Discussion $discussion, Request $request, EntityManagerInterface $entityManager): Response 
    {

        $messages = $discussion->getMessages(); 
        dump($messages);
        
        $newMessage = new Message();
        $newMessage->setDiscussion($discussion); // Associer le message à la discussion
        
        $form = $this->createForm(MessageType::class, $newMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($newMessage);
            $entityManager->flush();
            return $this->redirectToRoute('app_discussion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('message/messages.html.twig', [
            'discussion' => $discussion,
            'messages' => $messages,
            'form' => $form->createView(),
        ]);
    }*/


}
