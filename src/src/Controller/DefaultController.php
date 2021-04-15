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

namespace App\Controller;

use App\Entity\Movie;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used to manage default contents such as home, help center and contact pages.
 *
 * @todo Inject MovieRepository
 * @Route(name="app_default_")
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class DefaultController extends AbstractController
{
    /** @var int Movies available on the homepage */
    public const NB_DISPLAYED_MOVIES = 6;

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $movieRepository = $this->getDoctrine()->getRepository(Movie::class);

        return $this->render(
            'default/index.html.twig',
            ['movies' => $movieRepository->findBy([], ['released' => 'ASC'], self::NB_DISPLAYED_MOVIES)]
        );
    }

    /**
     * @todo Send email with the Symfony Mailer component
     * @Route("/contact", name="contact", methods={"GET", "POST"})
     */
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', $form->getData()['name'].' your email has been sent.');

            return $this->redirectToRoute('app_default_index');
        }

        return $this->render('default/contact.html.twig', ['form' => $form->createView()]);
    }
}
