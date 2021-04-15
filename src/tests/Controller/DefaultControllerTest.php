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

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use Generator;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group   functional
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role@gmail.com>
 */
class DefaultControllerTest extends AbstractWebTestCase
{
    /**
     * @dataProvider providePublicUrls
     */
    public function testPublicUrlsAreSuccessful(string $url): void
    {
        self::$webClient->request(Request::METHOD_GET, $url);
        self::assertResponseIsSuccessful();
    }

    /**
     * Provide some public urls to test.
     */
    public function providePublicUrls(): Generator
    {
        yield ['/'];
        yield ['/movie/chart/top-rated'];
        yield ['/genre'];
        yield ['/movie/chart/latest'];
        yield ['/contact'];
        yield ['/login'];
        yield ['/recover'];
        yield ['/register'];
    }
}
