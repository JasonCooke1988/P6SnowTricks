<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private UserPasswordEncoderInterface $passwordEncoder;

    public const JASON_USER_REFERENCE = "jason-user";
    public const STELLA_USER_REFERENCE = "stella-user";

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
     $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $args = [
            [
                'firstName' => 'Jason',
                'lastName' => 'Cooke',
                'email' => 'jason.cooke@hotmail.fr',
                'userName' => 'Jason',
                'password' => 'testing',
                'photo' => 'man.png',
                'is_verified' => '1',
                'ref' => self::JASON_USER_REFERENCE
            ],
            [
                'firstName' => 'Stella',
                'lastName' => 'Cooke',
                'email' => 'jasonpcooke88@gmail.com',
                'userName' => 'Stella',
                'password' => 'testing',
                'photo' => 'female.png',
                'is_verified' => '1',
                'ref' => self::STELLA_USER_REFERENCE
            ]
        ];

        foreach($args as $elt) {

            $user = new User();

            $user->setFirstName($elt['firstName']);
            $user->setLastName($elt['lastName']);
            $user->setEmail($elt['email']);
            $user->setUsername($elt['userName']);
            $user->setPhoto($elt['photo']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $elt['password']
            ));
            $user->setIsVerified($elt['is_verified']);
            $user->setCreatedAt(new \DateTime());
            $this->addReference($elt['ref'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
