<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public const JASON_USER_REFERENCE = "jason-user";
    public const STELLA_USER_REFERENCE = "stella-user";

    public $args;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
     $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->args = [
            [
                'firstName' => 'Jason',
                'lastName' => 'Cooke',
                'email' => 'jason.cooke@hotmail.fr',
                'userName' => 'Jason',
                'password' => 'testing',
                'ref' => self::JASON_USER_REFERENCE
            ],
            [
                'firstName' => 'Stella',
                'lastName' => 'Cooke',
                'email' => 'jasonpcooke88@gmail.com',
                'userName' => 'Stella',
                'password' => 'testing',
                'ref' => self::STELLA_USER_REFERENCE
            ]
        ];

        foreach($this->args as $elt) {

            $user = new User();

            $user->setFirstName($elt['firstName']);
            $user->setLastName($elt['lastName']);
            $user->setEmail($elt['email']);
            $user->setUsername($elt['userName']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $elt['password']
            ));
            $user->setCreatedAt(new \DateTime());
            $this->addReference($elt['ref'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
