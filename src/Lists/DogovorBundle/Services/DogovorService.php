<?php

namespace Lists\DogovorBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Symfony\Component\BrowserKit\Response;

/**
 * Invoice Service class
 */
class DogovorService
{

    /**
     * @var Container $container
     */
    protected $container;

    /**
     * __construct
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * updateDate
     * 
     * @return string
     */
    public function updateDate()
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $directory = $this->container->getParameter('project.web.dir') . '/uploads/dogovor/';

        echo 'find in '.$directory."\t\n";
        /** @var DogovorRepository $dogovorR */
        $dogovorR = $em->getRepository('ListsDogovorBundle:Dogovor')->findAll();

        /** @var DopDogovorRepository $dopDogovorR */
        $dopDogovorR = $em->getRepository('ListsDogovorBundle:DopDogovor')->findAll();

        $countErr = 0;
        $countOk = 0;
        foreach ($dogovorR as $dogovor) {
            if (is_file($directory . $dogovor->getFilepath())) {
                $countOk++;
                $date = date("d.m.Y H:i:s.", filemtime($directory . $dogovor->getFilepath()));
                $dogovor->setCreateDateTime(new \DateTime($date));
                $em->persist($dogovor);
                $em->flush();
            } else {
                $countErr++;
            }
        }
        foreach ($dopDogovorR as $dogovor) {
            if (is_file($directory . $dogovor->getFilepath())) {
                $countOk++;
                $date = date("d.m.Y H:i:s.", filemtime($directory . $dogovor->getFilepath()));
                $dogovor->setCreateDateTime(new \DateTime($date));
                $em->persist($dogovor);
                $em->flush();
            } else {
                $countErr++;
            }
        }

        return 'ok: ' . $countOk . ' error: ' . $countErr;
    }
}
