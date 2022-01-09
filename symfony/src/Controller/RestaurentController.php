<?php

namespace App\Controller;

use App\Entity\Restaurent;
use App\Form\RestaurentType;
use App\Repository\RestaurentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/restaurent')]
class RestaurentController extends AbstractController
{
    #[Route('/', name: 'restaurent_index', methods: ['GET'])]
    public function index(RestaurentRepository $restaurentRepository): Response
    {
        return $this->render('restaurent/index.html.twig', [
            'restaurents' => $restaurentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'restaurent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $restaurent = new Restaurent();
        $form = $this->createForm(RestaurentType::class, $restaurent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($restaurent);
            $entityManager->flush();

            return $this->redirectToRoute('restaurent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('restaurent/new.html.twig', [
            'restaurent' => $restaurent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'restaurent_show', methods: ['GET'])]
    public function show(Restaurent $restaurent): Response
    {
        return $this->render('restaurent/show.html.twig', [
            'restaurent' => $restaurent,
        ]);
    }

    #[Route('/{id}/edit/user={idUser}', name: 'restaurent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Restaurent $restaurent, EntityManagerInterface $entityManager): Response
    {
        $id=$request->get('idUser');
        $id=(int)$id; 
        dump($id);

        $form = $this->createForm(RestaurentType::class, $restaurent, ['id' => $id]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('restaurent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('restaurent/edit.html.twig', [
            'restaurent' => $restaurent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'restaurent_delete', methods: ['POST'])]
    public function delete(Request $request, Restaurent $restaurent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restaurent->getId(), $request->request->get('_token'))) {
            $entityManager->remove($restaurent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('restaurent_index', [], Response::HTTP_SEE_OTHER);
    }
}
