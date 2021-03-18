<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\Type\ProjectType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TestDoctrineController extends AbstractController {
    /**
     * @Route("/test/doctrine/create", name="test_doctrine_create")
     */
    public function create(Request $request): Response {

        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('test_doctrine/index.html.twig', [
                'formulaire' => $form->createView(),
            ]);
        } else {
            // On utilise les données de notre formulaire

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            return new Response('OK');
        }
    }

    /**
     * @Route("/test/doctrine/retrieve/{id}", name="test_doctrine_retrieve")
     */
    public function retrieve($id = null) {
        if (empty($id)) return $this->render('test_doctrine/index.html.twig');

        else {
            $repository = $this->getDoctrine()->getRepository(Project::class);
            $project = $repository->find($id);

            return $this->render('projet.html.twig', [
                'projet' => $project
            ]);
        }
    }

    /**
     * @Route("/test/doctrine/retrieve-all", name="test_doctrine_retrieve_all")
     */
    public function retrieveAll() {
        $repository = $this->getDoctrine()->getRepository(Project::class);
        $projects = $repository->findAll();

        return $this->render('projets.html.twig', [
            'projets' => $projects
        ]);
    }

    /**
     * @Route("/test/doctrine/update/{id}", name="test_doctrine_update")
     */
    public function update(Request $request, $id) {
        $repository = $this->getDoctrine()->getRepository(Project::class);
        $project = $repository->find($id);

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('test_doctrine/index.html.twig', [
                'formulaire' => $form->createView(),
            ]);
        } else {
            // On utilise les données de notre formulaire

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            return new Response('OK');
        }
    }

    /**
     * @Route("/test/doctrine/delete/{id}", name="test_doctrine_delete")
     */
    public function delete($id) {
        $repository = $this->getDoctrine()->getRepository(Project::class);
        $project = $repository->find($id);

        if (empty($project)) throw new NotFoundHttpException();

        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        // Doctrine exécute les requêtes SQL 
        // Qui correspondent à l'opération souhaitée
        // Ici : DELETE ...
        $em->flush();

        return $this->render('test_doctrine/index.html.twig');
    }
}
