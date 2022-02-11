<?php

namespace App\Controller\Admin;

use App\Entity\Technos;
use App\Form\TechnosType;
use App\Repository\TechnosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/admin/technos", name="admin_technos_" )
 */
class TechnosController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(TechnosRepository $technosRepository): Response
    {
        return $this->render('admin/technos/index.html.twig', [
            'technos' => $technosRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $techno = new Technos();
        $form = $this->createForm(TechnosType::class, $techno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('picture')->getData();
            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                // ceci est nécessaire pour inclure en toute sécurité le nom de fichier dans l'URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();
                // Déplacez le fichier dans le répertoire où les brochures sont stockées
                try {
                    $picture->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... gérer l'exception si quelque chose se produit pendant le téléchargement du fichier
                }
                // met à jour la propriété 'photoLicencie' pour stocker le nom du fichier PDF
                // au lieu de son contenu
                $techno->setPicture($newFilename);
            }

            $entityManager->persist($techno);
            $entityManager->flush();

            return $this->redirectToRoute('admin_technos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/technos/new.html.twig', [
            'techno' => $techno,
            'form' => $form,
        ]);
    }



    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Technos $techno, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TechnosType::class, $techno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_technos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/technos/edit.html.twig', [
            'techno' => $techno,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Technos $techno, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $techno->getId(), $request->request->get('_token'))) {
            $entityManager->remove($techno);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_technos_index', [], Response::HTTP_SEE_OTHER);
    }
}
