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

use App\Entity\Movie;
use App\Event\OnMovieDetailsAccess;
use App\Security\Voter\MpaaVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Controller used to show many things related to one selected movie.
 *
 * @Route("/movie", name="app_movie_")
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/details/{uuid}", name="details", methods={"GET"})
     */
    public function details(string $uuid, EventDispatcherInterface $eventDispatcher): Response
    {
        $movieRepository = $this->getDoctrine()->getManager()->getRepository(Movie::class);
        $movie = $movieRepository->findOneByUuid($uuid);

        if (false === $movie instanceof Movie) {
            throw $this->createNotFoundException();
        }

        $this->denyAccessUnlessGranted(MpaaVoter::MOVIE_VIEW, $movie);

        $eventDispatcher->dispatch(new OnMovieDetailsAccess($movie));

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @todo Add a Movie parameter to this route
     * @Route("/player", name="player", methods={"GET"})
     */
    public function player(): Response
    {
        return $this->render('movie/player.html.twig');
    }

    /**
     * @todo Add a Movie parameter to this route
     * @Route("/trailer", name="trailer", methods={"GET"})
     */
    public function trailer(): Response
    {
        return $this->render('movie/trailer.html.twig');
    }
}
