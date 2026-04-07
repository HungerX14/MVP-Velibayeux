<?php

namespace App\DataFixtures;

use App\Entity\Bike;
use App\Entity\Station;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    // Stations réalistes à Bayeux (Normandie)
    private const STATIONS = [
        ['name' => 'Gare de Bayeux',               'lat' => 49.2768, 'lng' => -0.6997],
        ['name' => 'Cathédrale Notre-Dame',         'lat' => 49.2744, 'lng' => -0.7034],
        ['name' => 'Musée de la Tapisserie',        'lat' => 49.2752, 'lng' => -0.7009],
        ['name' => 'Place Charles de Gaulle',       'lat' => 49.2738, 'lng' => -0.7051],
        ['name' => 'Hôpital de Bayeux',             'lat' => 49.2790, 'lng' => -0.6960],
        ['name' => 'Musée Mémorial 1944',           'lat' => 49.2720, 'lng' => -0.6980],
        ['name' => 'Place Saint-Patrice',           'lat' => 49.2760, 'lng' => -0.7070],
        ['name' => 'Lycée Alain Chartier',          'lat' => 49.2800, 'lng' => -0.7020],
        ['name' => 'Stade Municipal',               'lat' => 49.2820, 'lng' => -0.6950],
        ['name' => 'Centre Commercial Intermarché', 'lat' => 49.2710, 'lng' => -0.7100],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::STATIONS as $stationData) {
            $totalSlots = rand(8, 20);
            $availableCount = rand(1, $totalSlots);

            $station = new Station();
            $station->setName($stationData['name']);
            $station->setLatitude($stationData['lat']);
            $station->setLongitude($stationData['lng']);
            $station->setTotalSlots($totalSlots);

            $manager->persist($station);

            // Créer les vélos
            for ($i = 0; $i < $totalSlots; $i++) {
                $bike = new Bike();
                $bike->setStation($station);
                $bike->setStatus($i < $availableCount ? 'available' : 'reserved');
                $manager->persist($bike);
            }
        }

        $manager->flush();
    }
}
