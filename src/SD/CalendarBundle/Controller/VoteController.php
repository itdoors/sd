<?php

namespace SD\CalendarBundle\Controller;

use ITDoors\CommonBundle\Controller\BaseFilterController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SD\CalendarBundle\Entity\Article;

/**
 * Class VoteController
 */
class VoteController extends BaseFilterController
{
    protected $baseTemplate = 'Vote';
    /**
     * Renders template holder for calendar
     *
     * @return string
     */
    public function historyAction()
    {
        return $this->render('SDCalendarBundle:' . $this->baseTemplate . ':history.html.twig');
    }
    /**
     * Renders template holder for calendar
     *
     * @return string
     */
    public function listAction()
    {
        return $this->render('SDCalendarBundle:' . $this->baseTemplate . ':list.html.twig');
    }
    /**
     * Renders template holder for calendar
     *
     * @return string
     */
    public function addPartyAction(Request $request)
    {
        
        $article = new Article();
        $router = $this->get('router');
        $form = $this->createFormBuilder($article)
            ->add('user', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field control-label col-md-3',
                    'data-url' => $router->generate('sd_common_ajax_user_fio'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter fio',
                )
            ))
            ->add('datecreate', 'text', array(
            ))
            ->add('title', 'text', array(
            ))
            ->add('textShort', 'textarea', array(
            ))
            ->add('text', 'textarea', array(
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Connection $connection */
            $connection = $this->getDoctrine()->getConnection();

            $connection->beginTransaction();

            try {
//                $formData = $request->request->get($form->getName());
//                $userId = $formData['user'];
//                $user = $em->getRepository('SDUserBundle:user')->find($userId);
//
//                
//                $party  = new Article();
//
//                $party->setUser($user);
//                $party->setType('article');
//
//                if (!empty($formData['datecreate'])) {
//                    $party->setDateCreate(new \DateTime($formData['datecreate']));
//                }
//                $party->setTitle($formData['title']);
//                $party->setTextShort($formData['textShort']);
//                $party->setText($formData['text']);
//              
//
//                $em->persist($party);
//
//                $em->flush();

                $connection->commit();
                
            } catch (\Exception $e) {
                $connection->rollBack();
                $em->close();
                throw $e;
            }

            return $this->redirect($this->generateUrl('sd_calendar_vote_history'));
        }
        
        return $this->render('SDCalendarBundle:' . $this->baseTemplate . ':addParty.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
}
