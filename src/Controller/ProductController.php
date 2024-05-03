<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/addprod', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UserRepository $repoUser): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
            //setting the time
            $today = new DateTime();
            $formattedDate = $today->format('d-m-Y');
            $product->setCreationdate($formattedDate);
            //setting User
            $User = $repoUser->find(4);
            $product->setIdUser($User);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('productimage')->getData();
            if ($file) {
                $fileName = uniqid().'.'.$file->guessExtension();
                // Move the file to the desired directory
                $file->move(
                    $this->getParameter('upload_directory'),
                    $fileName
                );
                // Store the file path in the database
                $filePath = 'C:/Users/bigal/OneDrive - ESPRIT/Bureau/Sprint-for ali/public/uploads/' . $fileName;
                $product->setProductimage($filePath);
            }
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/show{idProduct}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/edit{idProduct}', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager,UserRepository $repoUser): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
            //setting the time
            $today = new DateTime();
            $formattedDate = $today->format('d-m-Y');
            $product->setCreationdate($formattedDate);
            //setting User
            $User = $repoUser->find(4);
            $product->setIdUser($User);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('productimage')->getData();
            if ($file) {
                $fileName = uniqid().'.'.$file->guessExtension();
                // Move the file to the desired directory
                $file->move(
                    $this->getParameter('upload_directory'),
                    $fileName
                );
                // Store the file path in the database
                $filePath = 'C:/Users/bigal/OneDrive - ESPRIT/Bureau/Sprint-for ali/public/uploads/' . $fileName;
                $product->setProductimage($filePath);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/delete{idProduct}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getIdProduct(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/Adminprods', name: 'Admin')]
    public function Admin( ProductRepository $repo): Response
    {
        $prods = $repo->findAll() ; 
        return $this->render('admin/ProdsAdmin.html.twig', [
            'prods' => $prods ,
        ]);
    }
    #[Route('/product_forsale', name: 'app_product_forsale', methods: ['GET'])]
    public function forsaleprod(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findBy(['forsale' => true]),
        ]);
    }
    #[Route('/product_notforsale', name: 'app_product_notforsale', methods: ['GET'])]
    public function notforsaleprod(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findBy(['forsale' => false]),
        ]);
    }
    #[Route('/app_search', name: 'app_search', methods: ['GET'])]
    public function search(Request $request,ProductRepository $productRepository): Response
    {
        $searchBy = $request->query->get('searchby');
        $searchText = $request->query->get('searchtext');
        // Query the database based on search criteria
         if ($searchBy && $searchText) {
        if ($searchBy === 'title') {
            $products = $productRepository->findByPartialTitle($searchText);
        } elseif ($searchBy === 'description') {
            $products = $productRepository->findByPartialDescription($searchText);
        } else {
            // Handle invalid searchBy value
            $products = [];
        }
        } else {
        // Handle case when searchBy or searchText is not provided
        $products = $productRepository->findAll(); // Or any default logic you want
         }

    return $this->render('product/index.html.twig', [
        'products' => $products,
    ]);
    }
}
