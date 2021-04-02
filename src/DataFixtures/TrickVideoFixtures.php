<?php


namespace App\DataFixtures;


use App\Entity\TrickVideo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TrickVideoFixtures extends Fixture implements DependentFixtureInterface
{

    public $args;
    public $tricksRef;

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $this->tricksRef = [
            TrickFixtures::MUTE_TRICK_REFERENCE,
            TrickFixtures::ROTATION_180_TRICK_REFERENCE,
            TrickFixtures::ROTATION_360_TRICK_REFERENCE,
            TrickFixtures::ROTATION_540_TRICK_REFERENCE,
            TrickFixtures::ROTATION_720_TRICK_REFERENCE,
            TrickFixtures::FLIP_TRICK_REFERENCE,
            TrickFixtures::FRONT_FLIP_TRICK_REFERENCE,
            TrickFixtures::BACK_FLIP_TRICK_REFERENCE,
            TrickFixtures::INDY_TRICK_REFERENCE,
            TrickFixtures::SAD_TRICK_REFERENCE,
            TrickFixtures::STALEFISH_TRICK_REFERENCE,
            TrickFixtures::TAIL_GRAB_TRICK_REFERENCE,
            TrickFixtures::NOSE_GRAB_TRICK_REFERENCE,
            TrickFixtures::JAPAN_TRICK_REFERENCE,
            TrickFixtures::SEAT_BELT_TRICK_REFERENCE,
            TrickFixtures::TRUCK_DRIVER_TRICK_REFERENCE,
        ];


        foreach ($this->tricksRef as $ref) {
            $trickVideo = new TrickVideo();
            $trickVideo->setEmbed('<iframe width="560" height="315" src="https://www.youtube.com/embed/SQyTWk7OxSI" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
            $trickVideo->setCreatedAt(new \DateTime());
            $trickVideo->setTrick($this->getReference($ref));

            $trickVideo2 = new TrickVideo();
            $trickVideo2->setEmbed('<iframe width="560" height="315" src="https://www.youtube.com/embed/qsd8uaex-Is" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
            $trickVideo2->setCreatedAt(new \DateTime());
            $trickVideo2->setTrick($this->getReference($ref));

            $trickVideo3 = new TrickVideo();
            $trickVideo3->setEmbed('<iframe width="560" height="315" src="https://www.youtube.com/embed/OsbpD8BN10k" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
            $trickVideo3->setCreatedAt(new \DateTime());
            $trickVideo3->setTrick($this->getReference($ref));

            $manager->persist($trickVideo);
            $manager->persist($trickVideo2);
            $manager->persist($trickVideo3);
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