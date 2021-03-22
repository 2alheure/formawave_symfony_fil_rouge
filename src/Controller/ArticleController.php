<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
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
     * @Route("/gerer-articles", name="gerer_articles")
     */
    public function gererArticles(): Response {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findAll();

        return $this->render('article/gerer-articles.html.twig', [
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

            // Je vais déplacer le fichier uploadé

            // On récupère l'image
            $image = $form->get('image')->getData();
            // On définit le nom du fichier
            $fileName =  uniqid() . '.' . $image->guessExtension();

            try {
                // On déplace le fichier
                $image->move($this->getParameter('article_image_directory'), $fileName);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $article->setImage($fileName);

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

        $oldImage = $article->getImage();

        if (empty($article)) throw new NotFoundHttpException();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('article/modifier-article.html.twig', [
                'form' => $form->createView(),
                'oldImage' => $oldImage,
                'id' => $article->getId()
            ]);
        } else {

            // Je vais déplacer le fichier uploadé
            $image = $form->get('image')->getData();

            try {
                $image->move($this->getParameter('article_image_directory'), $oldImage);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $article->setImage($oldImage);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirect('/article/' . $article->getId());
        }
    }

    /**
     * @Route("/supprimer-un-article/{id}", name="supprimer_article")
     */
    public function supprimerArticle($id): Response {

        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id);

        if (empty($article)) throw new NotFoundHttpException();

        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('gerer_articles');
    }
}
