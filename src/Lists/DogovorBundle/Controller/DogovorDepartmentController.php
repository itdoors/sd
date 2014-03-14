<?php

namespace Lists\DogovorBundle\Controller;

use ITDoors\CommonBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class DogovorDepartmentController extends BaseController
{
    protected $filterNamespace = 'base.dogovor.department.filters';
    protected $baseRoutePrefix = 'dogovor.department';
    protected $baseTemplate = 'DogovorDepartment';

    /**
     * Returns department list
     *
     * @param int $dogovorId
     *
     * @return string
     */
    public function listAction($dogovorId)
    {
        /** @var \Lists\DogovorBundle\Entity\DogovorDepartmentRepository $repository */
        $repository = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:DogovorDepartment');

        /** @var \Doctrine\ORM\Query $query */
        $query = $repository->getAllByDogovorIdQuery($dogovorId);

        $items = $query->getResult();

        return $this->render('ListsDogovorBundle:' . $this->baseTemplate. ':list.html.twig', array(
            'items' => $items,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
}
