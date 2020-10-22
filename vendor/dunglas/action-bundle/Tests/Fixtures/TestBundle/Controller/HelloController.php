<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\ActionBundle\Tests\Fixtures\TestBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * This legacy controller is here to ensure that old-school and bleeding edge
 * can be used on the same project.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class HelloController extends Controller
{
    /**
     * @Route("/legacy")
     */
    public function testAction()
    {
        return new Response($this->get('router')->generate('isolated'));
    }
}
