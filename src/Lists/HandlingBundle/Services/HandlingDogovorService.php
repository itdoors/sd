<?php

namespace Lists\HandlingBundle\Services;

use Doctrine\ORM\EntityManager;
use Lists\DogovorBundle\Entity\Dogovor;
use Lists\DogovorBundle\Entity\DogovorRepository;
use Lists\HandlingBundle\Entity\Handling;
use Lists\HandlingBundle\Entity\HandlingDogovor;
use Lists\HandlingBundle\Entity\HandlingRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * HandlingDogovorService class
 */
class HandlingDogovorService
{
    /**
     * @var Container $container
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
     * Add form defaults depending on defaults)
     *
     * @param Form    $form
     * @param mixed[] $defaults
     */
    public function addFormDefaults(Form $form, $defaults)
    {

    }

    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveForm(Form $form, Request $request, $params)
    {
        $data = $form->getData();

        $handlingDogovor = new HandlingDogovor();

        /** @var DogovorRepository $dr */
        $dr = $this->container->get('lists_dogovor.repository');
        /** @var HandlingRepository $hr */
        $hr = $this->container->get('handling.repository');

        /** @var Dogovor $dogovor */
        $dogovor = $dr->find($data['dogovorId']);
        /** @var Handling $handling */
        $handling = $hr->find($data['handlingId']);

        $handlingDogovor->setDogovor($dogovor);
        $handlingDogovor->setHandling($handling);

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        $em->persist($handlingDogovor);
        $em->flush();
    }
}
