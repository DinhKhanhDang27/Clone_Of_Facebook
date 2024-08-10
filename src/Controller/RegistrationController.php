<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the value of the agreeTerms checkbox
            $agreeTerms = $form->get('agreeTerms')->getData();
            if (!$agreeTerms) {
                $this->addFlash('error', 'You must agree to the terms and conditions.');
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                ]);
            }

            // Encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Set default values for fields that cannot be null
            $user->setRoles(['ROLE_USER']);  // Set default role
            $user->setVerified(false);       // Set default verified status
            $user->setCreatedAt(new \DateTime());  // Set current datetime
            $user->setUpdatedAt(new \DateTime());  // Set current datetime

            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to profile page after registration
            return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
