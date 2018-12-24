<?php

namespace App\DataFixtures;

use App\Entity\Courier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CourierFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $nameList = [
            'Екатерина',
            'Ксения',
            'Клара',
            'Нина',
            'Ксения',
            'Богдан',
            'Юлия',
            'Семён',
            'Ксения',
            'Любава',
            'Алексей',
        ];

        foreach ($nameList as $name) {
            $courier = new Courier();
            $courier->setName($name);
            $manager->persist($courier);
        }

        $manager->flush();
    }
}
