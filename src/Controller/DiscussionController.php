<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Form\DiscussionType;
use App\Repository\DiscussionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/discussion')]
class DiscussionController extends AbstractController
{
    #[Route('/', name: 'app_discussion_index', methods: ['GET'])]
    public function index(DiscussionRepository $discussionRepository): Response
    {
        return $this->render('discussion/index.html.twig', [
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
}
