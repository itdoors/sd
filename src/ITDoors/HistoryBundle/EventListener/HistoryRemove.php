<?php
namespace ITDoors\HistoryBundle\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * HistoryRemove
 */
class HistoryRemove
{
    protected $container;

    /**
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        $tables = $this->container->getParameter('listener.history.table');
        $tableName = $em->getClassMetadata(get_class($entity))->getTableName();
        if (in_array($tableName, $tables)) {
            $serviceHistory= $this->container->get('it_doors_history.service');
            $serviceHistory->add(
                array(
                    'modelId' => $entity->getId(),
                    'modelName' => $tableName,
                    'action' => 'delete'
                )
            );
        }
    }
}