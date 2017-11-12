<?php
/**
 * Created by PhpStorm.
 * User: lenaic
 * Date: 29/01/2017
 * Time: 16:19
 */

namespace Orgabat\GameBundle\Controller;

use Orgabat\GameBundle\Entity\Apprentice;
use Orgabat\GameBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $this->getPlayedCategoriesByUser($user);

        // Create a view for rendering the pdf
        $html = $this->renderView('OrgabatGameBundle:Pdf:user.html.twig', $data);

        /*
         * // If You want to see the twig file
        return $this->render('OrgabatGameBundle:Pdf:user.html.twig', $data);
        */

        // Generate the pdf
        return $this->generatePdfFile(
            $user->getSluggedFullName(),
            $html
        );
    }

    /**
     * Get a pdf file with all apprentices statistics under the responsability of the current user
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

        $dataByUsers = [];
        foreach ($sections as $section) {
            foreach ($section->getApprentices() as $apprentice) {
                $dataByUsers[] = $this->getPlayedCategoriesByUser($apprentice);
            }
        }

        // Create a view for rendering the pdf
        $html = $this->renderView('OrgabatGameBundle:Pdf:allUsers.html.twig', array('dataByUsers' => $dataByUsers));

        /* // If you want to render the twig file
        return $this->render('OrgabatGameBundle:Pdf:allUsers.html.twig', array('dataByUsers' => $dataByUsers));
        */

        // Generate the pdf
        return $this->generatePdfFile(
            'apprentis',
            $html
        );
    }

    /**
     * Get all the categories played along with exercises and exercisesHistory by a given apprentice
     *
     * @ParamConverter("apprentice", options={"mapping": {"apprentice_id": "id"}})
     * 
     * @param Apprentice
     * @return Array 
    */
    private function getPlayedCategoriesByUser(Apprentice $apprentice) {
        $em = $this->getDoctrine()->getManager();

        // Get the categories played by the current user
        $categories = $em
            ->getRepository('OrgabatGameBundle:Category')
            ->getExercisesOfAllCategoriesByUserArray($apprentice)
        ;

        return array(
            'user' => $apprentice,
            'categories' => $this->decorateCategories($categories),
        );
    }

    /**
     * Decorate categorie array for the front displaying
     *
     * @param Array : the categories played by the apprentice
     * @return Array : the decorate array of categories
    */
    private function decorateCategories($categories) {
        foreach ($categories as $c_key => $category) {
            $globalStats = [
                'healthNote' => 0,
                'organizationNote' => 0,
                'businessNotorietyNote' => 0,
                'healthMaxNote' => 0,
                'organizationMaxNote' => 0,
                'businessNotorietyMaxNote' => 0,
                'attemptCount' => 0,
                'timer' => 0,
            ];
            foreach ($category['exercises'] as $e_key => $exercise) {
                $bestAttempt = $this->getExercisesBestAttempt($exercise['exerciseHistories']);

                $globalStats['healthNote'] += $bestAttempt['healthNote'];
                $globalStats['organizationNote'] += $bestAttempt['organizationNote'];
                $globalStats['businessNotorietyNote'] += $bestAttempt['businessNotorietyNote'];
                $globalStats['healthMaxNote'] += $exercise['healthMaxNote'];
                $globalStats['organizationMaxNote'] += $exercise['organizationMaxNote'];
                $globalStats['businessNotorietyMaxNote'] += $exercise['businessNotorietyMaxNote'];
                $globalStats['attemptCount'] += $bestAttempt['attemptCount'];
                $globalStats['timer'] += $bestAttempt['timer'];

                $categories[$c_key]['exercises'][$e_key]['bestAttempt'] = $bestAttempt;
                unset($categories[$c_key]['exercises'][$e_key]['exerciseHistories']);
            }
            $categories[$c_key]['globalGameStatistics'] = $globalStats;
        }
        return $categories;
    }

    /**
    * Get the best attempt for an exercise from given exercise history
    *
    * @param Array : exerciseHistories from an exercise
    * @return Array : the best attempt
    */
    private function getExercisesBestAttempt($ehs) {
        $bestAttempt = null;
        $bestTotalScore = 0;
        foreach ($ehs as $key => $eh) {
            $ehTotalScore = $eh['healthNote'] + $eh['organizationNote'] + $eh['businessNotorietyNote'];

            if ($ehTotalScore > $bestTotalScore || !empty($bestAttempt) && $ehTotalScore == $bestTotalScore && $eh['timer'] > $ehs[$bestAttemptKey]['timer']) {
                $bestTotalScore = $ehTotalScore;
                $bestAttempt = $ehs[$key];
            }            
        }

        $bestAttempt['attemptCount'] = count($ehs);

        return $bestAttempt;
    }

    /**
     * Get html and filename to generate a pdf
     *
     * @return Response : pdf file
     */
    private function generatePdfFile(String $filename, String $html) {
        $html2pdf = $this->get('html2pdf_factory')->create();
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        return new Response(
            $html2pdf->Output(__DIR__.$filename.".pdf", 'S'),
            200,
            [
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="' . $filename . '".pdf"'
            ]
        );
    }
}
