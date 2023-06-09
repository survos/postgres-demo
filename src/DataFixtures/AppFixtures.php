<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\Person;
use App\Factory\CarFactory;
use App\Service\AppService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Core\Color;
use Faker\Factory;
use Random\Randomizer;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();
        $colors =  ['red','white','blue','green','brown','black','yellow'];
        $brands = ['toyota','vw','bmw', 'audi','chevy'];
        $countries = Countries::getCountryCodes();
        $languages = ['en','fr','es','ru','pt','de'];
        for ($i=0; $i<AppService::MAX_PERSONS; $i++) {
            $speaks = array_rand(array_flip($languages), rand(1, 3));
            if (!is_array($speaks)) {
                $speaks = [$speaks];
            }
            $person = (new Person())
                ->setName($faker->name)
                ->setInfo([
                    'languages' => $speaks,
                    'visited' => array_rand(array_flip($countries), rand(2, 4))
                ]);
            $manager->persist($person);
        }
        $manager->flush();
    }
}
