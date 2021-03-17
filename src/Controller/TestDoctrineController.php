<?php

namespace App\Controller;

use App\Entity\Project;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestDoctrineController extends AbstractController {
    /**
     * @Route("/test/doctrine/create", name="test_doctrine_create")
     */
    public function create(): Response {

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

        return $this->render('test_doctrine/index.html.twig');
    }

    /**
     * @Route("/test/doctrine/retrieve", name="test_doctrine_retrieve")
     */
    public function retrieve() {
        $repository = $this->getDoctrine()->getRepository(Project::class);

        $project = $repository->find(4);

        return $this->render('test_doctrine/index.html.twig');
    }

    /**
     * @Route("/test/doctrine/update", name="test_doctrine_update")
     */
    public function update() {
        $repository = $this->getDoctrine()->getRepository(Project::class);

        $project = $repository->find(3);

        $project->setName('Updated name 2');

        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();

        return $this->render('test_doctrine/index.html.twig');
    }

    /**
     * @Route("/test/doctrine/delete", name="test_doctrine_delete")
     */
    public function delete() {
        $repository = $this->getDoctrine()->getRepository(Project::class);
        $project = $repository->find(3);

        
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();

        return $this->render('test_doctrine/index.html.twig');
    }
}
