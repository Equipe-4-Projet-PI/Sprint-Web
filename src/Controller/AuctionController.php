<?php

namespace App\Controller;

use App\Entity\Auction;
use App\Entity\AuctionParticipant;
use App\Form\AuctionType;
use App\Repository\AuctionParticipantRepository;
use App\Repository\AuctionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auction')]
class AuctionController extends AbstractController
{
    #[Route('_', name: 'app_auction_index', methods: ['GET'])]
    public function index(AuctionRepository $auctionRepository, AuctionParticipantRepository $apRepo): Response
    {
        $auctions = $auctionRepository->findAll();
        $participants = $apRepo->findAll();

        $participantWithRating = [];
        $averageRating = [];

        foreach ($auctions as $a) {
            $participantWithRating[$a->getIdAuction()] = $apRepo->countParticipantsWithRating($a->getIdAuction());
            $averageRating[$a->getIdAuction()] = $apRepo->averageRatingForAuction($a->getIdAuction());
        }

        return $this->render('auction/index.html.twig', [
            'auctions' => $auctions,
            'participants' => $participants,
            'PartsRating' => $participantWithRating,
            'AvgRating' => $averageRating,
        ]);
    }

    #[Route('_new', name: 'app_auction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $auction = new Auction();
        $auction->setIdArtist(1);
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
    public function show(Auction $auction, AuctionParticipantRepository $apRepo): Response
    {
        $idAuction = $auction->getIdAuction();
        $participants = $apRepo->findBy(['idAuction' => $idAuction]);
        $numberParticipants = count($participants);

        return $this->render('auction/show.html.twig', [
            'auction' => $auction,
            'numberParticipants' => $numberParticipants,
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
        if ($this->isCsrfTokenValid('delete' . $auction->getIdAuction(), $request->request->get('_token'))) {
            $entityManager->remove($auction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_auction_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/submit-price_{idUser}', name: "submit_price", methods: ['POST'])]
    public function submitPrice( UserRepository $uRepo ,$idUser , Request $request ,  AuctionRepository $aRepo , EntityManagerInterface $entityManager,  AuctionParticipantRepository $apRepo): Response
    {
        $idAuction = $request->request->get('idAuction');
        $price = $request->request->get('price');
        $auction = $aRepo->find($idAuction);

        $participant = $apRepo->findOneBy(['idParticipant' => $idUser, 'idAuction' => $idAuction]);
        
        if (!$auction) {
            throw $this->createNotFoundException('Auction not found');
        }
        
        if ($participant) {
            $participant->setPrix($price);
        } else {
            $participant = new AuctionParticipant();
            $user = $uRepo -> find($idUser);
            $participant->setIdParticipant($user);
            $participant->setIdAuction($auction);
            $participant->setDate();
            $participant->setPrix($price);
            $entityManager->persist($participant);
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_auction_show', [
            'idAuction' => $auction->getIdArtist(),
        ]);
    }
}
