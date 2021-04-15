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
 * Controller used to manage the user dashboard, such as profile, watchlist and so on.
 *
 * @Route("/user/dashboard", name="app_user_dashboard_")
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class DashboardController extends AbstractController
{
    /**
     * @todo Get the current user and create an edit form
     * @Route("/profile", name="profile", methods={"GET", "POST"})
     */
    public function profile(): Response
    {
        return $this->render('user/dashboard/profile.html.twig');
    }

    /**
     * @todo Get all Movies related to the user (business logic is not yet implemented)
     * @Route("/watchlist", name="watchlist", methods={"GET"})
     */
    public function watchlist(): Response
    {
        return $this->render('user/dashboard/watchlist.html.twig');
    }
}
