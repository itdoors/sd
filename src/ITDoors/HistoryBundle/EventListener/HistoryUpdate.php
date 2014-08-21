<?php
namespace ITDoors\HistoryBundle\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * HistoryUpdate
 */
class HistoryUpdate
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
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        $tableName = $em->getClassMetadata(get_class($entity))->getTableName();
        $tables = $this->container->getParameter('listener.history.table');
        if (in_array($tableName, $tables)) {
            $uow = $em->getUnitOfWork();
            $serviceHistory= $this->container->get('it_doors_history.service');
            $changeset = $uow->getEntityChangeSet($entity);
            $keys = array_keys($changeset);
            foreach ($keys as $key) {
                $oldValue = $args->getOldValue($key);
                $value = $args->getNewValue($key);
                if (gettype($oldValue) ==  'object') {
                    if (get_class($oldValue) == 'DateTime') {
                        $oldValue = $oldValue->format('Y-m-d H:i:s');
                    } else if (method_exists($oldValue, 'getId')) {
                        $oldValue = $oldValue->getId();
                    }
                }
                if (gettype($value) ==  'object') {
                    if (get_class($value) == 'DateTime') {
                        $value = $value->format('Y-m-d H:i:s');
                    } else if (method_exists($value, 'getId')) {
                        $value = $value->getId();
                    }
                }
                if (!empty($oldValue) || !empty($value)) {
                    $serviceHistory->add(
                        array(
                            'modelId' => $entity->getId(),
                            'modelName' => $tableName,
                            'fieldName' => $key,
                            'oldValue' => $oldValue,
                            'value' => $value,
                            'action' => 'update'
                        )
                    );
                }
            }
        }
    }
}