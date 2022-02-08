<?php

namespace App\Controller;

use App\Entity\Technos;
use App\Form\TechnosType;
use App\Repository\TechnosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/technos")
 */
class TechnosController extends AbstractController
{
    /**
     * @Route("/", name="technos_index", methods={"GET"})
     */
    public function index(TechnosRepository $technosRepository): Response
    {
        return $this->render('technos/index.html.twig', [
            'technos' => $technosRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="technos_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $techno = new Technos();
        $form = $this->createForm(TechnosType::class, $techno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($techno);
            $entityManager->flush();

            return $this->redirectToRoute('technos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('technos/new.html.twig', [
            'techno' => $techno,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="technos_show", methods={"GET"})
     */
    public function show(Technos $techno): Response
    {
        return $this->render('technos/show.html.twig', [
            'techno' => $techno,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="technos_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Technos $techno, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TechnosType::class, $techno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('technos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('technos/edit.html.twig', [
            'techno' => $techno,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="technos_delete", methods={"POST"})
     */
    public function delete(Request $request, Technos $techno, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$techno->getId(), $request->request->get('_token'))) {
            $entityManager->remove($techno);
            $entityManager->flush();
        }

        return $this->redirectToRoute('technos_index', [], Response::HTTP_SEE_OTHER);
    }
}
