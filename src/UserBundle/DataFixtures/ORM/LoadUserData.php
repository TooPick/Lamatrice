<?php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setUsername('user');
        $user->setPlainPassword('test');
        $user->setEmail('user@user.com');
        $user->setEnabled(true);

        $manager->persist($user);

        $userAdmin = new User();

        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('admin@admin.com');
        $userAdmin->setPlainPassword('test');
        $userAdmin->setEnabled(true);
        $userAdmin->addRole('ROLE_ADMIN');

        $manager->persist($userAdmin);

        $manager->flush();
    }
}