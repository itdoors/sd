<?php
namespace ITDoors\SipBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Lists\HandlingBundle\Entity\HandlingMessage;
use SD\UserBundle\Entity\User;
use Doctrine\Common\EventManager;
use ITDoors\SipBundle\Entity\Call;
use Lists\ContactBundle\Entity\ModelContact;

/**
 * Class AccessService
 */
class SipService
{
    /** @var Container $container */
    protected $container;

    /**
     * __construct
     *
     * @param Container $container
     */
    public function __construct (Container $container)
    {
        $this->container = $container;
    }

    public function saveCall(InputInterface $input,  OutputInterface $output)
    {
        $em = $this->container->get('doctrine')->getManager();
        
        $caller = $em->getRepository('SDUserBundle:User')->findOneBy(array(
            'peerId' => $input->getOption('callerId')
        ));
       
        $receiver = $em->getRepository('ListsContactBundle:ModelContact')
            ->createQueryBuilder('mc')
            ->select('mc')
            ->where('mc.phone1 = :phone OR mc.phone2 = :phone')
            ->setParameter(':phone', $input->getOption('receiverId'))
            ->getQuery()
            ->getResult();
        $modelName = $input->getOption('modelName');
        if ($caller && $receiver) {
            $call = new Call();
            $call->setCaller($caller);
            $call->setDestuniqueId($input->getOption('destuniqueId'));
            $call->setDuration($input->getOption('answeredTime'));
            $call->setFileName($input->getOption('filename'));
            $method = 'save'.ucfirst($modelName).'Message';
            if (method_exists($this, $method)) {
                $model = $this->$method($receiver[0], $caller, $input->getOption('modelId'));
                $call->setModelId($model['modelId']);
                $call->setModelName($model['modelName']);
            } else {
                $call->setModelId($input->getOption('modelId'));
                $call->setModelName($modelName);
            }
            $call->setPeerId($input->getOption('callerId'));
            $call->setPhone($input->getOption('receiverId'));
            $call->setProxyId($input->getOption('proxyId'));
            $call->setReceiverId($receiver[0]->getId());
            $call->setStatus($input->getOption('dialStatus'));
            $call->setUniqueId($input->getOption('uniqueId'));
            $call->setDatetime(new \DateTime());
            $em->persist($call);
            $em->flush();
        }else {
            $output->writeln("!!! User not found !!!");
        }
    }
    public function saveHandlingMessage(ModelContact $receiver, User $caller, $modelId)
    {
        /*@var EventManager $em */
        $em = $this->container->get('doctrine')->getManager();
        $result = array();
        $type = $em->getRepository('ListsHandlingBundle:HandlingMessageType')->find(1);
        $handling = $em->getRepository('ListsHandlingBundle:Handling')->find($modelId);

        if ($type && $handling) {
            $handlingMessage = new HandlingMessage();
            $handlingMessage->setType($type);
            $handlingMessage->setHandling($handling);
            $handlingMessage->setUser($caller);
            $handlingMessage->setContact($receiver);
            $handlingMessage->setCreatedate(new \DateTime());
            $em->persist($handlingMessage);
            $em->flush();
            $result['modelId'] = $handlingMessage->getId();
            $result['modelName'] = $em->getClassMetadata(get_class($handlingMessage))->getTableName();
        }
        
        return $result;
    }
}
