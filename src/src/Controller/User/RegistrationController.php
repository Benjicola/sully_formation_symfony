<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\User;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route("/user", name="app_user_")
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class RegistrationController extends AbstractController
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /** @var GuardAuthenticatorHandler */
    private $handler;

    /** @var LoginFormAuthenticator */
    private $authenticator;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $handler,
        LoginFormAuthenticator $authenticator,
        EntityManagerInterface $entityManager
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->handler = $handler;
        $this->authenticator = $authenticator;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/registration", name="registration", methods={"GET", "POST"})
     */
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // do anything else you need here, like send an email

            return $this->handler->authenticateUserAndHandleSuccess($user, $request, $this->authenticator, 'main');
        }

        return $this->render('user/registration/registration.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
