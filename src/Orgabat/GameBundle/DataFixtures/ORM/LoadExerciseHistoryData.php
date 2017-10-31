<?php

namespace Orgabat\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Orgabat\GameBundle\Entity\ExerciseHistory;

class LoadExerciseHistoryData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $data = [
            [150, 0, 0, 1, 'Jeu 1A', 'Jacques Dupont'],
            [100, 1, 1, 2, 'Jeu 1A', 'Jacques Dupont'],
            // [112, 4, 0, 1, 'Jeu 1F', 'Jacques Dupont'],
            [95, 1, 1, 6, 'Jeu 3B', 'Jacques Dupont'],
            [30, 1, 1, 7, 'Jeu 3B', 'Jean Duval'],
            [ 140, 5, 3, 8, 'Jeu 1G', 'Myra Valenzuela' ],
            [ 188, 9, 2, 4, 'Jeu 3F', 'Hyacinth Nielsen' ],
            [ 204, 2, 4, 8, 'Jeu 3H', 'Echo Strickland' ],
            [ 139, 0, 8, 6, 'Jeu 3A', 'Galvin Thompson' ],
            [ 291, 6, 9, 0, 'Jeu 3H', 'Jeremy Knight' ],
            [ 137, 1, 3, 0, 'Jeu 3F', 'Rhonda Miller' ],
            [ 251, 7, 6, 2, 'Jeu 3F', 'Charissa Ryan' ],
            [ 249, 9, 2, 1, 'Jeu 2E', 'Charissa Ryan' ],
            [ 262, 6, 7, 1, 'Jeu 2A', 'Myra Valenzuela' ],
            [ 243, 7, 8, 9, 'Jeu 2H', 'Elliott Waters' ],
            [ 124, 5, 1, 8, 'Jeu 1B', 'Raven Coleman' ],
            [ 222, 9, 0, 7, 'Jeu 1F', 'Gannon Olsen' ],
            [ 152, 0, 3, 1, 'Jeu 2F', 'Carol Roman' ],
            [ 192, 0, 3, 3, 'Jeu 3F', 'Amery Osborn' ],
            [ 130, 6, 8, 4, 'Jeu 1H', 'Harlan Finley' ],
            [ 225, 8, 3, 8, 'Jeu 2G', 'Ulysses Fitzgerald' ],
            [ 121, 2, 5, 5, 'Jeu 3A', 'Uta Rutledge' ],
            [ 125, 5, 8, 5, 'Jeu 3F', 'Echo Strickland' ],
            [ 186, 7, 1, 2, 'Jeu 2A', 'Tallulah Stark' ],
            [ 252, 2, 8, 3, 'Jeu 3F', 'Kerry Salazar' ],
            [ 133, 7, 6, 5, 'Jeu 2G', 'Jacques Dupont' ],
            [ 277, 1, 9, 8, 'Jeu 3G', 'Francis Robinson' ],
            [ 268, 2, 6, 6, 'Jeu 3A', 'Yael Booker' ],
            [ 139, 1, 0, 0, 'Jeu 2F', 'Benjamin Vargas' ],
            [ 232, 8, 6, 7, 'Jeu 3G', 'Chiquita Knapp' ],
            [ 241, 4, 1, 6, 'Jeu 3G', 'Colt George' ],
            [ 244, 6, 0, 6, 'Jeu 3F', 'Alexis Mills' ],
            [ 268, 8, 4, 6, 'Jeu 3F', 'Sierra Perry' ],
            [ 211, 8, 5, 1, 'Jeu 1A', 'Gannon Olsen' ],
            [ 126, 1, 2, 4, 'Jeu 1B', 'Carol Roman' ],
            [ 296, 7, 6, 1, 'Jeu 2F', 'Melinda Campbell' ],
            [ 268, 3, 3, 4, 'Jeu 2G', 'Maya Moody' ],
            [ 143, 6, 2, 8, 'Jeu 3A', 'Charissa Ryan' ],
            [ 282, 0, 1, 2, 'Jeu 1A', 'Gannon Olsen' ],
            [ 179, 1, 1, 4, 'Jeu 2H', 'Chantale Callahan' ],
            [ 278, 9, 6, 5, 'Jeu 3G', 'Charissa Ryan' ],
            [ 170, 9, 9, 1, 'Jeu 1H', 'Benjamin Vargas' ],
            [ 216, 1, 1, 7, 'Jeu 1B', 'Lydia Parsons' ],
            [ 174, 0, 2, 1, 'Jeu 1A', 'Patricia Scott' ],
            [ 211, 2, 7, 2, 'Jeu 1F', 'Carol Roman' ],
            [ 185, 2, 6, 5, 'Jeu 3A', 'Zephr Mcleod' ],
            [ 123, 2, 8, 3, 'Jeu 3G', 'Kerry Salazar' ],
            [ 186, 0, 0, 7, 'Jeu 3H', 'Sigourney Salinas' ],
            [ 262, 2, 7, 6, 'Jeu 3H', 'Shay Clark' ],
            [ 218, 8, 1, 0, 'Jeu 3A', 'Karleigh Alvarado' ],
            [ 221, 5, 7, 3, 'Jeu 1B', 'Zephr Mcleod' ],
            [ 209, 5, 3, 1, 'Jeu 1F', 'Celeste Melton' ],
            [ 162, 4, 1, 6, 'Jeu 3G', 'Zeus Duffy' ],
            [ 300, 4, 3, 2, 'Jeu 2B', 'Hyacinth Nielsen' ],
            [ 283, 1, 6, 0, 'Jeu 1D', 'Wyatt Navarro' ],
            [ 294, 9, 1, 4, 'Jeu 1G', 'Myra Valenzuela' ],
            [ 159, 8, 0, 2, 'Jeu 3H', 'Raven Coleman' ],
            [ 149, 0, 2, 8, 'Jeu 2F', 'Micah Francis' ],
            [ 224, 6, 4, 4, 'Jeu 3H', 'Uta Rutledge' ],
            [ 171, 5, 1, 8, 'Jeu 1B', 'Gannon Olsen' ],
            [ 280, 0, 5, 6, 'Jeu 3H', 'Rebecca Spence' ],
            [ 146, 2, 9, 0, 'Jeu 2E', 'Hyacinth Nielsen' ],
            [ 198, 6, 6, 7, 'Jeu 1B', 'Rebecca Spence' ],
            [ 184, 9, 4, 0, 'Jeu 3G', 'Tallulah Stark' ],
            [ 192, 6, 5, 0, 'Jeu 1H', 'Kristen Huffman' ],
            [ 213, 7, 2, 3, 'Jeu 2F', 'Echo Strickland' ],
            [ 226, 2, 2, 8, 'Jeu 1D', 'Jacques Dupont' ],
            [ 222, 9, 1, 9, 'Jeu 1D', 'Demetrius Delaney' ],
            [ 299, 4, 8, 0, 'Jeu 1D', 'Mikayla Klein' ],
            [ 182, 8, 7, 7, 'Jeu 2A', 'Alfreda Gomez' ],
            [ 233, 8, 9, 6, 'Jeu 3F', 'Desiree Larsen' ],
            [ 269, 5, 7, 7, 'Jeu 1D', 'Ignatius Erickson' ],
            [ 170, 9, 6, 0, 'Jeu 1F', 'Benjamin Vargas' ],
            [ 300, 8, 0, 0, 'Jeu 1A', 'Macon Clements' ],
            [ 247, 6, 5, 8, 'Jeu 2E', 'Callie Conner' ],
            [ 202, 3, 3, 7, 'Jeu 2G', 'Mary Le' ],
            [ 206, 5, 2, 2, 'Jeu 3G', 'Amery Osborn' ],
            [ 164, 7, 4, 5, 'Jeu 2G', 'Illana Nieves' ],
            [ 173, 3, 9, 1, 'Jeu 1A', 'Kristen Huffman' ],
            [ 298, 8, 7, 3, 'Jeu 1D', 'Celeste Melton' ],
            [ 166, 8, 3, 8, 'Jeu 3A', 'Rhonda Miller' ],
            [ 228, 0, 0, 3, 'Jeu 1D', 'Freya Wade' ],
            [ 171, 8, 5, 3, 'Jeu 1G', 'Hall Small' ],
            [ 233, 6, 9, 6, 'Jeu 3G', 'Illana Nieves' ],
            [ 199, 9, 9, 4, 'Jeu 1F', 'Callie Conner' ],
            [ 258, 5, 9, 7, 'Jeu 2G', 'Zena Woodard' ],
            [ 219, 7, 6, 1, 'Jeu 3F', 'Lois Petersen' ],
            [ 257, 1, 0, 0, 'Jeu 3A', 'Jescie Stein' ],
            [ 211, 2, 3, 6, 'Jeu 2G', 'Beatrice Mills' ],
            [ 174, 7, 6, 0, 'Jeu 2E', 'Harlan Finley' ],
            [ 159, 6, 0, 4, 'Jeu 3G', 'Clinton Wilkinson' ],
            [ 264, 0, 9, 8, 'Jeu 1B', 'Patricia Scott' ],
            [ 204, 9, 4, 9, 'Jeu 1F', 'Ulysses Fitzgerald' ],
            [ 183, 7, 6, 9, 'Jeu 2G', 'Jennifer Gutierrez' ],
            [ 120, 0, 4, 1, 'Jeu 1G', 'Macon Clements' ],
            [ 239, 9, 1, 3, 'Jeu 1F', 'Patricia Scott' ],
            [ 276, 1, 1, 5, 'Jeu 3G', 'Celeste Melton' ],
            [ 216, 8, 3, 4, 'Jeu 3G', 'Desiree Larsen' ],
            [ 170, 7, 8, 5, 'Jeu 2G', 'Lydia Parsons' ],
            [ 122, 3, 4, 8, 'Jeu 1F', 'Ulysses Fitzgerald' ],
            [ 132, 0, 7, 3, 'Jeu 1F', 'Meghan Delaney' ],
            [ 125, 0, 0, 9, 'Jeu 2G', 'Jonah Sims' ],
            [ 157, 3, 1, 1, 'Jeu 3G', 'Herrod Hammond' ],
            [ 241, 6, 8, 0, 'Jeu 3A', 'Susan Nash' ],
            [ 246, 2, 4, 7, 'Jeu 3H', 'Jescie Stein' ],
        ];

        foreach ($data as $line) {
            $eh = new ExerciseHistory();
            $eh->setTimer($line[0]);
            $eh->setHealthNote($line[1]);
            $eh->setOrganizationNote($line[2]);
            $eh->setBusinessNotorietyNote($line[3]);

            $exercise = $manager
                ->getRepository('OrgabatGameBundle:Exercise')
                ->findOneByName($line[4])
            ;
            $eh->setExercise($exercise);

            $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
            $discriminator->setClass('Orgabat\GameBundle\Entity\Apprentice');
            $userManager = $this->container->get('pugx_user_manager');
            $user = $userManager->findUserBy(['username' => $line[5]]);
            $eh->setUser($user);

            $manager->persist($eh);
        }

        // On déclenche l'enregistrement de tous les tags
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}
