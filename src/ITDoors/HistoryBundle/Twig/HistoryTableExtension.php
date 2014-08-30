<?php

namespace ITDoors\HistoryBundle\Twig;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\Translator;

/**
 * HistoryTableExtension
 *
 */
class HistoryTableExtension extends \Twig_Extension
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
            'history_table_render' => new \Twig_Function_Method($this, 'render', array('is_safe' => array('html'))),
            'history_table_render_btn' => new \Twig_Function_Method($this, 'renderBtn', array('is_safe' => array('html')))
        );
    }

    /**
     * Renders form for ajax submission
     *
     * @param mixed[] $options
     * Includes array(
     *      selector,       $(selector)     class name or id
     *      formType,       form type service alias
     *      saveService = array('serviceName' => 'saveMethod')
     *      successFunctions = array('targetId' => array('functionName1', 'functionName2'))
     *      errorFunctions = array('targetId' => array('functionName1', 'functionName2'))
     *      target          $(target)       selector where form will be generated
     *      isModal         default: false  open form in modal window
     * )
     * saveService          service alias $serviceName->$saveMethod(Form $form, Request $request)
     * successFunctions     function that triggers when filter form is valid
     * errorFunctions       function that triggers when filter form has errors
     *
     * @return string
     */
    public function render($options)
    {
        return $this->environment->render("ITDoorsHistoryBundle:History:index.html.twig", array(
            'options' => $options
        ));
    }

    /**
     * Prepares $(selector) for ajax form submission
     *
     * @param mixed[] $options
     * Includes array(
     *      selector,       $(selector)     class name or id
     *      formType,       form type service alias
     *      saveService = array('serviceName' => 'saveMethod')
     *      successFunctions = array('targetId' => array('functionName1', 'functionName2'))
     *      errorFunctions = array('targetId' => array('functionName1', 'functionName2'))
     *      target          $(target)       selector where form will be generated
     *      isModal         default: false  open form in modal window
     * )
     * saveService          service alias $serviceName->$saveMethod(Form $form, Request $request)
     * successFunctions     function that triggers when filter form is valid
     * errorFunctions       function that triggers when filter form has errors
     *
     * @return string
     */
    public function renderBtn($options)
    {
        $this->addDefaultOptions($options);

        return $this->environment->render("ITDoorsHistoryBundle:History:indexBtn.html.twig", array(
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
        $options['url'] = $router->generate('it_doors_history');

        // Add loading text
        if (!isset($options['loadingText'])) {
            /** @var Translator $translator */
            $translator = $this->container->get('translator');

            $options['loadingText'] = $translator->trans('Loading...', array(), 'ITDoorsHistoryBundle');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'history_table';
    }
}
