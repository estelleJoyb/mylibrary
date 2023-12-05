<?php

namespace App\Controller\Backend;

use App\Entity\Auteur;
use App\Form\AuteurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auteur', name: 'app.auteur')]
class AuteurController extends AbstractController {
    public function __construct(private AuteurRepository $auteurRepository, private EntityManagerInterface $em) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(AuteurRepository $auteurRepository): Response {
        return $this->render('frontend/auteur/index.html.twig', [
            'auteurs' => $auteurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: '.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response|RedirectResponse {
        $auteur = new Auteur();
        //recover the form used for create an auteur
        //it updates the variable auteur
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request); //inspect the given request → check if the form has been submit

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($auteur); //save in the database
            $em->flush();
            $this->addFlash('success', 'Auteur créé avec succès !'); //user feedback
            return $this->redirectToRoute('app.auteur.index');
        }

        return $this->render('frontend/auteur/new.html.twig', [
            'auteur' => $auteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: '.edit', methods: ['GET', 'POST'])]
    public function edit(?Auteur $auteur, Request $request): Response|RedirectResponse {
        if(!$auteur) { //OR (!$auteur instanceof Auteur) { //check if auteur is not an auteur
            $this->addFlash('error', 'Auteur non trouvé');

            return $this->redirectToRoute('app.auteur.index');
        }

        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request); //inspect the given request → check if the form has been submit

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($auteur);
            $this->em->flush();
            $this->addFlash('success', 'Auteur mis à jour avec succes'); //little message (displayed once)

            return $this->redirectToRoute('app.auteur.index');
        }

        return $this->render('Frontend/auteur/edit.html.twig', [
            'form' => $form,
            'auteur' => $auteur,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Auteur $auteur, Request $request): RedirectResponse {
        if(!$auteur) { //OR (!$auteur instanceof Auteur) { //check if Auteur is not an author
            $this->addFlash('error', 'Auteur non trouvé');

            return $this->redirectToRoute('app.auteur.index');
        }

        if($this->isCsrfTokenValid('delete'.$auteur->getId(), $request->request->get('token'))) {
            $this->em->remove($auteur);
            $this->em->flush();
            $this->addFlash('success', 'Auteur supprimé avec succes'); //little message (displayed once)

            return $this->redirectToRoute('app.auteur.index');
        }
        $this->addFlash('error', 'Token CSRF invalide');

        return $this->redirectToRoute('app.auteur.index');

    }

    #[Route('/{id}/read', name: '.read', methods: ['GET'])]
    public function read(?Auteur $auteur, AuteurRepository $auteurRepository): Response {
        if(!$auteur) { //OR (!$auteur instanceof Auteur) { //check if Auteur is not an author
            $this->addFlash('error', 'Auteur non trouvé');
            return $this->redirectToRoute('app.auteur.index');
        }
        return $this->render('Frontend/auteur/read.html.twig', [
            'auteur' => $auteurRepository->findOneById(['id' => $auteur->getId()])
        ]);
    }
}
