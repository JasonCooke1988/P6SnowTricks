<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
     $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setFirstName('Jason');
        $user->setLastName('Cooke');
        $user->setEmail('jason.cooke@hotmail.fr');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'test'
        ));
        $user->setCreatedAt(new \DateTime());


        $user2 = new User();

        $user2->setFirstName('Stella');
        $user2->setLastName('Cooke');
        $user2->setEmail('jasonpcooke88@gmail.com');
        $user2->setPassword($this->passwordEncoder->encodePassword(
            $user2,
            'test2'
        ));
        $user2->setCreatedAt(new \DateTime());

        $manager->persist($user);
        $manager->persist($user2);

        $manager->flush();
    }
}
