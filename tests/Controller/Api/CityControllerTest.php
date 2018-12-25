<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.12.18
 * Time: 21:40
 */

namespace App\Tests\Controller\Api;

use App\Controller\Api\CityController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CityControllerTest extends WebTestCase
{
    public function testListAction()
    {
        $client = static::createClient();
        $request = $client->request('GET', '/api/city');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
