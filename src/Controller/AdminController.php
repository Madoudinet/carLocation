<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Repository\CommandeRepository;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/vehicules/modifier/{id}', name: 'app_modifier')]
    #[Route('/vehicules/ajout', name: 'app_ajout')]
    public function form(Request $globals, EntityManagerInterface $manager, Vehicule $vehicule = null, SluggerInterface $slugger): Response
    {
        if($vehicule == null){
            $vehicule = new Vehicule;
        }

        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($globals);

        if($form->isSubmitted() && $form->isValid())
        {
            
            // ! Début traitement de l'image
            $imageFile = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                // * Permet de récuperer le nom de notre fichier de base
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                // * On enleve se qui gene dans le nom du fichier si on l'utilise en URL
                $safeFilename = $slugger->slug($originalFilename);

                // * On crée un nouveau nom de fichier pour notre image qui sera : nomSafeDuFichier-idUnique.extensionDuFichier
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('img_upload'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $vehicule->setPhoto($newFilename);
            }
            // ! fin du traitement de l'image
            // $vehicule->setDateEnregistrement(new \DateTime);
            $manager->persist($vehicule);
            $manager->flush();
            return $this->redirectToRoute('app_gestion');
        }

        return $this->render('admin/vehicules/form.html.twig', [
            'form' => $form,
            'editMode' => $vehicule->getId() !== null,
        ]);
    }
    
    #[Route('/vehicules/gestion', name: 'app_gestion')]
    public function gestion(VehiculeRepository $repo): Response
    {
        $vehicules = $repo->findAll();
        return $this->render('admin/vehicules/gestion.html.twig', [
            'vehicules' => $vehicules,
        ]);
    }
    
    #[Route('/vehicule/supprimer/{id}', name: 'app_supprimer')]
    public function supprimer(Vehicule $vehicule, EntityManagerInterface $manager): Response
    {
        $manager->remove($vehicule);
        $manager->flush();
        return $this->redirectToRoute('app_gestion');
    }

    #[Route('/users/gestion', name: 'app_gestion_users')]
    public function gestionUsers(UserRepository $repo): Response
    {
        $users = $repo->findAll();
        return $this->render('admin/users/gestion.html.twig', [
            'users' => $users,
        ]);
    }

    // #[Route('/users/modifier', name: 'user_modifier')]
    // public function modifyUser(Request $globals, EntityManagerInterface $manager, User $user)
    // {
    //     $user = new User;
    //     $form = $this->createForm(RegistrationFormType::class, $user);
    //     $form->handleRequest($globals);

    //     if($form->isSubmitted() && $form->isValid())
    //         {
    //             $manager->persist($user);
    //             $manager->flush();
    //             return $this->redirectToRoute('app_gestion_users');
    //         }
    //     return $this->render('registration/register.html.twig', [
    //         'form' => $form,
    //         'editMode' => $user->getId() !== null,
    //     ]);
    // }
    #[Route('/commandes/gestion', name: 'app_gestion_commandes')]
    public function gestionCommande(CommandeRepository $repo): Response
    {
        $commande = $repo->findAll();
        return $this->render('admin/vehicules/gestionCommandes.html.twig', [
            'commandes' => $commande,
        ]);
    }

}

