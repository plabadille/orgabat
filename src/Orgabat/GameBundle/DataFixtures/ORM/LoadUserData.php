<?php

namespace Orgabat\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $discriminator->setClass('Orgabat\GameBundle\Entity\Apprentice');

        $userManager = $this->container->get('pugx_user_manager');

        // First Name, Last Name, Birth Date, Email, Section
        $userData = [
            ['Jacques', 'Dupont', '01012001', 'jacques.dupont@mail.com', 'TEST 1'],
            ['Jean', 'Duval', '01012001', 'jean.duval@mail.fr', 'TEST 1'],
            ['Yves', 'Boulanger', '01012001', 'yves.boulanger@autremail.net', 'TEST 1'],
            ['Lucy', 'Ewing', '17091998', 'venenatis@temporbibendum.co.uk', 'TEST 2'],
            ['Mercedes', 'Schneider', '14011993', 'tellus.Nunc@molestiesodalesMauris.net', 'TEST 2'],
        ];

        // First Name, Last Name, Password, Email, Section(s)
        $trainerData = [
            ['Prof', 'Test', '12345678', 'prof.test@mail.com', [ 'TEST 1', 'TEST 2']],
            ['Autre', 'Prof', '12345678', 'autre.prof@mail.fr', [ 'TEST 2']],
        ];

        // First Name, Last Name, Username, Email, Password
        // /!\ After app setup, admin password has to be change in app /!\
        $adminData = [
            ['btp', 'admin', 'btp_admin', '12345678', 'btp@example.com'],
            ['deliberata', 'admin', 'deliberata_admin', '12345678', 'deliberata@example.com'],
        ];


        foreach ($userData as $line) {

            $apprentice = $userManager->createUser();
            $apprentice->setFirstName($line[0]);
            $apprentice->setLastName($line[1]);
            $apprentice->setUsername($line[0].' '.$line[1]);
            $apprentice->setBirthDate($line[2]);
            $apprentice->setPlainPassword($line[2]);
            $apprentice->setEmail($line[3]);

            $section = $manager
                ->getRepository('OrgabatGameBundle:Section')
                ->findOneByName($line[4])
            ;

            $apprentice->setSection($section);
            $apprentice->setEnabled(true);
            $apprentice->addRole('ROLE_APPRENTICE');
            $userManager->updateUser($apprentice, false);
        }

        $discriminator->setClass('Orgabat\GameBundle\Entity\Trainer');

        foreach ($trainerData as $line) {

            $trainer = $userManager->createUser();
            $trainer->setFirstName($line[0]);
            $trainer->setLastName($line[1]);
            $trainer->setUsername($line[0].' '.$line[1]);
            $trainer->setPlainPassword($line[2]);
            $trainer->setEmail($line[3]);

            foreach( $line[4] as $section) {
                $result = $manager
                    ->getRepository('OrgabatGameBundle:Section')
                    ->findOneByName($section)
                ;
                $trainer->addSection($result);
            }
            $trainer->setEnabled(true);
            $trainer->addRole('ROLE_TRAINER');
            $userManager->updateUser($trainer, false);
        }

        $discriminator->setClass('Orgabat\GameBundle\Entity\Admin');

        foreach ($adminData as $line) {

            $admin = $userManager->createUser();
            $admin->setFirstName($line[0]);
            $admin->setLastName($line[1]);
            $admin->setUsername($line[2]);
            $admin->setPlainPassword($line[3]);
            $admin->setEmail($line[4]);

            $admin->setEnabled(true);
            $admin->addRole('ROLE_ADMIN');
            $userManager->updateUser($admin, false);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
