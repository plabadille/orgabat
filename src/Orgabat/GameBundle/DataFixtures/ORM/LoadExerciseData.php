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
            // Jeu test : [ 'Jeu 1', 'Approvisionnement', 3, 4, 2, 1, '' ],
            [ 'Jeu 1B', 4, 'Approvisionnement', 5, 4, 1, 'Choisir les différents matériaux, matériels et équipements de protection pour la réalisation d\'un mur en agglo de 20 en retour d\'angle.' ],
            [ 'Jeu 2B', 11, 'Approvisionnement', 1, 1, 7, 'Contrôler l\'ensemble de la marchandise à l\'aide du bon de livraison donné par le chauffeur.' ],
            [ 'Jeu 3B', 19, 'Approvisionnement', 8, 4, 8, 'Amener la palette d\'agglos, le sable et le gravier au bon endroit sur le chantier.' ],
            [ 'Jeu 1C', 7, 'Déplacements', 3, 4, 7, 'Cliquer sur les différentes cartes et retrouver celles qui sont identiques.' ],
            [ 'Jeu 2C', 12, 'Déplacements', 4, 6, 4, 'Retrouver le nom des équipements d\'arrimage sur camionnette et remorque.' ],
            [ 'Jeu 1D', 13, 'Circulation', 3, 3, 3, 'Trouver la bonne protecion afin de sécuriser la zone concernée' ],
            [ 'Jeu 1E', 6, 'Manutention et postures au travail', 2, 4, 8, 'Repérer les conduites qui permettent de charger le véhicule tout en préservant son intégrité physique.' ],
            [ 'Jeu 2E', 14, 'Manutention et postures au travail', 3, 8, 9, 'Choisir le cheminement le plus adapté pour approvisionner son poste de travail.' ],
            [ 'Jeu 3E', 5, 'Manutention et postures au travail', 9, 9, 5, 'Identifier les bonnes et les mauvaises postures au travail et les moyens de prévention.' ],
            [ 'Jeu 1F', 17, 'Protection', 8, 8, 1, 'Proposer des mesures de prévention adaptées à chaque situation en s\'appuyant sur lesprincipes généraux de prévention.' ],
            [ 'Jeu 2F', 15, 'Protection', 5, 1, 6, 'Choisir les bons Equipements de Protection Individuel nécessaire à la réalisation de la tâche concernée.' ],
            [ 'Jeu 3F', 23, 'Protection', 3, 9, 8, 'Analyser la situation de travail et évaluer le risque d\'accident.' ],
            [ 'Jeu 1H', 3, 'Repli de chantier', 2, 7, 6, 'Repérer les déchets et savoir les trier en utilisant les Équipements de Protection Individuel adaptés.' ],
            [ 'Jeu 2H', 25, 'Repli de chantier', 2, 3, 1, 'W I P : à venir.' ],
            [ 'Jeu 3H', 2, 'Repli de chantier', 6, 5, 3, 'Repérer les risques et les dangers pour sécuriser au mieux le chantier.' ],
            [ 'Jeu 1G', 18, 'Gestion de l\'espace de travail', 3, 9, 1, 'Trouver des solutions pour éliminer la pénibilité sur un chantier.' ],
            [ 'Jeu 2G', 24, 'Gestion de l\'espace de travail', 4, 8, 6, 'Repérer les six erreurs afin de compléter la grille de mots fléchés.' ],
            [ 'Jeu 3G', 16, 'Gestion de l\'espace de travail', 9, 6, 3, 'Cohabitation entre l\'intervention professionnelle et le cadre de vie du client.' ],
            [ 'Jeu 1A', 8, 'Communication', 4, 6, 3, 'Réagir correctement en cas d\'accident du travail.' ],
            [ 'Jeu 2A', 9, 'Communication', 7, 9, 5, 'Savoir identifier  les  instances  de  prévention  dans  mon  environnement  professionnel.' ],
            [ 'Jeu 3A', 10, 'Communication', 8, 4, 8, 'Savoir dégager les informations utiles de conversations professionnelles.' ],
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
