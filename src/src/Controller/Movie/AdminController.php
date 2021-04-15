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

namespace App\Controller\Movie;

use App\Consumer\MovieInformationProvider;
use App\Entity\Movie;
use App\Form\MovieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for Admin actions. Used to manage Movie CRUD methods with Admin rights.
 *
 * @todo Inject EntityManagerInterface
 * @Route("/admin/movie", name="app_admin_movie_")
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/import/{id}", requirements={"id": "tt[\d]+"})
     */
    public function import(string $id, MovieInformationProvider $movieInformationProvider): Response
    {
        dump($movieInformationProvider->findFromApi($id));

        return new Response('OK');
    }

    /**
     * @todo Add a paginator with filters and inject MovieRepository
     * @Route(name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $movieRepository = $this->getDoctrine()->getRepository(Movie::class);

        return $this->render(
            'movie/admin/index.html.twig',
            ['movies' => $movieRepository->findBy([], ['title' => 'ASC'])]
        );
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($movie);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_movie_index');
        }

        return $this->render(
            'movie/admin/new.html.twig',
            ['movie' => $movie, 'form' => $form->createView()]
        );
    }

    /**
     * @Route("/{uuid<^.{36}$>}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Movie $movie): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_movie_index');
        }

        return $this->render(
            'movie/admin/edit.html.twig',
            ['movie' => $movie, 'form' => $form->createView()]
        );
    }

    /**
     * @Route("/{uuid<^.{36}$>}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Movie $movie, ?Mailer $mailer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movie->getUuid()->toString(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($movie);
            $entityManager->flush();
            if ($mailer) {
//                $mailer->send();
            }
        }

        return $this->redirectToRoute('app_admin_movie_index');
    }

    public static function getSubscribedServices()
    {
        return parent::getSubscribedServices() + ['mailer' => 'mailer'];
    }
}
