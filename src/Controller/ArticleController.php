<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController {
    /**
     * @Route("/article/{id}", name="article")
     */
    public function afficherUnArticle($id): Response {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $article = $repository->find($id);

        if (empty($article)) throw new NotFoundHttpException();

        return $this->render('article/index.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/tous-les-articles", name="tous_les_article")
     */
    public function afficherTousLesArticle(): Response {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findAll();

        return $this->render('article/tous-les-articles.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/creer-un-article", name="creer_article")
     */
    public function creerArticle(Request $r): Response {

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('article/creer-article.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirect('/article/' . $article->getId());
        }
    }

    /**
     * @Route("/modifier-un-article/{id}", name="modifier_article")
     */
    public function modifierArticle($id, Request $r): Response {

        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id);

        if (empty($article)) throw new NotFoundHttpException();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('article/modifier-article.html.twig', [
                'form' => $form->createView(),
                'id' => $article->getId()
            ]);
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirect('/article/' . $article->getId());
        }
    }
}
