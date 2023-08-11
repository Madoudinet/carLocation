<?php

namespace App\Controller;

use App\Repository\VehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    #[Route('/vehicules', name: 'app_vehicules')]
    public function vehicules(VehiculeRepository $repo): Response
    {
        $vehicules = $repo->findAll();
        return $this->render('app/vehicules.html.twig', [
            'vehicules' => $vehicules,
        ]);
    }

    #[Route('/vehicule/{id}', name: 'show_vehicule')]
    public function show($id, VehiculeRepository $repo)
    {
        $vehicule = $repo->find($id);
        return $this->render('app/show.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }

}
