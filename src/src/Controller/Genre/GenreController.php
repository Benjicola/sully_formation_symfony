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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used to manage everything related to the Genre Entity.
 *
 * @Route("/genre", name="app_genre_")
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class GenreController extends AbstractController
{
    /**
     * @Route(name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $genreRepository = $this->getDoctrine()->getRepository(Genre::class);

        return $this->render(
            'genre/index.html.twig',
            ['genres' => $genreRepository->findBy([], ['name' => 'ASC'])]
        );
    }
}
