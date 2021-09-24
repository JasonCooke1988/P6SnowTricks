<?php


namespace App\DataFixtures;


use App\Entity\TrickImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TrickImageFixtures extends Fixture implements DependentFixtureInterface
{
    

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $args = [
            [
                'path' => 'mute.jpg',
                'trick' => TrickFixtures::MUTE_TRICK_REFERENCE
            ],
            [
                'path' => 'mute2.jpg',
                'trick' => TrickFixtures::MUTE_TRICK_REFERENCE
            ],
            [
                'path' => 'tailgrab.jpg',
                'trick' => TrickFixtures::TAIL_GRAB_TRICK_REFERENCE
            ],
            [
                'path' => 'mute3.jpg',
                'trick' => TrickFixtures::MUTE_TRICK_REFERENCE
            ],
            [
                'path' => 'nosegrab.jpg',
                'trick' => TrickFixtures::NOSE_GRAB_TRICK_REFERENCE
            ],
            [
                'path' => '180.jpg',
                'trick' => TrickFixtures::ROTATION_180_TRICK_REFERENCE
            ],
            [
                'path' => '360.jpg',
                'trick' => TrickFixtures::ROTATION_360_TRICK_REFERENCE
            ],
            [
                'path' => 'japan.jpg',
                'trick' => TrickFixtures::JAPAN_TRICK_REFERENCE
            ],
            [
                'path' => '540.jpg',
                'trick' => TrickFixtures::ROTATION_540_TRICK_REFERENCE
            ],
            [
                'path' => '720.jpg',
                'trick' => TrickFixtures::ROTATION_720_TRICK_REFERENCE
            ],
            [
                'path' => 'flip.jpg',
                'trick' => TrickFixtures::FLIP_TRICK_REFERENCE
            ],
            [
                'path' => 'frontflip.jpg',
                'trick' => TrickFixtures::FRONT_FLIP_TRICK_REFERENCE
            ],
            [
                'path' => 'seatbelt.jpg',
                'trick' => TrickFixtures::SEAT_BELT_TRICK_REFERENCE
            ],
            [
                'path' => 'backflip.jpg',
                'trick' => TrickFixtures::BACK_FLIP_TRICK_REFERENCE
            ],
            [
                'path' => 'indy.jpg',
                'trick' => TrickFixtures::INDY_TRICK_REFERENCE
            ],
            [
                'path' => 'sad.jpg',
                'trick' => TrickFixtures::SAD_TRICK_REFERENCE
            ],
            [
                'path' => 'truckdriver.jpg',
                'trick' => TrickFixtures::TRUCK_DRIVER_TRICK_REFERENCE
            ],
            [
                'path' => 'stalefish.jpg',
                'trick' => TrickFixtures::STALEFISH_TRICK_REFERENCE
            ]
        ];



        foreach($args as $elt) {
            $trickImage = new TrickImage();
            $trickImage->setPath($elt['path']);
            $trickImage->setCreatedAt(new \DateTime());
            $trickImage->setTrick($this->getReference($elt['trick']));
            $manager->persist($trickImage);
        }

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            TrickFixtures::class
        ];
    }
}