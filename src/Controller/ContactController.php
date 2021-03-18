<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as MimeEmail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactController extends AbstractController {
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailerInterface $mailer): Response {

        // Je crée mon formulaire
        // Ou bien
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email()
                ]
            ])
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 255
                    ])
                ]
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            ->getForm();

        // Ou bien
        // $form = $this->createForm(ContactType::class);

        // Je donne la requête à mon formulaire
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            // Si mon formulaire n'a pas été soumis, 
            // Ou s'il n'est pas valide

            // J'affiche la vue du formulaire
            return $this->render('contact/form.html.twig', [
                'form' => $form->createView(),
            ]);
        } else {

            // On envoie notre mail

            // On récupère les infos de notre formulaire

            // Méthode 1 :
            // On récupère à partir de l'objet $form
            $data = $form->getData();
            // dd($data);

            // Méthode 2 : 
            // On récupère depuis $_POST
            // $data = $request->request->get('form');
            // dd($data);

            $text = 'Quelqu\'un vous a envoyé une demande de contact sur votre site. Cette personne s\'appelle ' . $data['nom'] . '.' . PHP_EOL . PHP_EOL
                . 'Voici son message : ' . PHP_EOL . PHP_EOL
                . $data['message'] . PHP_EOL . PHP_EOL
                . 'Si vous voulez lui répondre, veuillez écrire à l\'adresse : ' . $data['email'];


            $email = new MimeEmail();
            $email->from(Address::create('Portfolio de 2alheure <test.symfony@2alheure.fr>'))
                ->to('jojo77.juv@live.fr')
                ->replyTo($data['email'])
                ->subject('Tu as reçu un mail de contact !')
                ->html('<html><body>test')
                ->text($text);

            $mailer->send($email);

            return $this->redirectToRoute('contact');
        }
    }
}
