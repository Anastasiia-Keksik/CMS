<?php

namespace App\DataFixtures;

use App\Entity\Account;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){

        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function($i){
            $user = new Account();
            $user->setEmail(sprintf('sting%d@sting.cms', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);
            $user->setUsername($this->faker->userName);
            $user->setCreatedAt($this->faker->dateTime('now'));

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                "sting123"
            ));
            return $user;
        });

        $manager->flush();
    }
}
