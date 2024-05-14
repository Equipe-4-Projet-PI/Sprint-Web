<?php

namespace App\Controller;

use App\Entity\Auction;
use App\Entity\Product;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\AuctionParticipant;
use App\Form\AuctionType;
use App\Repository\AuctionParticipantRepository;
use App\Repository\AuctionRepository;
use App\Repository\EventRepository;
use App\Repository\ForumRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use App\Service\SwapiService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

use DateTime;

#[Route('/auction')]
class AuctionController extends AbstractController
{
    #[Route('-gagnant{idAuction}{id_user}{etat}', name: 'gagnant')]
    public function gagnant(AuctionParticipantRepository $apRepo, $id_user, $idAuction, $etat): Response
    {
        $gagnant = $apRepo->findGagnant($idAuction);
        $message = '';

        if ($id_user == $gagnant) { 
            $message = "Bravo vous avez gagnez ! ";
            return $this->render('auction/gagnant.html.twig' , [
                'message' => $message,
                'id_user'=> $id_user,
            ]);
        } else {
            $message = "Cette enchère est terminé ! ";
            return $this->render('auction/gagnant.html.twig' , [
                'message' => $message,
                'id_user'=> $id_user,
            ]);
        }
    }

    #[Route('_new{id_user}', name: 'app_auction_new', methods: ['GET', 'POST'])]
    public function new(AuctionRepository $Arepo, ProductRepository $Prepo, Request $request, EntityManagerInterface $entityManager, $id_user): Response
    {
        $auction = new Auction();
        $auction->setIdArtist($id_user);
        $Products = [];
        $Products = $Arepo->findByUserId($id_user);
        $form = $this->createForm(AuctionType::class, $auction, ['id_user' => $id_user, 'prod' => $Products]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Récupérer l'ID du produit sélectionné dans le formulaire
            $idProduit = $auction->getIdProduit();
            $idProd = $Prepo->find($auction->getIdProduit());


            // Affecter l'ID du produit à l'entité Auction
            $auction->setIdProduit($idProd->getIdProduct());

            $entityManager->persist($auction);
            $entityManager->flush();

            return $this->redirectToRoute('app_auction_index', ['id_user' => $id_user], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('auction/new.html.twig', [
            'auction' => $auction,
            'form' => $form,
            'id_user' => $id_user,
        ]);
    }



    #[Route('/auctionlistsearchres_{id_user}', name: 'auction_search_res')]
    public function searchfunc(Request $request, AuctionRepository $auctionRepository, $id_user): Response
    {
        $query = $request->query->get('query');
        $forums = $auctionRepository->search($query);

        if ($query != null) {
            $auction = $auctionRepository->SEARCH($query);
        } else {
            $auction = $auctionRepository->findAll();
        }

        return $this->render('auction/searchResults.html.twig', [
            'auctions' => $forums,
            'id_user' => $id_user,
            'etat'=> "live",
        ]);
    }
    #[Route('/auctionlistsearch_{id_user}', name: 'auctions_search')]
    public function search(AuctionRepository $repo, Request $request, $id_user): Response
    {

        $searchTerm = $request->query->get('query');

        // Perform search logic here
        if ($searchTerm != null) {
            $searchResults = $repo->SEARCH($searchTerm);
        } else {
            $searchResults = $repo->findAll();
        }



        return $this->render('auction/search.html.twig', [
            'auctions' => $searchResults,
            'id_user' => $id_user,
        ]);
    }


    #[Route('_home{id_user}', name: 'app_auction_index', methods: ['GET'])]
    public function index(UserRepository $uRepo,  AuctionRepository $auctionRepository, AuctionParticipantRepository $apRepo, PaginatorInterface $pi, Request $req, $id_user): Response
    {
        $user = $uRepo->find($id_user);

        // Initialiser la variable user_is_artist à false
        $user_is_artist = false;

        // Vérifier si l'utilisateur possède un rôle d'artiste
        if ($user && is_string($user->getRole())) {
            $role = $user->getRole();

            if ($role === 'Artist') {
                $user_is_artist = true;
            }
        }

        $data = $auctionRepository->findAll();
        $auctions = $pi->paginate(
            $data,
            $req->query->getInt('page', 1),
            3
        );
        $participants = $apRepo->findAll();

        $participantWithRating = [];
        $averageRating = [];

        foreach ($auctions as $a) {
            $participantWithRating[$a->getIdAuction()] = $apRepo->countParticipantsWithRating($a->getIdAuction());
            $averageRating[$a->getIdAuction()] = $apRepo->averageRatingForAuction($a->getIdAuction());
        }
        $now = new DateTime();

        return $this->render('auction/index.html.twig', [
            'auctions' => $auctions,
            'participants' => $participants,
            'PartsRating' => $participantWithRating,
            'AvgRating' => $averageRating,
            'id_user' => $id_user,
            'user_is_artist' => $user_is_artist,
            'now' => $now,
        ]);
    }


    #[Route('--homeArtist{id_user}', name: 'artist', methods: ['GET'])]
    public function indexArtist(UserRepository $uRepo, AuctionRepository $auctionRepository, AuctionParticipantRepository $apRepo, PaginatorInterface $pi, Request $req, $id_user): Response
    {

        $user = $uRepo->find($id_user);

        // Initialiser la variable user_is_artist à false
        $user_is_artist = false;

        // Vérifier si l'utilisateur possède un rôle d'artiste
        if ($user && is_string($user->getRole())) {
            $role = $user->getRole();

            if ($role === 'Artist') {
                $user_is_artist = true;
            }
        }
        $auctions = $auctionRepository->findBy(['idArtist' => $id_user]);

        $participants = $apRepo->findAll();

        $participantWithRating = [];
        $averageRating = [];

        foreach ($auctions as $a) {
            $participantWithRating[$a->getIdAuction()] = $apRepo->countParticipantsWithRating($a->getIdAuction());
            $averageRating[$a->getIdAuction()] = $apRepo->averageRatingForAuction($a->getIdAuction());
        }
        $now = new DateTime();


        return $this->render('auction/artistEnchere.html.twig', [
            'auctions' => $auctions,
            'participants' => $participants,
            'PartsRating' => $participantWithRating,
            'AvgRating' => $averageRating,
            'id_user' => $id_user,
            'user_is_artist' => $user_is_artist,
            'now' => $now,
        ]);
    }

    /* #[Route('_new{id_user}', name: 'app_auction_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, $id_user ,  ProductRepository $productRepo): Response
{
    $auction = new Auction();
    $auction->setIdArtist($id_user);

    $form = $this->createForm(AuctionType::class, $auction, ['id_user' => $id_user]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer l'ID du produit sélectionné dans le formulaire
        $idProduit = $auction->getIdProduit();

        // Affecter l'ID du produit à l'entité Auction
        $auction->setIdProduit($idProduit);

        $entityManager->persist($auction);
        $entityManager->flush();

        return $this->redirectToRoute('app_auction_index', ['id_user' => $id_user], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('auction/new.html.twig', [
        'auction' => $auction,
        'form' => $form,
        'id_user'=> $id_user,
    ]);
}
*/
    #[Route('_{idAuction}_{id_user}_{etat}', name: 'app_auction_show', methods: ['GET'])]
    public function show($etat, UserRepository $uRepo,  Auction $auction, AuctionParticipantRepository $apRepo, $id_user, ProductRepository $pRepo): Response
    {
        $idAuction = $auction->getIdAuction();
        $participants = $apRepo->findBy(['idAuction' => $idAuction]);
        $numberParticipants = count($participants);
        $produit = $pRepo->find($auction->getIdProduit());
        $artist = $uRepo -> find(['idUser'=> $id_user]);
        if ($artist !== null) {
            $nomArtist = $artist->getUsername();
        } else {
            $nomArtist = "our artist";
        }


        return $this->render('auction/show.html.twig', [
            'auction' => $auction,
            'numberParticipants' => $numberParticipants,
            'id_user' => $id_user,
            'produit' => $produit,
            'nomArtist'=> $nomArtist,
            'etat'=> $etat,
        ]);
    }
    

    #[Route('_{idAuction}_edit{id_user}', name: 'app_auction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Auction $auction, EntityManagerInterface $entityManager, $id_user): Response
    {
        $form = $this->createForm(AuctionType::class, $auction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_auction_index', ['id_user' => $id_user], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('auction/edit.html.twig', [
            'auction' => $auction,
            'form' => $form,
            'id_user' => $id_user,
        ]);
    }

    #[Route('_{idAuction}_delete{id_user}', name: 'app_auction_delete', methods: ['POST'])]
    public function delete(Request $request, Auction $auction, EntityManagerInterface $entityManager, $id_user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $auction->getIdAuction(), $request->request->get('_token'))) {
            $entityManager->remove($auction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_auction_index', ['id_user' => $id_user], Response::HTTP_SEE_OTHER);
    }

    #[Route('/submit-price_{id_user}', name: "submit_price", methods: ['POST'])]
    public function submitPrice($id_user, UserRepository $uRepo, Request $request,  AuctionRepository $aRepo, EntityManagerInterface $entityManager,  AuctionParticipantRepository $apRepo): Response
    {
        $idAuction = $request->request->get('idAuction');
        $price = $request->request->get('price');
        $auction = $aRepo->find($idAuction);

        $participant = $apRepo->findOneBy(['idParticipant' => $id_user, 'idAuction' => $idAuction]);

        if (!$auction) {
            throw $this->createNotFoundException('Auction not found');
        }

        if ($participant) {
            $participant->setPrix($price);
            $participant->setDate();
            $auction-> setPrixFinal($price);
        } else {
            $participant = new AuctionParticipant();
            $user = $uRepo->find($id_user);
            $participant->setIdParticipant($user);
            $participant->setIdAuction($auction);
            $participant->setDate();
            $participant->setPrix($price);
            $auction-> setPrixFinal($price);
            $entityManager->persist($participant);
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_auction_show', [
            'idAuction' => $auction->getIdArtist(),
            'id_user' => $id_user,
        ]);
    }

    

    #[Route("/save-rating_{id_user}", name: "save_rating", methods: ["GET", "POST"])]
    public function saveRating(AuctionRepository $aRepo, EntityManagerInterface $entityManager, Request $request,  $id_user, AuctionParticipantRepository $apRepo, UserRepository $uRepo): Response
    {
        $idAuction = $request->request->get('idAuction');
        $ratingValue = $request->request->get('rating');

        $auction = $aRepo->find($idAuction);
        $participant = $apRepo->findOneBy(['idParticipant' => $id_user, 'idAuction' => $idAuction]);

        if (!$auction) {
            throw $this->createNotFoundException('Auction not found');
        }

        if ($participant !== null) {
            $participant->setRating($ratingValue);
        } else {
            $auctionParticipant = new AuctionParticipant();
            $auctionParticipant->setRating($ratingValue);
            $auctionParticipant->setDate(new \DateTime());
            $auctionParticipant->setIdAuction($auction);
            $auctionParticipant->setIdParticipant($uRepo->find($id_user));
            $entityManager->persist($auctionParticipant);
        }

        $entityManager->flush();
        return $this->redirectToRoute('app_auction_show', [
            'idAuction' => $auction->getIdAuction(),
            'id_user' => $id_user,
        ]);
    }

    #[Route('/save-love-click/{id_user}', name: 'save_love_click', methods: ['GET', 'POST'])]
    public function saveLoveClick(AuctionRepository $aRepo, EntityManagerInterface $entityManager, Request $request,  $id_user, AuctionParticipantRepository $apRepo, UserRepository $uRepo): Response
    {
        $idAuction = $request->request->get('idAuction');


        $auction = $aRepo->find($idAuction);
        $participant = $apRepo->findOneBy(['idParticipant' => $id_user, 'idAuction' => $idAuction]);

        if (!$auction) {
            throw $this->createNotFoundException('Auction not found');
        }

        if ($participant !== null) {
            $participant->setLove(1);
        } else {
            $auctionParticipant = new AuctionParticipant();
            $auctionParticipant->setDate(new \DateTime());
            $auctionParticipant->setLove(1);
            $auctionParticipant->setIdAuction($auction);
            $auctionParticipant->setIdParticipant($uRepo->find($id_user));
            $entityManager->persist($auctionParticipant);
        }

        $entityManager->flush();
        return $this->redirectToRoute('app_auction_show', [
            'idAuction' => $auction->getIdArtist(),
            'id_user' => $id_user,
        ]);
    }

    #[Route('/AdminAuc', name: 'AdminAuc')]
    public function AdminAuc(AuctionRepository $repoAuc, UserRepository $repo, ForumRepository $repoF, ProductRepository $repoP, EventRepository $repoE): Response
    {
        $Auctions = $repoAuc->findAll();
        $NumForums = $repoF->numberOfForums();
        $usernumbers = $repo->numberOfUsers();
        $productsnumbers = $repoP->numberOfProducts();
        $eventnumbers = $repoE->numberOfEvents();
        $auctionnumbers = $repoAuc->numberOfAuctions();
        return $this->render('admin/AuctionAdmin.html.twig', [
            'Auctions' => $Auctions,
            'usernumber' => $usernumbers,
            'NumForms' => $NumForums,
            'productnumber' => $productsnumbers,
            'NumEvents' => $eventnumbers,
            'NumAuctions' => $auctionnumbers,
        ]);
    }

    #[Route('delete/{id}', name: 'Admin_delete')]
    public function deleteAuthor(ManagerRegistry $manager, AuctionRepository $repo, $id)
    {
        $auction = $repo->find($id);
        $manager->getManager()->remove($auction);
        $manager->getManager()->flush();
        return $this->redirectToRoute('AdminAuc');
    }
}
