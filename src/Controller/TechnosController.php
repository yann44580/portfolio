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
 * @Route("/technos" )
 */
class TechnosController extends AbstractController
{
  
   

    /**
     * @Route("/{id}", name="technos_show", methods={"GET"})
     */
    public function show(Technos $techno): Response
    {
        return $this->render('technos/show.html.twig', [
            'techno' => $techno,
        ]);
    }

   
}
