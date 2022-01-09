<?php

namespace App\Controller;

use App\Entity\LigneCommande;
use App\Form\LigneCommandeType;
use App\Repository\LigneCommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Commande;
use App\Entity\Produit;
use App\Repository\ProduitRepository;

#[Route('/ligne/commande')]
class LigneCommandeController extends AbstractController
{
    #[Route('/{id}', name: 'ligne_commande_index', methods: ['GET'])]
    public function index(LigneCommandeRepository $ligneCommandeRepository, Commande $commande): Response
    {
        return $this->render('ligne_commande/index.html.twig', [
            'ligne_commandes' => $ligneCommandeRepository->findAll(),
            'commande' => $commande,
        ]);
        
    }

    #[Route('/new/{id}', name: 'ligne_commande_new', methods: ['GET', 'POST'])]
    public function new(SessionInterface $session, Commande $commande, ProduitRepository $produitRepository, EntityManagerInterface $entityManager): Response
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $ligneDeCommande = new LigneCommande();
            $produit = $produitRepository->find($id);
            
            $dataPanier[] = [
                "produit" => $produit,
                "quantite" => $quantite
            ];

            $total += $produit->getPrix() * $quantite;
            $ligneDeCommande->setCommande($commande);
            $ligneDeCommande->setProduit($produit);
            $ligneDeCommande->setQuantite($quantite);
            $entityManager->persist($ligneDeCommande);
            $entityManager->flush();
        }

        $session->remove('panier');

         return $this->redirectToRoute('ligne_commande_index', ['id' => $commande->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/newForm/{id}', name: 'ligne_commande_form', methods: ['GET', 'POST'])]
    public function newForm(Request $request, SessionInterface $session, EntityManagerInterface $entityManager, Commande $commande): Response
    {
        $ligneCommande = new LigneCommande();
        $total = $commande->getPrix();

        $form = $this->createForm(LigneCommandeType::class, $ligneCommande, [
            'commande' => $commande,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $total += $ligneCommande->getProduit()->getPrix() * $ligneCommande->getQuantite();
            $commande->setPrix($total);

            $entityManager->persist($commande);
            $entityManager->persist($ligneCommande);

            $entityManager->flush();

            return $this->redirectToRoute('ligne_commande_index', ['id' => $commande->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ligne_commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'ligne_commande_show', methods: ['GET'])]
    public function show(LigneCommande $ligneCommande): Response
    {
        return $this->render('ligne_commande/show.html.twig', [
            'ligne_commande' => $ligneCommande,
        ]);

        
    }

    #[Route('/{id}/edit', name: 'ligne_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LigneCommande $ligneCommande, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $commande = $ligneCommande->getCommande();
        $quantite =$ligneCommande->getQuantite();

        $form = $this->createForm(LigneCommandeType::class, $ligneCommande, [
            'commande' => $commande,
        ]);
        $form->handleRequest($request);

        $total = $commande->getPrix();

        if ($form->isSubmitted() && $form->isValid()) {
            if($ligneCommande->getQuantite() > $quantite)
            {
                $total += $ligneCommande->getProduit()->getPrix() * $ligneCommande->getQuantite();
                $commande->setPrix($total);

                $entityManager->persist($commande);
                $entityManager->flush();
                return $this->redirectToRoute('ligne_commande_index', ['id' => $commande->getId()], Response::HTTP_SEE_OTHER);
            }
            elseif ($ligneCommande->getQuantite() == $quantite) {
                $commande->setPrix($total);

                $entityManager->persist($commande);
                $entityManager->flush();
                return $this->redirectToRoute('ligne_commande_index', ['id' => $commande->getId()], Response::HTTP_SEE_OTHER);
            }
            else {
                $total -= $ligneCommande->getPrix() * $ligneCommande->getQuantite();
                $commande->setPrix($total);

                $entityManager->persist($commande);
                $entityManager->flush();
                return $this->redirectToRoute('ligne_commande_index', ['id' => $commande->getId()], Response::HTTP_SEE_OTHER);
            }

        }

        return $this->renderForm('ligne_commande/edit.html.twig', [
            'ligne_commande' => $ligneCommande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'ligne_commande_delete', methods: ['POST'])]
    public function delete(Request $request, LigneCommande $ligneCommande, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $commande = $ligneCommande->getCommande();
        $total =$commande->getPrix();

        if ($this->isCsrfTokenValid('delete'.$ligneCommande->getId(), $request->request->get('_token'))) {
            $total -= $ligneCommande->getProduit()->getPrix() * $ligneCommande->getQuantite();
            $commande->setPrix($total);

            $entityManager->persist($commande);
            $entityManager->remove($ligneCommande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ligne_commande_index', ['id' => $commande->getId()], Response::HTTP_SEE_OTHER);
    }
}
