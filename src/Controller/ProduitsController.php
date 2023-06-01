<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitAjoutType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class ProduitsController extends AbstractController
{
    #[Route('/produits', name: 'app_produits')]
    #[IsGranted('ROLE_USER')]
    public function index(ProduitRepository $produitRepo): Response
    {
        $produits = $produitRepo->findAll();

        // dd($produits);

        
        return $this->render('produits/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/produits/ajouter', name: 'app_produits_ajout')]
    #[IsGranted('ROLE_USER')]
    public function ajouter(Request $request, EntityManagerInterface $manager): Response
    {
        $produit = new Produit();

        $form = $this->createForm(ProduitAjoutType::class, $produit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $produit = $form->getData();
            $produit->setUser($this->getUser());

            $manager->persist($produit);
            $manager->flush();

            return $this->redirectToRoute('app_produits_ajout');

        }

        return $this->render('produits/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produits/editer/{id}', name: 'app_produits_editer', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user === produit.getUser()")]
    public function editer(Produit $produit, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProduitAjoutType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData();

            $manager->persist($produit);
            $manager->flush();

            return $this->redirectToRoute('app_produits');
        }

        return $this->render('produits/editer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
