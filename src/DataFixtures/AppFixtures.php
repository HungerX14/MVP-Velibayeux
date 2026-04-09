<?php

namespace App\DataFixtures;

use App\Entity\Bike;
use App\Entity\Station;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    // slots = total emplacements, available = vélos disponibles
    // Distribution volontairement variée pour la démonstration des filtres :
    //   🔴 vide      → available = 0
    //   🟠 presque   → available <= 25% du total
    //   🟢 bien dispo → available > 25% du total
    private const STATIONS = [
        ['name' => 'Mairie de Bayeux', 'lat' => 49.2766, 'lng' => -0.7024, 'slots' => 14, 'available' => 8],
        ['name' => 'Gare de Bayeux',   'lat' => 49.2698, 'lng' => -0.6975, 'slots' => 15, 'available' => 6],
        ['name' => 'Saint-Patrice',    'lat' => 49.28121531117451, 'lng' => -0.7070357273234474, 'slots' => 16, 'available' => 4],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::STATIONS as $data) {
            $station = new Station();
            $station->setName($data['name']);
            $station->setLatitude($data['lat']);
            $station->setLongitude($data['lng']);
            $station->setTotalSlots($data['slots']);

            $manager->persist($station);

            for ($i = 0; $i < $data['slots']; $i++) {
                $bike = new Bike();
                $bike->setStation($station);
                $bike->setStatus($i < $data['available'] ? 'available' : 'reserved');
                $manager->persist($bike);
            }
        }

        $manager->flush();
    }
}
