<?php
/**
 * Rendering ajax_table twig extension
 *
 * @author Pavel Pechreny ppecheny@gmail.com
 */
namespace SD\CommonBundle\Twig;

/**
 * AjaxTableExtension
 */
class AjaxTableExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

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
    public function getName()
    {
        return 'ajax_table';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            'ajax_table_render' => new \Twig_Function_Method($this, 'render', array('is_safe' => array('html')))
        );
    }

    /**
     * {@inheritDoc}
     */
    public function render($params)
    {
        return $this->environment->render('SDCommonBundle:AjaxTable:index.html.twig', array(
            'params' => $params
        ));
    }
}
