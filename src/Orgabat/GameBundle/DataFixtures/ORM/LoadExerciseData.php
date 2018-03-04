<?php

namespace Orgabat\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Orgabat\GameBundle\Entity\Exercise;

class LoadExerciseData implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = [
            // [ Slug du jeu <string>, id du jeu <int>, score max santée <int>, score max organisation <int>, score max notoriété <int>, description courte du jeu <string> ]
            // Jeu test : [ 'Jeu 1', 'Approvisionnement', 3, 4, 2, 1, '' ],
            [ 'Jeu 1A', 8, 'Communication', 4, 6, 3, 'Réagir correctement en cas d\'accident du travail.' ],
            [ 'Jeu 2A', 9, 'Communication', 6, 0, 0, 'Savoir identifier  les  instances  de  prévention  dans  mon  environnement  professionnel.' ],
            [ 'Jeu 3A', 10, 'Communication', 5, 5, 5, 'Savoir dégager les informations utiles de conversations professionnelles.' ],
            [ 'Jeu 1B', 4, 'Approvisionnement', 5, 5, 5, 'Choisir les différents matériaux, matériels et équipements de protection pour la réalisation d\'un mur en agglo de 20 en retour d\'angle.' ],
            [ 'Jeu 2B', 11, 'Approvisionnement', 0, 10, 5, 'Contrôler l\'ensemble de la marchandise à l\'aide du bon de livraison donné par le chauffeur.' ],
            [ 'Jeu 3B', 19, 'Approvisionnement', 6, 6, 3, 'Amener la palette d\'agglos, le sable et le gravier au bon endroit sur le chantier.' ],
            [ 'Jeu 1C', 7, 'Déplacements', 10, 0, 0, 'Cliquer sur les différentes cartes et retrouver celles qui sont identiques.' ],
            [ 'Jeu 2C', 12, 'Déplacements', 5, 5, 5, 'Retrouver le nom des équipements d\'arrimage sur camionnette et remorque.' ],
            [ 'Jeu 1D', 13, 'Circulation', 3, 3, 3, 'Trouver la bonne protecion afin de sécuriser la zone concernée' ],
            [ 'Jeu 1E', 6, 'Manutention et postures au travail', 11, 6, 0, 'Repérer les conduites qui permettent de charger le véhicule tout en préservant son intégrité physique.' ],
            [ 'Jeu 2E', 14, 'Manutention et postures au travail', 0, 4, 0, 'Choisir le cheminement le plus adapté pour approvisionner son poste de travail.' ],
            [ 'Jeu 3E', 5, 'Manutention et postures au travail', 19, 0, 0, 'Identifier les bonnes et les mauvaises postures au travail et les moyens de prévention.' ],
            [ 'Jeu 1F', 17, 'Protection', 9, 0, 9, 'Proposer des mesures de prévention adaptées à chaque situation en s\'appuyant sur lesprincipes généraux de prévention.' ],
            [ 'Jeu 2F', 15, 'Protection', 5, 0, 0, 'Choisir les bons Equipements de Protection Individuel nécessaire à la réalisation de la tâche concernée.' ],
            [ 'Jeu 3F', 23, 'Protection', 14, 9, 1, 'Analyser la situation de travail et évaluer le risque d\'accident.' ],
            [ 'Jeu 1G', 18, 'Gestion de l\'espace de travail', 3, 9, 1, 'Trouver des solutions pour éliminer la pénibilité sur un chantier.' ],
            [ 'Jeu 3G', 16, 'Gestion de l\'espace de travail', 9, 6, 3, 'Cohabitation entre l\'intervention professionnelle et le cadre de vie du client.' ],
            [ 'Jeu 1H', 3, 'Repli de chantier', 5, 5, 5, 'Repérer les déchets et savoir les trier en utilisant les Équipements de Protection Individuel adaptés.' ],
            [ 'Jeu 3H', 2, 'Repli de chantier', 6, 5, 3, 'Repérer les risques et les dangers pour sécuriser au mieux le chantier.' ],   
        ];

        foreach ($data as $line) {
            $exercise = new Exercise;
            $exercise->setName($line[0]);
            $exercise->setCode($line[1]);

            $category = $manager
                ->getRepository('OrgabatGameBundle:Category')
                ->findOneByName($line[2])  
            ;

            $exercise->setCategory($category);
            $exercise->setHealthMaxNote($line[3]);
            $exercise->setOrganizationMaxNote($line[4]);
            $exercise->setBusinessNotorietyMaxNote($line[5]);
            $exercise->setDescription($line[6]);

            $manager->persist($exercise);
        }

        // On déclenche l'enregistrement de tous les tags
        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}
