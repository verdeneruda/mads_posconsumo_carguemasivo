<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\ActionBundle\Tests\Fixtures\TestBundle\Action;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class RouteAnnotationAction
{
    /**
     * @Route("/annotation")
     */
    public function __invoke()
    {
        return new Response('Hey, ho, let\'s go!');
    }
}
