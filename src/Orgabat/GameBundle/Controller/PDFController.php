<?php

namespace Orgabat\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PDFController extends Controller
{
    /**
     * Get a pdf file for an apprentice using his games statistics
     *
     * @return Response : pdf file
     */
    public function getUserInfosUsingPDFAction() {
        // Get the current user
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        // Get all exercises
        $exercises = $em->getRepository('OrgabatGameBundle:Exercise')->findAll();

        // Get the categories played by the current user
        $categories = $em
            ->getRepository('OrgabatGameBundle:Category')
            ->getExercisesOfAllCategoriesByUser($user)
        ;

        // Get the total global score
        $globalScore = [
            'healthNote' => 0,
            'organizationNote' => 0,
            'businessNotorietyNote' => 0,
        ];
        foreach ($exercises as $exercise) {
            $globalScore['healthNote'] += $exercise->getHealthMaxNote();
            $globalScore['organizationNote'] += $exercise->getOrganizationMaxNote();
            $globalScore['businessNotorietyNote'] += $exercise->getBusinessNotorietyMaxNote();
        }

        // Create a view for rendering the pdf
        $html = $this->renderView('OrgabatGameBundle:Pdf:user.html.twig', [
            'user' => $user,
            'categories' => $categories,
            'stats' => ['global' => $globalScore],
        ]);

        //If You want to see the twig file
        /*return $this->render('OrgabatGameBundle:Pdf:user.html.twig', array(
            'user' => $user,
            'categories' => $categories,
            'stats' => [
                'global' => $globalScore
            ],
        ));*/

        // Generate the pdf
        $html2pdf = $this->get('html2pdf_factory')->create();
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);

        return new Response(
            $html2pdf->Output(__DIR__.$user->getFullName().".pdf", 'S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $user->getFullName() . '".pdf"',
            ]
        );
    }

    /**
     * Get a pdf file for all apprentices statistics
     *
     * @return Response : pdf file
     */
    public function getAllUsersInfosUsingPDFAction() {
        // Get the current user
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        // Get the sections managed by the user
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            // Get all sections if user == admin
            $sections = $em
                ->getRepository('OrgabatGameBundle:Section')
                ->getWithTrainersAndApprentices()
            ;
        } else {
            // Get section managed if user == trainer
            $sections = $em
                ->getRepository('OrgabatGameBundle:Trainer')
                ->getWithSections($user->getId())
                ->getSections()
            ;
        }

        // Get all exercises
        $exercises = $em->getRepository('OrgabatGameBundle:Exercise')->findAll();

        // Prepare data needed by the view
        // Note : We don't use CategoryRepository/getExercisesOfAllCategoriesByUser because of some unknow bug (maybe context issue)
        $exercisesHistoryByCategoriesAndUsers = [];
        foreach ($sections as $section) {
            // Iterate all apprentices of the current user
            $i=0;
            foreach ($section->getApprentices() as $apprentice) {
                $exercisesHistoryByCategory = [
                    'categories' => [],
                    'apprentice' => null,
                ];

                $exercisesHistory = $em
                    ->getRepository('OrgabatGameBundle:ExerciseHistory')
                    ->getAllHistoryExercicesByUser($apprentice)
                ;

                $exercisesHistoryByCategory['apprentice'] = $apprentice;
                #the user never play
                if (empty($exercisesHistory)) {
                    continue;
                }

                #we add in an array the data we need
                $eh_in_category_mapping = array_map(
                    function($exerciseHistory) {
                        return $exerciseHistory->getExercise()->getCategory();
                    },
                    $exercisesHistory
                );
                $eh_in_exercise_mapping = array_map(
                    function($exerciseHistory) {
                        return $exerciseHistory->getExercise();
                    },
                    $exercisesHistory
                );

                foreach ($exercisesHistory as $key => $eh) {
                    $category_name = $eh_in_category_mapping[$key]->getName();
                    $exercise_name = $eh_in_exercise_mapping[$key]->getName();

                    if (!isset ($exercisesHistoryByCategory['categories'][$category_name]) ) {
                        #The category haven't been added yet (everything is new)
                        $exercisesHistoryByCategory['categories'][$category_name] = [
                            'category' => $eh_in_category_mapping[$key],
                            'exercises' => [
                                $exercise_name => [
                                    'exercise' => $eh_in_exercise_mapping[$key],
                                    'exercisesHistory' => [$eh],
                                ],
                            ],
                        ];
                    } else {
                        #The category have been already added (but we have to check if the exercise has)
                        if (!isset ($exercisesHistoryByCategory['categories'][$category_name]['exercises'][$exercise_name] ) ) {
                            #The exercise haven't been added yet
                            $exercisesHistoryByCategory['categories'][$category_name]['exercises'][$exercise_name] = [
                                    'exercise' => $eh_in_exercise_mapping[$key],
                                    'exercisesHistory' => [$eh],
                                ];  
                        } else {
                            #The exercise have already been added
                            $exercisesHistoryByCategory['categories'][$category_name]['exercises'][$exercise_name]['exercisesHistory'][] = $eh;
                        }
                    }
                }

                $i++;

                #we sort needed rows
                ksort($exercisesHistoryByCategory['categories']);
                array_walk(
                    $exercisesHistoryByCategory['categories'], 
                    function($category, $key) {
                        ksort($category['exercises']);
                    }
                );

                #we add data to the main array
                array_push($exercisesHistoryByCategoriesAndUsers, $exercisesHistoryByCategory);
            }
        }

        // Get the total global score
        $globalScore = [
            'healthNote' => 0,
            'organizationNote' => 0,
            'businessNotorietyNote' => 0,
        ];
        foreach ($exercises as $exercise) {
            $globalScore['healthNote'] += $exercise->getHealthMaxNote();
            $globalScore['organizationNote'] += $exercise->getOrganizationMaxNote();
            $globalScore['businessNotorietyNote'] += $exercise->getBusinessNotorietyMaxNote();
        }

        // Create a view for rendering the pdf
        $html = $this->renderView('OrgabatGameBundle:Pdf:allUsers.html.twig', [
            'exercisesHistoryByCategoriesAndUsers' => $exercisesHistoryByCategoriesAndUsers,
            'stats' => ['global' => $globalScore],
        ]);

        
        // If you want to render the twig file
        // return $this->render('OrgabatGameBundle:Pdf:allUsers.html.twig', array(
        //     'exercisesHistoryByCategoriesAndUsers' => $exercisesHistoryByCategoriesAndUsers,
        //     'stats' => [
        //         'global' => $globalScore
        //     ],
        // ));
        

        // Generate the pdf
        $html2pdf = $this->get('html2pdf_factory')->create();
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);

        return new Response(
            $html2pdf->Output(__DIR__."apprentis.pdf", 'S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="apprentis.pdf"'
            ]
        );
    }
}
