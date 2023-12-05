<?php

namespace App\Controller\Backend;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie', name: 'app.categorie')]
class CategorieController extends AbstractController {
    public function __construct(private AuteurRepository $auteurRepository, private EntityManagerInterface $em) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response {
        return $this->render('frontend/categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: '.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response|RedirectResponse {
        $categorie = new Categorie();
        //recover the form used for create a categorie
        //it updates the variable catégorie
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request); //inspect the given request → check if the form has been submit

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($categorie); //save in the database
            $em->flush();
            $this->addFlash('success', 'Catégorie créé avec succès !'); //user feedback
            return $this->redirectToRoute('app.categorie.index');
        }

        return $this->render('frontend/categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: '.edit', methods: ['GET', 'POST'])]
    public function edit(?Categorie $categorie, Request $request): Response|RedirectResponse {
        if(!$categorie) { //check if catégorie is not an catégorie
            $this->addFlash('error', 'Catégorie non trouvé');

            return $this->redirectToRoute('app.categorie.index');
        }

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request); //inspect the given request → check if the form has been submit

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($categorie);
            $this->em->flush();
            $this->addFlash('success', 'Catégorie mis à jour avec succes'); //little message (displayed once)

            return $this->redirectToRoute('app.categorie.index');
        }

        return $this->render('Frontend/categorie/edit.html.twig', [
            'form' => $form,
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Categorie $categorie, Request $request): RedirectResponse {
        if(!$categorie) { //OR (!$categorie instanceof categorie) { //check if categorie is not an categorie
            $this->addFlash('error', 'Catégorie non trouvée');

            return $this->redirectToRoute('app.categorie.index');
        }

        if($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('token'))) {
            $this->em->remove($categorie);
            $this->em->flush();
            $this->addFlash('success', 'Catégorie supprimée avec succes'); //little message (displayed once)

            return $this->redirectToRoute('app.categorie.index');
        }
        $this->addFlash('error', 'Token CSRF invalide');

        return $this->redirectToRoute('app.categorie.index');

    }

    #[Route('/{id}/read', name: '.read', methods: ['GET'])]
    public function read(?Categorie $categorie, CategorieRepository $categorieRepository): Response {
        if(!$categorie) { //OR (!$categorie instanceof categorie) { //check if categorie is not an categorie
            $this->addFlash('error', 'Catégorie non trouvée');
            return $this->redirectToRoute('app.categorie.index');
        }
        return $this->render('Frontend/categorie/read.html.twig', [
            'categorie' => $categorieRepository->findOneById(['id' => $categorie->getId()])
        ]);
    }
}
