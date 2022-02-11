<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Form\CompetencesType;
use App\Repository\CompetencesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/competences")
 */
class CompetencesController extends AbstractController
{
    /**
     * @Route("/", name="competences_index", methods={"GET"})
     */
    public function index(CompetencesRepository $competencesRepository): Response
    {
        return $this->render('competences/index.html.twig', [
            'competences' => $competencesRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{id}", name="competences_show", methods={"GET"})
     */
    public function show(Competences $competence): Response
    {
        return $this->render('competences/show.html.twig', [
            'competence' => $competence,
        ]);
    }


  
}
