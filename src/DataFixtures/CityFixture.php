<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CityFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cityList = [
            'Санкт-Петербург',
            'Уфа',
            'Нижний Новгород',
            'Владимир',
            'Кострома',
            'Екатеринбург',
            'Ковров',
            'Воронеж',
            'Самара',
            'Астрахань'
        ];

        foreach ($cityList as $item) {
            $duration = rand(2,10);
            $city = new City();
            $city->setName($item);
            $city->setTripDuration($duration);
            $manager->persist($city);
        }

        $manager->flush();
    }
}
