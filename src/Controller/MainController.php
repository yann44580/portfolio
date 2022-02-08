<?php

namespace App\Controller;

use App\Repository\TechnosRepository;
use App\Repository\ProjectsRepository;
use App\Repository\FormationsRepository;
use App\Repository\CompetencesRepository;
use App\Repository\PresentationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PresentationRepository $presentationRepository, CompetencesRepository $competencesRepository, FormationsRepository $formationsRepository, ProjectsRepository $projectsRepository, TechnosRepository $technosRepository  ): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'presentations' => $presentationRepository->findAll(),
            'competences' => $competencesRepository->findAll(),
            'formations' => $formationsRepository->findAll(),
            'projects' => $projectsRepository->findAll(),
            'technos' => $technosRepository->findAll(),

        ]);
    }
}
