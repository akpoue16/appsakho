<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
  /**
   * @Route("/login", name="login")
   */
  public function index(AuthenticationUtils $authenticationUtils): Response
  {
    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    // if (true === $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
      
    // }

    return $this->render('login/index.html.twig', [
      'last_username' => $lastUsername,
      'error'         => $error,
    ]);
  }

  /**
   * @Route("/logout", name="logout", methods={"GET"})
   */
  public function logout()
  {
  }
}
