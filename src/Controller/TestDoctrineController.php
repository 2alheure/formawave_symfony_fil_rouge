<?php

namespace App\Controller;

use App\Entity\Project;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TestDoctrineController extends AbstractController {
    /**
     * @Route("/test/doctrine/create", name="test_doctrine_create")
     */
    public function create(): Response {

        $project = new Project();

        $form = $this->createFormBuilder($project)
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('description', TextareaType::class)
            ->add('date', DateType::class)
            ->add('url', UrlType::class)
            ->add('image', UrlType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Créer un projet'
            ])
            ->getForm();

        return $this->render('test_doctrine/index.html.twig', [
            'form' => $form->createView()
        ]);
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
    public function update($id) {
        $repository = $this->getDoctrine()->getRepository(Project::class);

        $project = $repository->find($id);

        if (empty($project)) throw new NotFoundHttpException();

        $project->setName('Updated name 2');

        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        // Doctrine exécute les requêtes SQL 
        // Qui correspondent à l'opération souhaitée
        // Ici : UPDATE ...
        $em->flush();

        return $this->render('test_doctrine/index.html.twig');
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
