<?php

/**
 * Command class for deleting handling
 */
namespace Lists\ProjectBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;
use Lists\ProjectBundle\Entity\ServiceСommercialTender;
use Lists\ProjectBundle\Entity\ServiceStateTender;
use Lists\ProjectBundle\Entity\StatusStateTender;
use Lists\ProjectBundle\Entity\StatusСommercialTender;

/**
 * Class MigrationCommand
 */
class MigrationCommand extends ContainerAwareCommand
{
    /**
    * @var \Doctrine\ORM\EntityManager $em
    */
    protected $em;

    /**
     * @var Connection $connection
     */
    protected $connection;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
          ->setName('lists:project:migration')
          ->setDescription('Mifration handling to project');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('START');
        /** @var \Doctrine\ORM\EntityManager $em */
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // перенос сервисов
        $services = $this->em->getRepository('ListsHandlingBundle:HandlingService')->findAll();
        foreach ($services as $service) {
            $servicesNew = $this->em->getRepository('ListsProjectBundle:Service')->findOneBy(
                array(
                    'name' => $service->getName()
                ));
            if (!$servicesNew && $service->getSlug() == 'project') {
                $servicesNew = new ServiceСommercialTender();
                $servicesNew->setName($service->getName());
                $servicesNew->setReportNumber($service->getReportNumber());
                $servicesNew->setSlug($service->getSlug());
                $servicesNew->setSortorder($service->getSortorder());
                $this->em->persist($servicesNew);
            }
            if (!$servicesNew && $service->getSlug() == 'gos_tender') {
                $servicesNew = new ServiceStateTender();
                $servicesNew->setName($service->getName());
                $servicesNew->setReportNumber($service->getReportNumber());
                $servicesNew->setSlug($service->getSlug());
                $servicesNew->setSortorder($service->getSortorder());
                $this->em->persist($servicesNew);
            }
        }
        $output->writeln('END SERVICE');

        // перенос статусов
        $status = $this->em->getRepository('ListsHandlingBundle:HandlingStatus')->findAll();
        foreach ($status as $val) {
            $statusNew = $this->em->getRepository('ListsProjectBundle:Status')->findOneBy(
                array(
                    'name' => $val->getName(),
                    'alias' => $val->getAlias()
                ));
            if (!$statusNew) {
                if ($val->getSlug() == 'gos_tender') {
                    $statusNew = new StatusStateTender();
                } else {
                    $statusNew = new StatusСommercialTender();
                }
                $statusNew->setName($val->getName());
                $statusNew->setAlias($val->getAlias());
                $this->em->persist($statusNew);
            }
        }
        $output->writeln('END STATUS');
        
        $this->em->flush();
        $output->writeln('END');
    }
}
