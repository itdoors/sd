<?php

namespace ITDoors\AjaxBundle\Twig;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Twig extention for rendering filte form
 *
 * @author Pavel Pechreny ppecheny@gmail.com
 */
class AjaxFilterExtention extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @var Container $container
     */
    protected $container;

    /**
     * __construct()
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
            'ajax_filter_render' => new \Twig_Function_Method($this, 'render', array('is_safe' => array('html')))
        );
    }

    /**
     * Renders filter form for ajax submit
     *
     * @param string $formAlias form service alias
     * @param string $filterNamespace filter session holder name
     * @param string[] $successFunctions function that triggers when filter form is valid \
     *        array('targetId' => array('functionName1', 'functionName2'))
     *
     * @return string
     */
    public function render($formAlias, $filterNamespace, $successFunctions = array())
    {
        $sessionFilters = $this->getSessionFilters($filterNamespace);

        /** @var Form $form */
        $form = $this->container->get('form.factory')->create($formAlias, $sessionFilters);

        $form
            ->add('formAlias', 'hidden', array(
                'data' => $formAlias,
                'attr' => array(
                    'name' => 'formAlias'
                )
            ))
            ->add('filterNamespace', 'hidden', array(
                'data' => $filterNamespace
            ));

        if (sizeof($successFunctions))
        {
            $form
                ->add('successFunctions', 'hidden', array(
                    'data' => json_encode($successFunctions)
                ));
        }

        // Maybe need add in controller... will see
        $form
            ->add('submit', 'submit')
            ->add('reset', 'submit');

        return $this->environment->render('ITDoorsAjaxBundle:Filter:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Return session filter data depending on filter namespace
     */
    public function getSessionFilters($namespace)
    {
        /** @var Session $session */
        $session = $this->container->get('session');

        return $session->get($namespace);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'ajax_filter';
    }
}