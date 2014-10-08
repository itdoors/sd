<?php

namespace ITDoors\HistoryBundle\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * HistoryInsert
 */
class HistoryInsert
{

    protected $container;

    /**
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    public function __construct (Container $container)
    {
        $this->container = $container;
    }
    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function postPersist (LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        $tables = $this->container->getParameter('listener.history.table');
        $tableName = $em->getClassMetadata(get_class($entity))->getTableName();
        if (in_array($tableName, $tables)) {
            $uow = $em->getUnitOfWork();
            $serviceHistory = $this->container->get('it_doors_history.service');
            $changeset = $uow->getEntityChangeSet($entity);
            $serviceHistory->add(
                array (
                    'modelId' => $entity->getId(),
                    'modelName' => $tableName,
                    'action' => 'insert',
                    'value' => (string) $entity,
                    'params' => json_encode($changeset)
                )
            );
        }
    }
}
