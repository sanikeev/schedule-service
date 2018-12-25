<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.12.18
 * Time: 21:47
 */

namespace App\Tests\Controller\Api;

use App\Controller\Api\ScheduleController;
use App\Entity\City;
use App\Entity\Courier;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScheduleControllerTest extends WebTestCase
{

    public function testListAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/schedule');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', '/api/schedule', [
            'page' => 3,
            'start' => '2017-12-12',
            'end' => '2018-01-01',
        ]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testArrivalAction()
    {
        self::bootKernel();

        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();

        /** @var ObjectManager $em */
        $em = $container->get('doctrine.orm.entity_manager');
        $list = $em->getRepository(City::class)->findAll();
        $trueParams = [
            'city' => $list[0]->getId(),
            'date' => (new \DateTime())->format('Y-m-d'),
        ];

        $client = static::createClient();
        $client->request('POST', '/api/schedule/arrival', [], [], [], json_encode($trueParams));

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $checkDate = new \DateTime('+' . ceil($list[0]->getTripDuration() / 2) . 'days');

        $this->assertEquals($checkDate->format('Y-m-d'), json_decode($response->getContent(), true)['arrival_date']);
    }

    public function testCheckAction()
    {
        self::bootKernel();

        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();

        /** @var ObjectManager $em */
        $em = $container->get('doctrine.orm.entity_manager');
        $list = $em->getRepository(Courier::class)->findAll();

        $trueParams = [
            'courier' => $list[0]->getId(),
            'date' => (new \DateTime('+1month'))->format('Y-m-d')
        ];
        $falseParams = [
            'courier' => $list[0]->getId(),
            'date' => (new \DateTime('-1month'))->format('Y-m-d')
        ];
        $client = static::createClient();
        $client->request('POST', '/api/schedule/check', [], [], [], json_encode($trueParams));

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertFalse(json_decode($response->getContent(), true)['check']);

        $client->request('POST', '/api/schedule/check', [], [], [], json_encode($falseParams));

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertTrue(json_decode($response->getContent(), true)['check']);
    }

    public function testCreateAction()
    {
        self::bootKernel();

        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();

        /** @var ObjectManager $em */
        $em = $container->get('doctrine.orm.entity_manager');
        /** @var Connection $conn */
        $conn = $container->get('database_connection');
        $conn->setAutoCommit(false);
        $conn->beginTransaction();

        $listCourier = $em->getRepository(Courier::class)->findAll();
        $listCity = $em->getRepository(City::class)->findAll();
        $date = new \DateTime('+1month');
        $params = [
            'started_at' => $date->format('Y-m-d'),
            'city' => $listCity[0]->getId(),
            'courier' => $listCourier[0]->getId()
        ];

        $client = static::createClient();
        $client->request('POST', '/api/schedule', [], [], [], json_encode($params));

        $response = $client->getResponse();

        $this->assertEquals(201, $response->getStatusCode());

        $conn->rollBack();
    }
}
