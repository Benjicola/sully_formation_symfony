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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used to manage everything related to the user cart.
 *
 * @Route("/user/cart", name="app_user_cart_")
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class CartController extends AbstractController
{
    /**
     * @todo Add a complete order workflow with sessions, events, storage, payment services...
     * @Route(name="index", methods={"GET", "POST"})
     */
    public function index(): Response
    {
        return $this->render('user/cart/index.html.twig');
    }
}
