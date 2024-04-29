<?php

namespace App\Controller;

use App\Entity\Auction;
use App\Form\AuctionType;
use App\Repository\AuctionParticipantRepository;
use App\Repository\AuctionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auction')]
class AuctionController extends AbstractController
{
    #[Route('_', name: 'app_auction_index', methods: ['GET'])]
    public function index(AuctionRepository $auctionRepository , AuctionParticipantRepository $apRepo): Response
    {
        $auctions = $auctionRepository->findAll();
        $participants = $apRepo -> findAll();
        return $this->render('auction/index.html.twig', [
            'auctions' => $auctions,
            'participants' => $participants,
        ]);
    }

    #[Route('_new', name: 'app_auction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $auction = new Auction();
        $auction->setIdArtist(1) ;
        $form = $this->createForm(AuctionType::class, $auction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($auction);
            $entityManager->flush();

            return $this->redirectToRoute('app_auction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('auction/new.html.twig', [
            'auction' => $auction,
            'form' => $form,
        ]);
    }

    #[Route('_{idAuction}', name: 'app_auction_show', methods: ['GET'])]
    public function show(Auction $auction): Response
    {
        return $this->render('auction/show.html.twig', [
            'auction' => $auction,
        ]);
    }

    #[Route('_{idAuction}/edit', name: 'app_auction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Auction $auction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuctionType::class, $auction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_auction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('auction/edit.html.twig', [
            'auction' => $auction,
            'form' => $form,
        ]);
    }

    #[Route('_{idAuction}_delete', name: 'app_auction_delete', methods: ['POST'])]
    public function delete(Request $request, Auction $auction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auction->getIdAuction(), $request->request->get('_token'))) {
            $entityManager->remove($auction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_auction_index', [], Response::HTTP_SEE_OTHER);
    }

     #[Route('_submit-price', name:"submit_price", methods:['POST'])]
    public function submitPrice(Request $request): Response
    {
        $price = $request->request->get('price');

        return new Response('Price submitted: ' . $price);
    }
}
