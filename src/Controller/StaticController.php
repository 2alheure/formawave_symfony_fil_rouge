<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticController extends AbstractController {
    /**
     * @Route("/", name="home")
     */
    public function home(): Response {
        return $this->render('static/home.html.twig');
    }
    
    /**
     * @Route("/cv", name="cv")
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
