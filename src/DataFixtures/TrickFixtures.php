<?php


namespace App\DataFixtures;


use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    public const MUTE_TRICK_REFERENCE = "mute-trick";
    public const SAD_TRICK_REFERENCE = "sad-trick";
    public const INDY_TRICK_REFERENCE = "indy-trick";
    public const STALEFISH_TRICK_REFERENCE = "stalefish-trick";
    public const ROTATION_180_TRICK_REFERENCE = "rotation-180-trick";
    public const ROTATION_360_TRICK_REFERENCE = "rotation-360-trick";
    public const ROTATION_540_TRICK_REFERENCE = "rotation-540-trick";
    public const ROTATION_720_TRICK_REFERENCE = "rotation-720-trick";
    public const FLIP_TRICK_REFERENCE = "flip-trick";
    public const FRONT_FLIP_TRICK_REFERENCE = "front-flip-trick";
    public const BACK_FLIP_TRICK_REFERENCE = "back-flip-trick";
    public const TAIL_GRAB_TRICK_REFERENCE = "tail-grab-trick";
    public const NOSE_GRAB_TRICK_REFERENCE = 'nose-grab-trick';
    public const JAPAN_TRICK_REFERENCE = "japan-grab-trick";
    public const SEAT_BELT_TRICK_REFERENCE = "seat-belt-grab-trick";
    public const TRUCK_DRIVER_TRICK_REFERENCE = "truck-driver-grab-trick";

    private object $jason;
    private object $stella;
    private object $oldSchool;
    private object $grabs;
    private object $rotations;
    private object $rotationsDesaxees;
    private object $flips;
    private object $slides;

    public $args;



    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        /*
         * The references to be inserted in the Trick object's user_id
         */
        $this->jason = $this->getReference(UserFixtures::JASON_USER_REFERENCE);
        $this->stella = $this->getReference(UserFixtures::STELLA_USER_REFERENCE);

        /*
         * The references to be inserted in the Trick object's group_id
         */
        $this->grabs = $this->getReference(GroupFixtures::GRABS_GROUP_REFERENCE);
        $this->rotations = $this->getReference(GroupFixtures::ROTATIONS_GROUP_REFERENCE);
        $this->flips = $this->getReference(GroupFixtures::FLIPS_GROUP_REFERENCE);
        $this->rotationsDesaxees = $this->getReference(GroupFixtures::ROATIONS_DESAXEES_GROUP_REFERENCE);
        $this->slides = $this->getReference(GroupFixtures::SLIDES_GROUP_REFERENCE);
        $this->oldSchool = $this->getReference(GroupFixtures::OLD_SCHOOL_GROUP_REFERENCE);

        $this->args =  [
            [
                'name' => 'Mute',
                'description' => 'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant',
                'group' => $this->grabs,
                'user' => $this->jason,
                'ref' => self::MUTE_TRICK_REFERENCE
            ],
            [
                'name' => 'Sad',
                'description' => 'Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant',
                'group' => $this->grabs,
                'user' => $this->stella,
                'ref' => self::SAD_TRICK_REFERENCE
            ],
            [
                'name' => 'Indy',
                'description' => 'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière',
                'group' => $this->grabs,
                'user' => $this->jason,
                'ref' => self::INDY_TRICK_REFERENCE
            ],
            [
                'name' => 'Stalefish',
                'description' => 'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière',
                'group' => $this->grabs,
                'user' => $this->jason,
                'ref' => self::STALEFISH_TRICK_REFERENCE
            ],
            [
                'name' => 'Tail grab',
                'description' => 'Saisie de la partie arrière de la planche, avec la main arrière',
                'group' => $this->grabs,
                'user' => $this->jason,
                'ref' => self::TAIL_GRAB_TRICK_REFERENCE
            ],
            [
                'name' => 'Nose grab',
                'description' => 'Saisie de la partie avant de la planche, avec la main avant',
                'group' => $this->grabs,
                'user' => $this->stella,
                'ref' => self::NOSE_GRAB_TRICK_REFERENCE
            ],
            [
                'name' => 'Japan',
                'description' => 'Saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside',
                'group' => $this->grabs,
                'user' => $this->stella,
                'ref' => self::JAPAN_TRICK_REFERENCE
            ],
            [
                'name' => 'Seat belt',
                'description' => 'Saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside',
                'group' => $this->grabs,
                'user' => $this->stella,
                'ref' => self::SEAT_BELT_TRICK_REFERENCE
            ],
            [
                'name' => 'Truck driver',
                'description' => 'Saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)',
                'group' => $this->grabs,
                'user' => $this->stella,
                'ref' => self::TRUCK_DRIVER_TRICK_REFERENCE
            ],
            [
                'name' => 'Rotation 180°',
                'description' => 'Un 180 désigne un demi-tour, soit 180 degrés d\'angle',
                'group' => $this->rotations,
                'user' => $this->stella,
                'ref' => self::ROTATION_180_TRICK_REFERENCE
            ],
            [
                'name' => 'Rotation 360°',
                'description' => '360, trois six pour un tour complet',
                'group' => $this->rotations,
                'user' => $this->stella,
                'ref' => self::ROTATION_360_TRICK_REFERENCE
            ],
            [
                'name' => 'Rotation 540°',
                'description' => '540, cinq quatre pour un tour et demi',
                'group' => $this->rotations,
                'user' => $this->stella,
                'ref' => self::ROTATION_540_TRICK_REFERENCE
            ],
            [
                'name' => 'Rotation 720°',
                'description' => '720, sept deux pour deux tours complets',
                'group' => $this->rotations,
                'user' => $this->stella,
                'ref' => self::ROTATION_720_TRICK_REFERENCE
            ],
            [
                'name' => 'Flip',
                'description' => 'Un flip est une rotation verticale.',
                'group' => $this->flips,
                'user' => $this->jason,
                'ref' => self::FLIP_TRICK_REFERENCE
            ],
            [
                'name' => 'Front flip',
                'description' => 'On distingue les front flips, rotations en avant.',
                'group' => $this->flips,
                'user' => $this->jason,
                'ref' => self::FRONT_FLIP_TRICK_REFERENCE
            ],
            [
                'name' => 'Back flip',
                'description' => 'On distingue les back flips, rotations en arrière.',
                'group' => $this->flips,
                'user' => $this->stella,
                'ref' => self::BACK_FLIP_TRICK_REFERENCE
            ],
        ];

        foreach($this->args as $elt) {
            $trick = new Trick();
            $trick->setName($elt['name']);
            $trick->setDescription($elt['description']);
            $trick->setCreatedAt(new \DateTime());
            $trick->setUser($elt['user']);
            $trick->setGroup($elt['group']);
            $this->addReference($elt['ref'], $trick);
            $manager->persist($trick);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          UserFixtures::class,
          GroupFixtures::class
        ];
    }
}