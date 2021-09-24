<?php


namespace App\DataFixtures;


use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    private object $jason;
    private object $stella;

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $this->jason = $this->getReference(UserFixtures::JASON_USER_REFERENCE);
        $this->stella = $this->getReference(UserFixtures::STELLA_USER_REFERENCE);

        $args = [
            [
                'trick' => TrickFixtures::MUTE_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::MUTE_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::TAIL_GRAB_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::MUTE_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::NOSE_GRAB_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::ROTATION_180_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::ROTATION_360_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::JAPAN_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::ROTATION_540_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::ROTATION_720_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::FLIP_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::FRONT_FLIP_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::SEAT_BELT_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::BACK_FLIP_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::INDY_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::SAD_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::TRUCK_DRIVER_TRICK_REFERENCE
            ],
            [
                'trick' => TrickFixtures::STALEFISH_TRICK_REFERENCE
            ]
        ];


        foreach ($args as $elt) {
            for ($i = 0; $i <= 30; $i++) {
                $comment = new Comment();
                $comment->setTrick($this->getReference($elt['trick']));
                $comment->setUser($i % 2 == 0 ? $this->jason : $this->stella);
                $comment->setContent("Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
                Ab asperiores dignissimos explicabo id.");
                $comment->setCreatedAt(new \DateTime());
                $manager->persist($comment);
            }
        }
        $manager->flush();
    }


    public function getDependencies()
    {
        return [
            TrickFixtures::class,
            UserFixtures::class
        ];
    }
}