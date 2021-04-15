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

use App\Entity\Genre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used to show Movie charts.
 *
 * @todo Inject GenreRepository
 * @Route("/movie/chart", name="app_movie_chart_")
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class ChartController extends AbstractController
{
    /**
     * @todo Get all Movies sort by $globalRatingValue attr
     * @todo Add a paginator with filters
     * @Route("/top-rated", name="top_rated", methods={"GET"})
     */
    public function topRated(): Response
    {
        $genreRepository = $this->getDoctrine()->getRepository(Genre::class);

        return $this->render(
            'movie/chart/top_rated.html.twig',
            ['genres' => $genreRepository->findAllGenreNames()]
        );
    }

    /**
     * @todo Get all Movies sort by $released date attr
     * @todo Add a paginator with filters
     * @Route("/latest", name="latest", methods={"GET"})
     */
    public function latest(): Response
    {
        $genreRepository = $this->getDoctrine()->getRepository(Genre::class);

        return $this->render(
            'movie/chart/latest.html.twig',
            ['genres' => $genreRepository->findAllGenreNames()]
        );
    }
}
