<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function show($id, VehiculeRepository $repo, Request $rq, EntityManagerInterface $manager)
    {
        $vehicule = $repo->find($id);

        $commande = new Commande;
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($rq);

        if($form->isSubmitted() && $form->isValid())
        {
            $commande
                     
                     ->setVehicule($vehicule);

            $manager->persist($commande);
            $manager->flush();
            $this->addFlash('success', "Votre commande a bien été pris en compte");
            return $this->redirectToRoute('show_vehicule', ['id' => $id]);
        }

        return $this->render('app/show.html.twig', [
            'vehicule' => $vehicule,
            'commande' => $form,
        ]);
    }

}
