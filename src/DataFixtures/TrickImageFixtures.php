<?php


namespace App\DataFixtures;


use App\Entity\TrickImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TrickImageFixtures extends Fixture implements DependentFixtureInterface
{

    public $args;

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $this->args = [
            [
                'path' => 'mute.jpg',
                'trick' => TrickFixtures::MUTE_TRICK_REFERENCE
            ],
            [
                'path' => 'mute2.jpg',
                'trick' => TrickFixtures::MUTE_TRICK_REFERENCE
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
                'path' => 'stalefish.jpg',
                'trick' => TrickFixtures::STALEFISH_TRICK_REFERENCE
            ]
        ];



        foreach($this->args as $elt) {
            $trickImage = new TrickImage();
            $trickImage->setPath($elt['path']);
            $trickImage->setCreatedAt(new \DateTime());
            $trickImage->setTrick($this->getReference($elt['trick']));
            $manager->persist($trickImage);
        }

        $manager->flush();
    }


    public function getDependencies()
    {
        return [
            TrickFixtures::class
        ];
    }
}