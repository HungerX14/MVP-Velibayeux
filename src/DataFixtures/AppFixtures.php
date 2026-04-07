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
        ['name' => 'Gare de Bayeux',               'lat' => 49.2768, 'lng' => -0.6997, 'slots' => 15, 'available' => 8],  // 🟢 bien approvisionné
        ['name' => 'Cathédrale Notre-Dame',         'lat' => 49.2744, 'lng' => -0.7034, 'slots' => 12, 'available' => 6],  // 🟢 bien approvisionné
        ['name' => 'Musée de la Tapisserie',        'lat' => 49.2752, 'lng' => -0.7009, 'slots' => 10, 'available' => 0],  // 🔴 vide
        ['name' => 'Place Charles de Gaulle',       'lat' => 49.2738, 'lng' => -0.7051, 'slots' => 14, 'available' => 2],  // 🟠 presque vide
        ['name' => 'Hôpital de Bayeux',             'lat' => 49.2790, 'lng' => -0.6960, 'slots' => 20, 'available' => 0],  // 🔴 vide
        ['name' => 'Musée Mémorial 1944',           'lat' => 49.2720, 'lng' => -0.6980, 'slots' => 10, 'available' => 5],  // 🟢 bien approvisionné
        ['name' => 'Place Saint-Patrice',           'lat' => 49.2760, 'lng' => -0.7070, 'slots' => 16, 'available' => 1],  // 🟠 presque vide
        ['name' => 'Lycée Alain Chartier',          'lat' => 49.2800, 'lng' => -0.7020, 'slots' => 12, 'available' => 0],  // 🔴 vide
        ['name' => 'Stade Municipal',               'lat' => 49.2820, 'lng' => -0.6950, 'slots' => 18, 'available' => 9],  // 🟢 bien approvisionné
        ['name' => 'Centre Commercial Intermarché', 'lat' => 49.2710, 'lng' => -0.7100, 'slots' => 14, 'available' => 3],  // 🟠 presque vide
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
