<?php

namespace Lists\OrganizationBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Lists\OrganizationBundle\Entity\Organization;
use SD\UserBundle\Entity\User;
use Lists\OrganizationBundle\Classes\OrganizationAccessFactory;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Lists\OrganizationBundle\Entity\BankCron;
use Lists\OrganizationBundle\Entity\Bank;
use Lists\OrganizationBundle\Entity\OrganizationCurrentAccount;
use Lists\OrganizationBundle\Entity\Currency;

/**
 * OrganizationService class
 */
class OrganizationService
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
     * checkAccess
     * 
     * @param User         $user
     * @param Organization $organization
     * 
     * @return mixed[]
     */
    public function checkAccess(User $user, Organization $organization = null)
    {
        $role = array();
        $role[] = 'base';
        if ($organization) {
            $managers = $organization->getOrganizationUsers();
            foreach ($managers as $manager) {
                $rol = $manager->getRole();
                if ($rol && $rol->getLukey() == 'manager_organization' && $manager->getUser() == $user) {
                    $role[] = 'managerOrganization';
                }
            }
        }
        if ($user->hasRole('ROLE_CONTROLLING_OPER')) {
            $role[] = 'controlling_oper';
        }
        if ($user->hasRole('ROLE_CONTROLLING')) {
            $role[] = 'controlling';
        }
        if ($user->hasRole('ROLE_SALES')) {
            $role[] = 'sales';
        }
        if ($user->hasRole('ROLE_SALESADMIN')) {
            $role[] = 'sales_admin';
        }
        if ($user->hasRole('ROLE_DOGOVORADMIN')) {
            $role[] = 'dogovor_admin';
        }
        if ($user->hasRole('ROLE_GOS_TENDER_ADMIN')) {
            $role[] = 'GosTenderAdmin';
        }
        if ($user->hasRole('ROLE_PAY_MASTER_CONTROLLING')) {
            $role[] = 'PayMasterControlling';
        }

        return OrganizationAccessFactory::createAccess($role);
    }
    /**
     * Add form defaults depending on defaults)
     *
     * @param Form    $form
     * @param mixed[] $defaults
      * 
      * @return Form $form
     */
    public function currentAccountFormDefaults(Form $form, $defaults)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $organizationId = (int) $defaults['organizationId'];
        $dorganization = $em->getRepository('ListsOrganizationBundle:Organization')->find($organizationId);

        $form
            ->add('organizationId', 'hidden', array(
                'mapped' => false,
                'data' => $organizationId
            ))
            ->add('organization', 'entity', array(
                'class'=>'Lists\OrganizationBundle\Entity\Organization',
                'empty_value' => '',
                'required' => true,
                'disabled' => true,
                'data' => $dorganization
            ));
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveCurrentAccountForm(Form $form, Request $request, $params)
    {
        $currentAccount = $form->getData();
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($currentAccount);
        $em->flush();
    }
    /**
     * parserFile
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return string
     */
    public function parserFile (InputInterface $input, OutputInterface $output)
    {
        $em = $this->container->get('doctrine')->getManager();
        $directory = $this->container->getParameter('1C.file.path.bank');
        if (!is_dir($directory)) {
            $this->addCronError(0, 'ok', 'directory not found', $directory);
            $output->writeln('Directory not found: '.$directory);

            return;
        }
        $file = $this->findFile($directory);

        if ($file && is_file($directory . $file)) {
            $str = trim(file_get_contents($directory . $file));
            if (substr($str, 0, 1) !== '{') {
                $str = substr($str, strpos($str, '{'));
            }
            $json = json_decode($str);
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    $this->addCronError(0, 'start parser', $file, 'parser file');

                    $this->savejson($input, $output, $json);
                    $this->addCronError(0, 'stop parser', $file, 'parser file');
                    $em->flush();
                    if (!is_dir($directory . 'old')) {
                        mkdir($directory . 'old', 0755);
                    }
                    rename($directory . $file, $directory . 'old/' . $file);
                    break;
                default:
                    echo 'Error json: ' . json_last_error();
                    $this->addCronError(0, 'FATAL ERROR', $file, json_last_error());
                    $em->flush();
            }
        } else {
            $this->addCronError(0, 'ok', 'file not found', 'new file not found');
            $em->flush();
            $output->writeln('Directory not found: '.$directory);

            return 'File not found in derictory ' . "\n" . $directory;
        }
    }
    /**
     * findFile
     * 
     * @param string $directory
     * 
     * @return boolean
     */
    private function findFile ($directory)
    {
        $fileName = false;

        $dh = opendir($directory);
        if ($dh) {
            while (($file = readdir($dh)) !== false) {
                if (filetype($directory . $file) == 'file') {
                    $fileName = $file;
                    continue;
                }
            }
            closedir($dh);
        }

        return $fileName;
    }
    /**
     * addCronError
     * 
     * @param Bank    $bank
     * @param string  $status
     * @param string  $reason
     * @param string  $descript
     * 
     * @return boolean
     */
    private function addCronError ($bank, $status, $reason, $descript)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $error = new BankCron();
        if ($bank) {
            $error->setBank($bank);
        }
        $error->setStatus($status);
        $error->setReason($reason);
        $error->setDescription($descript);
        $em->persist($error);

        return true;
    }
    /**
     * savejson
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param object          $json
     * 
     * @return boolen
     */
    private function savejson (InputInterface $input, OutputInterface $output, $json)
    {
        $countBank = 0;
        $em = $this->container->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        $banks = $json->banks;
        $count = count($banks);
        foreach ($banks as $key => $bank) {

            if ($countBank == 1000) {
                $em->flush();
                $em->clear();
                $countBank = 0;
            }
            ++$countBank;

            $output->writeln(
                $countBank." ~ ".number_format(memory_get_usage()/8000000, 0, ',', ' ')."MB ~ more: ".($count - $key)
            );
            $this->saveBank($input, $output, $bank);

            $banks[$key] = null;
            unset($banks[$key]);
        }
        $output->writeln('Banks end');
        $countAcc = 0;
        $accLists = $json->accLists;
        $countAccAll = count($accLists);
        foreach ($accLists as $key => $acc) {

            if ($countAcc == 1000) {
                $em->flush();
                $em->clear();
                $countAcc = 0;
            }
            ++$countAcc;

            $output->writeln(
                $countAcc." ~ ".number_format(memory_get_usage()/8000000, 0, ',', ' ').
                "MB ~ more: ".($countAccAll - $key)
            );
            $this->saveAcc($input, $output, $acc);

            $accLists[$key] = null;
            unset($accLists[$key]);
        }
        $output->writeln('AccLists end');
        $output->writeln('Cron successfully');
    }
    /**
     * saveBank
     * 
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param object          $bank
     * 
     * @return Bank
     */
    private function saveBank (InputInterface $input, OutputInterface $output, $bank)
    {
        $em = $this->container->get('doctrine')->getManager();
        $object = $em->getRepository('ListsOrganizationBundle:Bank')->findOneBy(array ('guid' => trim($bank->guid)));
        if (!$object) {
            $output->writeln(' - guid: "'.trim($bank->guid).'" - not found');
            $object = $em->getRepository('ListsOrganizationBundle:Bank')
                ->findOneBy(array (
                'name' => trim($bank->name),
                'mfo' => trim($bank->mfo)
            ));
            if ($object) {
                $output->writeln(' - guid: "'.trim($bank->guid).'" - update old bank');
                $object->setGuid(trim($bank->guid));
            }
        }
        if (!$object) {
            $output->writeln(' - guid: "'.trim($bank->guid).'" -create new');
            $object = new Bank();
            $object->setGuid(trim($bank->guid));
        }
        $object->setName(trim($bank->name));
        $object->setMfo(trim($bank->mfo));
        $em->persist($object);

        return $object;
    }
    /**
     * saveAcc
     * 
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param object          $acc
     */
    private function saveAcc (InputInterface $input, OutputInterface $output, $acc)
    {
        $em = $this->container->get('doctrine')->getManager();
        $bank = $em->getRepository('ListsOrganizationBundle:Bank')->findOneBy(array ('guid' => trim($acc->bankGuid)));
        $organization = $em->getRepository('ListsOrganizationBundle:Organization')
            ->findOneBy(array ('edrpou' => trim($acc->edrpou)));
        $type = $em->getRepository('ListsOrganizationBundle:OrganizationCurrentAccountType')->find(2);
        $currency = $em->getRepository('ListsOrganizationBundle:Currency')
            ->findOneBy(array ('code' => trim($acc->currencyCode)));
        $currentAccount = $em->getRepository('ListsOrganizationBundle:OrganizationCurrentAccount')
            ->findOneBy(array (
                'currency' => $currency,
                'bank' => $bank,
                'organization' => $organization,
                'typeAccount' => $type,
                'name' => $acc->accNumber
            ));
        if (!$currency) {
            $output->writeln('currency - code: "'.trim($acc->currencyCode).'" - create new');
            $currency = new Currency();
            $currency->setCode(trim($acc->currencyCode));
            $currency->setShortName(trim($acc->currencyName));
            $em->persist($currency);
            $em->flush();
        }
        if (!$bank) {
            $output->writeln('Bank - guid: "'.trim($acc->bankGuid).'" - not found');
        }
        if (!$organization) {
            $output->writeln('Organization - edrpou: "'.trim($acc->edrpou).'" - not found');
        }
        if ($bank && $organization && !$currentAccount) {
            $output->writeln('Add current account - number: "'.trim($acc->accNumber).'" - create new');
            $object = new OrganizationCurrentAccount();
            $object->setCurrency($currency);
            $object->setBank($bank);
            $object->setOrganization($organization);
            $object->setName($acc->accNumber);
            $object->setTypeAccount($type);
            $em->persist($object);
            $em->flush();
        }
    }
}
