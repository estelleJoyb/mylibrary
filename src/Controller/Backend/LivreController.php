<?php

namespace App\Controller\Backend;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/livre', name: 'app.livre')]
class LivreController extends AbstractController {
    public function __construct(private LivreRepository $livreRepository, private EntityManagerInterface $em) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository): Response {
        return $this->render('frontend/livre/index.html.twig', [
            'livres' => $livreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: '.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response|RedirectResponse {
        $livre = new Livre();
        //recover the form used for create a livre
        //it updates the variable livre
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request); //inspect the given request → check if the form has been submit

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($livre); //save in the database
            $em->flush();
            $this->addFlash('success', 'Livre créé avec succès !'); //user feedback
            return $this->redirectToRoute('app.livre.index');
        }

        return $this->render('frontend/livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: '.edit', methods: ['GET', 'POST'])]
    public function edit(?Livre $livre, Request $request): Response|RedirectResponse {
        if(!$livre) { //OR (!$livre instanceof Livre) { //check if livre is not a livre
            $this->addFlash('error', 'Livre non trouvé');

            return $this->redirectToRoute('app.livre.index');
        }

        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request); //inspect the given request → check if the form has been submit

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($livre);
            $this->em->flush();
            $this->addFlash('success', 'Livre mis à jour avec succes'); //little message (displayed once)

            return $this->redirectToRoute('app.livre.index');
        }

        return $this->render('Frontend/livre/edit.html.twig', [
            'form' => $form,
            'livre' => $livre,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Livre $livre, Request $request): RedirectResponse {
        if(!$livre) { //OR (!$livre instanceof Livre) { //check if Livre is not an author
            $this->addFlash('error', 'Livre non trouvé');

            return $this->redirectToRoute('app.livre.index');
        }

        if($this->isCsrfTokenValid('delete'.$livre->getId(), $request->request->get('token'))) {
            $this->em->remove($livre);
            $this->em->flush();
            $this->addFlash('success', 'Livre supprimé avec succes'); //little message (displayed once)

            return $this->redirectToRoute('app.livre.index');
        }
        $this->addFlash('error', 'Token CSRF invalide');

        return $this->redirectToRoute('app.livre.index');

    }

    #[Route('/{id}/read', name: '.read', methods: ['GET'])]
    public function read(?Livre $livre, LivreRepository $livreRepository): Response {
        if(!$livre) { //OR (!$livre instanceof Livre) { //check if Livre is not a livre
            $this->addFlash('error', 'Livre non trouvé');
            return $this->redirectToRoute('app.livre.index');
        }
        return $this->render('Frontend/livre/read.html.twig', [
            'livre' => $livreRepository->findOneById(['id' => $livre->getId()])
        ]);
    }
}