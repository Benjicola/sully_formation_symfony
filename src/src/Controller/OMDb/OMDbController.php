<?php

namespace App\Controller\OMDb;

use App\Consumer\OMDb\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OMDbController
 * @package App\Controller\OMDb
 */
class OMDbController extends AbstractController
{
    /**
     * @Route("/movie/title/{title}", name="index", methods={"GET"})
     */
    public function index(Client $client, string $title): Response
    {
        $result = $client->requestByTitle($title);

        return new Response($result['Title']);
    }

}
