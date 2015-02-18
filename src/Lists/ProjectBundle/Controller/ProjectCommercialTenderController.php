<?php

namespace Lists\ProjectBundle\Controller;

use Lists\ProjectBundle\Controller\ProjectBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ProjectCommercialTenderController
 */
class ProjectCommercialTenderController extends ProjectBaseController
{
    protected $filterNamespace = 'project_commercial_tender';
    protected $createForm = 'projectCommercialTender';
    protected $nameEntity = 'ProjectCommercialTender';

    /**
     * Executes create action
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createAction (Request $request)
    {
        $this->createNotFoundException();
    }
    /**
     * indexAction
     *
     * @return Response
     */
    public function indexAction ()
    {
        $this->createNotFoundException();
    }
    /**
     * gosListAction
     *
     * @return Response
     */
    public function listAction ()
    {
        $this->createNotFoundException();
    }
}
