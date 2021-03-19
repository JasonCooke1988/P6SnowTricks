<?php


namespace App\DataFixtures;


use App\Entity\Group;
use Doctrine\Persistence\ObjectManager;

class GroupFixtures extends \Doctrine\Bundle\FixturesBundle\Fixture
{

    public const GRABS_GROUP_REFERENCE = "grabs-group";
    public const ROTATIONS_GROUP_REFERENCE = "rotations-group";
    public const FLIPS_GROUP_REFERENCE = "flips-group";
    public const ROATIONS_DESAXEES_GROUP_REFERENCE = "rotations-desaxees-group";
    public const SLIDES_GROUP_REFERENCE = "slides-group";
    public const OLD_SCHOOL_GROUP_REFERENCE = "old-school-group";

    public $args;

    public function __construct()
    {
        $this->args = [
        [
            'name' => "Les grabs",
            'ref' => self::GRABS_GROUP_REFERENCE
        ],
        [
            'name' => "Les rotations",
            'ref' => self::ROTATIONS_GROUP_REFERENCE
        ],
        [
            'name' => "Les flips",
            'ref' => self::FLIPS_GROUP_REFERENCE
        ],
        [
            'name' => "Les rotations désaxées",
            'ref' => self::ROATIONS_DESAXEES_GROUP_REFERENCE
        ],
        [
            'name' => "Les slides",
            'ref' => self::SLIDES_GROUP_REFERENCE
        ],
        [
            'name' => "Old school",
            'ref' => self::OLD_SCHOOL_GROUP_REFERENCE
        ],
    ];
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        foreach($this->args as $fixture) {
            $group = new Group();
            $group->setName($fixture['name']);
            $group->setCreatedAt(new \DateTime());
            $this->addReference($fixture['ref'], $group);
            $manager->persist($group);
        }

        $manager->flush();

    }
}