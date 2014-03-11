<?php

namespace ITDoors\AjaxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * FilterController class
 *
 * Generates, submits, handles ajax filter forms
 */
class FilterController extends Controller
{
    /**
     * indexAction
     *
     * @param Request $request
     *
     * @return string
     */
    public function indexAction(Request $request)
    {
        $formAlias = $request->request->get('formAlias');

        $requestData = $request->request->get($formAlias);

        $form = $this->createForm($formAlias);

        if ($request->request->get('reset')) {
            $this->clearFilters($requestData['filterNamespace']);
        }
        else {

            $form->handleRequest($request);

            $data = $form->getData();

            $this->setFilters($requestData['filterNamespace'], $data);
        }

        $result = array(
            'html' => '',
            'error' => false,
            'success' => true,
            'successFunctions' => isset($requestData['successFunctions']) ? $requestData['successFunctions'] : array()
        );

        /*$result['html'] = $this->renderView('ITDoorsAjaxBundle:Filter:form.html.twig', array(
            'form' => $form->createView(),
        ));*/

        return new Response(json_encode($result));
    }

    /**
     * Sets filter info to the session
     *
     * @param string $filterNamespace
     * @param mixed[] $filters
     */
    public function setFilters($filterNamespace, $filters)
    {
        /** @var Session $session */
        $session = $this->get('session');

        $session->set($filterNamespace, $filters);
    }

    /**
     * Get filter from session
     *
     * @param string $filterNamespace
     *
     * @return mixed[]
     */
    public function getFilters($filterNamespace)
    {
        /** @var Session $session */
        $session = $this->get('session');

        $filters = $session->get($filterNamespace);

        return $filters;
    }

    /**
     * Clear filters
     *
     * @param string $filterNamespace
     */
    public function clearFilters($filterNamespace)
    {
        $this->setFilters($filterNamespace, array());
    }
}
