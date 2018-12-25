<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.12.18
 * Time: 21:44
 */

namespace App\Tests\Controller\Api;

use App\Controller\Api\CourierController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CourierControllerTest extends WebTestCase
{

    public function testListAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/courier');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
