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

namespace App\Controller\Genre;

use App\Entity\Genre;
use App\Form\GenreType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for Admin actions. Used to manage Genre CRUD methods with Admin rights.
 *
 * @todo Inject EntityManagerInterface
 * @Route("/admin/genre", name="app_admin_genre_")
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class AdminController extends AbstractController
{
    /**
     * @todo Add a paginator with filters and inject MovieRepository
     * @Route(name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $genreRepository = $this->getDoctrine()->getRepository(Genre::class);

        return $this->render(
            'genre/admin/index.html.twig',
            ['genres' => $genreRepository->findBy([], ['name' => 'ASC'])]
        );
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($genre);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_genre_index');
        }

        return $this->render(
            'genre/admin/new.html.twig',
            ['genre' => $genre, 'form' => $form->createView()]
        );
    }

    /**
     * @Route("/{uuid<^.{36}$>}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Genre $genre): Response
    {
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_genre_index');
        }

        return $this->render(
            'genre/admin/edit.html.twig',
            ['genre' => $genre, 'form' => $form->createView()]
        );
    }

    /**
     * @Route("/{uuid<^.{36}$>}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Genre $genre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genre->getUuid()->toString(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($genre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_genre_index');
    }
}
