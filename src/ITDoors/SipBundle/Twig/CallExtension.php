<?php

namespace ITDoors\SipBundle\Twig;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\Translator;

/**
 * HistoryTableExtension
 *
 */
class CallExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @var Container
     */
    protected $container;

    /**
     * __construct()
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            'call_botton' => new \Twig_Function_Method(
                $this,
                'render',
                array('is_safe' => array('html'))
            )
        );
    }

    /**
     * Renders form for ajax submission
     *
     * @param mixed[] $options
     * Includes array(
     *      selector,       $(selector)     class name or id
     *      params,         array('modelName' => 'model', 'modelId' => 0', 'formName' => null)
     * )
     * saveService          service alias $serviceName->$saveMethod(Form $form, Request $request)
     * successFunctions     function that triggers when filter form is valid
     * errorFunctions       function that triggers when filter form has errors
     *
     * @return string
     */
    public function render($options)
    {
        $session = $this->container->get('session');
        $namespace = 'call'.$options['params']['modelName'].$options['params']['modelId'];

        $session->set($namespace, array('call' => $options['params']));

        return $this->environment->render("ITDoorsSipBundle:Botton:form.html.twig", array(
            'options' => $options
        ));
    }

    /**
     * Adds default options like url
     *
     * @param mixed[] &$options
     */
    public function addDefaultOptions(&$options)
    {
        if (!isset($options['defaults'])) {
            $options['defaults'] = array();
        }

        /** @var Router $router*/
        $router = $this->container->get('router');
        $options['url'] = $router->generate('it_doors_call');

        // Add loading text
        if (!isset($options['loadingText'])) {
            /** @var Translator $translator */
            $translator = $this->container->get('translator');

            $options['loadingText'] = $translator->trans('Loading...', array(), 'ITDoorsCallBundle');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'call';
    }
}
