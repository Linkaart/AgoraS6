<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\MembreType;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/membres')]
final class MembresController extends AbstractController
{
    #[Route('/',name: 'app_membres_index', methods: ['GET'])]
    public function index(MembreRepository $membreRepository): Response
    {
        return $this->render('membres/index.html.twig', [
            'membres' => $membreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_membres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, 
    EntityManagerInterface $entityManager, 
    UserPasswordHasherInterface $unPasswordHasher): Response {
        $membre = new Membre();
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordSaisi = $membre->getPassword();
            $membre->setPassword($unPasswordHasher->hashPassword($membre, $passwordSaisi));

            $entityManager->persist($membre);
            $entityManager->flush();

            return $this->redirectToRoute('app_membres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('membres/new.html.twig', [
            'membre' => $membre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_membres_show', methods: ['GET'])]
    public function show(Membre $membre): Response
    {
        return $this->render('membres/show.html.twig', [
            'membre' => $membre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_membres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,   
    Membre $membre, 
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $unPasswordHasher): Response {
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordSaisi = $membre->getPassword();
            $membre->setPassword($unPasswordHasher->hashPassword($membre, $passwordSaisi));

            $entityManager->flush();
            return $this->redirectToRoute('app_membres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('membres/edit.html.twig', [
            'membre' => $membre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_membres_delete', methods: ['POST'])]
    public function delete(Request $request, Membre $membre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$membre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($membre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_membres_index', [], Response::HTTP_SEE_OTHER);
    }
}
