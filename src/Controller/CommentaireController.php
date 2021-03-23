<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireController extends AbstractController {
    /**
     * @Route("/commentaire/creer/{article_id}", name="creer_commentaire")
     */
    public function index($article_id, Request $request): Response {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $article = $repository->find($article_id);

        if (empty($article)) throw new NotFoundHttpException();

        $commentaire = new Commentaire();

        $formCommentaire = $this->createForm(CommentaireType::class, $commentaire);
        $formCommentaire->handleRequest($request);

        if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {

            $commentaire->setDate(new DateTime());
            $commentaire->setArticle($article);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();
        }

        return $this->redirect('/article/' . $article->getId());
    }
}
