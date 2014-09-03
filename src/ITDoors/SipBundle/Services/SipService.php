<?php
namespace ITDoors\SipBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Console\Input\InputInterface;

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

    public function saveCall(InputInterface $input)
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
        if ($receiver && $receiver) {
            $call = new Call();
            $call->setCaller($caller);
            $call->setDestuniqueId($input->getOption('destuniqueId'));
            $call->setDuration($input->getOption('answeredTime'));
            $call->setFileName($input->getOption('filename').'.mp3');
            $call->setModelId('1');//$input->getOption('modelId');
            $call->setModelName('sdf');//$input->getOption('modelName');
            $call->setPeerId($input->getOption('callerId'));
            $call->setPhone($input->getOption('receiverId'));
            $call->setProxyId($input->getOption('proxyId'));
            $call->setReceiver($receiver);
            $call->setStatus($input->getOption('dialStatus'));
            $call->setUniqueId($input->getOption('uniqueId'));
            $em->persist($call);
            $em->flush();
        }else {
            echo "!!! User not found !!!\n";
        }
    }
    public function saveHandlingMessage()
    {
        
    }
    public function saveInvoiceMessage()
    {
        
    }
}
