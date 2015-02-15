<?php

namespace SD\UserBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use SD\UserBundle\Entity\Deputy;

/**
 * DeputiesController
 */
class DeputiesController extends BaseController
{
    /**
     * Executes index action
     *
     * @return string
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $chiefs = $this
            ->getDoctrine()
            ->getRepository('ListsCompanystructureBundle:Companystructure')
            ->getAllChiefs();

        return $this->render('SDUserBundle:Deputies:index.html.twig', array(
                        'chiefs' => $chiefs
        ));
    }

    /**
     * Executes saveAction
     * 
     * @param Request $request
     *
     * @return string
     */
    public function saveAction(Request $request)
    {
        $chiefStuffId = $request->get('pk');
        $deputiesUserIds = $request->get('value');
        $em = $this->getDoctrine()->getManager();

        $deputyUsers = $this->getDoctrine()
            ->getRepository('SDUserBundle:User')
            ->findBy(array('id' => $deputiesUserIds));

        $deputy = $em
            ->getRepository('SDUserBundle:Deputy')
            ->findOneBy(array('forStuff' => $chiefStuffId));

        $deputyStuffs = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($deputyUsers as $deputyUser) {
            $deputyStuffs->add($deputyUser->getStuff());
        }

        if (!$deputy) {
            $deputy = new Deputy();
            $chief = $this->getDoctrine()
                ->getRepository('SDUserBundle:Stuff')
                ->findOneBy(array('id' => $chiefStuffId));
            $deputy->setForStuff($chief);
        }

        $deputy->setDeputyStuffs($deputyStuffs);

        try {
            $em->persist($deputy);
            $em->flush();
        } catch (\Exception $e) {
            //Some error message...
        }

        return new JsonResponse();
    }
}
