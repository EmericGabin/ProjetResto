<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Restaurent;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/restaurent/{id}', name: 'produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository, Restaurent $restaurent): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
            'restaurent' => $restaurent,
        ]);
    }

    #[Route('/new/restaurent/{id}', name: 'produit_new_restaurent', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Restaurent $restaurent): Response
    {
        dump($restaurent);
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit, [
            'restaurent' => $restaurent
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produit_index', ['id' => $produit->getRestaurent()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    { 
        $restaurent = $produit->getRestaurent();
        $form = $this->createFormBuilder($produit)
                    ->add('nom')
                    ->add('prix')
                    ->add('description')
                    ->add('restaurent', EntityType::class,[
                        'class' => Restaurent::class,
                        'choice_label' => 'nom',
                        'query_builder' => function(EntityRepository $er) use ($restaurent) {
                            return $er->createQueryBuilder('r')
                            ->where('r.id = :id')
                            ->setParameter(':id', $restaurent->getId());
                        }
                    ])
                    ->getForm()
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('produit_index', ['id' => $produit->getRestaurent()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $restaurentId = $produit->getRestaurent()->getId();
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_index', ['id' => $restaurentId], Response::HTTP_SEE_OTHER);
    }
}
