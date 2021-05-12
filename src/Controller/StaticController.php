<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class StaticController extends AbstractController {
    /**
     * @Route("/", name="home")
     */
    public function home(): Response {
        return $this->render('static/home.html.twig');
    }

    /**
     * @Route("/cv", name="cv")
     * 
     * @IsGranted("ROLE_USER")
     */
    public function cv(): Response {
        return $this->render('static/cv.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(): Response {
        return $this->render('static/about.html.twig');
    }
}
