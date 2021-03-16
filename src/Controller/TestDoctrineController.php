<?php

namespace App\Controller;

use App\Entity\Project;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestDoctrineController extends AbstractController {
    /**
     * @Route("/test/doctrine", name="test_doctrine")
     */
    public function index(): Response {

        $project = new Project();       // On crée un projet

        // On le remplit
        $project->setName('Projet Symfony');
        $project->setDate(new DateTime());
        $project->setDescription('Ce projet utilise le framework Symfony version 5.2.');
        $project->setImage('https://www.josh-digital.com/wp-content/uploads/2019/05/Symfony-4-API-Platform-React.js-Full-Stack-Masterclass.jpg');

        // On récupère notre entity manager
        $entityManager = $this->getDoctrine()->getManager();
        
        // On lui dit de s'occuper de notre nouveau projet
        $entityManager->persist($project);

        // On "sauvegarde" toutes les modifications
        $entityManager->flush();

        return $this->render('test_doctrine/index.html.twig', [
            'controller_name' => 'TestDoctrineController',
        ]);
    }
}
