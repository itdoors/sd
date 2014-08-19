<?php

namespace ITDoors\ControllingBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use ITDoors\ControllingBundle\Entity\Invoicecron;
use ITDoors\ControllingBundle\Entity\InvoicecronRepository;
use ITDoors\ControllingBundle\Entity\Invoice;
use ITDoors\ControllingBundle\Entity\InvoicePayments;
use ITDoors\ControllingBundle\Entity\InvoiceMessage;
use ITDoors\ControllingBundle\Entity\InvoiceAct;
use ITDoors\ControllingBundle\Entity\InvoiceActDetal;

/**
 * Invoice Service class
 */
class InvoiceService
{

    /**
     * @var Container $container
     */
    protected $container;

    protected $messageTemplate;

    protected $arrCostumersForSendMessages;
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
     * findFile
     * 
     * @param string $directory
     * 
     * @return boolean
     */
    private function findFile($directory)
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
     * @return string
     */
    public function parserFile()
    {
        $directory = $this->container->getParameter('1C.file.path');

        if (!is_dir($directory)) {
            $this->addCronError(0, 'ok', 'directory not found', $directory);
            echo 'Directory not found: ';

            return $directory;
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
                    $this->arrCostumersForSendMessages = array();
                    $em = $this->container->get('doctrine')->getManager();
                    $this->savejson($json->invoice);
                    $em->flush();
                    $this->addCronError(0, 'stop parser', $file, 'parser file');
                    if (!is_dir($directory.'old')) {
                        mkdir($directory.'old', 0700);
                    }
                    rename($directory.$file, $directory.'old/'.$file);
                    break;
                default:
                    echo json_last_error();
                    $this->addCronError(0, 'FATAL ERROR', $file, json_last_error());
            }
        } else {
            $this->addCronError(0, 'ok', 'file not found', 'new file not found');

            $em->flush();

            return 'File not found in derictory '."\n".$directory;
        }
    }

    /**
     * saveinvoice
     * 
     * @param object $invoice
     * @param object $em
     * 
     * @return Invoice|boolean
     */
    private function saveinvoice($invoice)
    {
        $invoice = $invoice;
        $em = $this->container->get('doctrine')->getManager();
        $invoiceNew = $em->getRepository('ITDoorsControllingBundle:Invoice')
            ->findOneBy(array('invoiceId' => trim($invoice->invoiceId)));
        if (!$invoiceNew) {
            $invoiceNew = new Invoice();
            $invoiceNew->setCourt(0);
            $invoiceNew->setInvoiceId(trim($invoice->invoiceId));

            if (count($invoice->payments) > 0 ) {
                $summa = 0;
                $dateFact = null;
                foreach ($invoice->payments as $pay) {
                    $dateFact = new \DateTime(trim($pay->date));
                    $payments = new InvoicePayments();
                    $payments->setInvoice($invoiceNew);
                    $payments->setDate($dateFact);
                    $payments->setSumma(trim($pay->summa));
                    if ($pay->bank != 'null') {
                        $payments->setBank(trim($pay->bank));
                    }
                    $em->persist($payments);
                    $summa += trim($pay->summa);
                }

                if ($summa >= $invoice->sum && $dateFact != null) {
                    $invoiceNew->setDateFact($dateFact);
                }
            } else {
                $this->messageTemplate = 'invoice-not-pay';
            }
        } else {
            if (count($invoice->payments) > 0) {
                $paymentsOld = $em->getRepository('ITDoorsControllingBundle:InvoicePayments')->findBy(array('invoiceId' => $invoiceNew->getId()));
                foreach ($paymentsOld as $payOld) {
                    $em->remove($payOld);
                }
                $summa = 0;
                $dateFact = null;
                $sendEmailPay = false;
                $date = date('Y-m-d');
                foreach ($invoice->payments as $pay) {
                    $dateFact = new \DateTime(trim($pay->date));
                    $payments = new InvoicePayments();
                    $payments->setInvoice($invoiceNew);
                    $payments->setDate($dateFact);
                    $payments->setSumma(trim($pay->summa));
                    if ($pay->bank != 'null') {
                        $payments->setBank(trim($pay->bank));
                    }
                    $em->persist($payments);
                    $summa += trim($pay->summa);

                    $days = (strtotime($date)-strtotime($pay->date))/24/3600;

                    if (in_array($days, array(1))) {
                       $sendEmailPay = true;
                    }
                }
                if ($sendEmailPay) {
                    $this->messageTemplate = 'invoice-pay';
                }

                if ($summa >= $invoice->sum && $dateFact != null) {
                    $invoiceNew->setDateFact($dateFact);
                }
            } else {
                $date = date('Y-m-d');
                $invoiceNew->setDateFact(null);
                $days = (strtotime($date)-strtotime($invoiceNew->getDelayDate()->format('Y-m-d')))/24/3600;
                if (in_array($days, array(1))) {
                    $this->messageTemplate = 'invoice-not-pay';
                }
                $paymentsOld = $em->getRepository('ITDoorsControllingBundle:InvoicePayments')->findBy(array('invoiceId' => $invoiceNew->getId()));
                foreach ($paymentsOld as $payOld) {
                    $em->remove($payOld);
                }
            }
        }
        $invoiceNew->setDogovorGuid(trim($invoice->dogovorGuid));
        $invoiceNew->setDogovorNumber(trim($invoice->dogovorNumber));
        $invoiceNew->setDogovorName(trim($invoice->dogovorName));
        if ($invoice->bank != 'null') {
            $invoiceNew->setBank(trim($invoice->bank));
        }
        $invoiceNew->setCustomerName(trim($invoice->customerName));
        $invoiceNew->setCustomerEdrpou(trim($invoice->customerEdrpou));
        $invoiceNew->setPerformerName(trim($invoice->performerName));
        $invoiceNew->setPerformerEdrpou(trim($invoice->performerEdrpou));
        $acts = $invoice->acts;
        $this->addActs($invoiceNew, $acts);
       // $invoiceNew->setDogovorAct(json_encode($invoice->dogovorAct));

        if (!empty($invoice->delayDate) && $invoice->delayDate != 'null') {
            $invoiceNew->setDelayDate(new \DateTime(trim($invoice->delayDate)));
        }
        if (is_numeric($invoice->delayDays)) {
            $invoiceNew->setDelayDays((int) $invoice->delayDays);
        }
        if (in_array($invoice->delayDaysType, array('Б', 'К', 'б', 'к'))) {
            $invoiceNew->setDelayDaysType(trim($invoice->delayDaysType));
        }
        if (!empty($invoice->dogovorDate) && $invoice->dogovorDate != 'null') {
            $invoiceNew->setDogovorDate(new \DateTime(trim($invoice->dogovorDate)));
        }
        if (!empty($invoice->date) && $invoice->date != 'null') {
            $invoiceNew->setDate(new \DateTime(trim($invoice->date)));
        } else {
            $this->addCronError(false, 'error data', 'date', json_encode($invoice));

            return false;
        }
        if (is_numeric($invoice->sum)) {
            $invoiceNew->setSum(trim($invoice->sum));
        } else {
            $this->addCronError(false, 'error data', 'sum', json_encode($invoice));

            return false;
        }

        return $invoiceNew;
    }

    private function addActs($invoiceNew, $acts)
    {
        $em = $this->container->get('doctrine')->getManager();
        foreach ($acts as $act) {
            $actFind = $em->getRepository('ITDoorsControllingBundle:InvoiceAct')
                    ->findOneBy(array('number' => $act->actNumber));
            if (!$actFind) {
                $actFind = new InvoiceAct();
                $actFind->setNumber($act->actNumber);
                $actFind->setInvoice($invoiceNew);
            }
            $actFind->setDate(new \DateTime(trim($act->actDate)));
            $actFind->setOriginal($act->actOriginal);
            $em->persist($actFind);
            $details = $act->actDetail;
            $detailsFind = $em->getRepository('ITDoorsControllingBundle:InvoiceActDetal')
                ->findBy(array('invoiceActId' => $actFind->getId()));
            foreach ($detailsFind as $detailsFindOne) {
                $em->remove($detailsFindOne);
            }
            foreach ($details as $detail) {
                 $detalAdd = new InvoiceActDetal();
                 $detalAdd->setAct($actFind);
                 $detalAdd->setCount($detail->count);
                 $detalAdd->setMpk($detail->mpk);
                 $detalAdd->setNote($detail->note);
                 $detalAdd->setSumma($detail->summa);
                 $em->persist($detalAdd);
            }
        }
    }
    private function findDogovor($invoiceFind, $invoice, $invoiceNew)
    {
        $em = $this->container->get('doctrine')->getManager();

        /** @var Dogovor  $dogovorfind */
        $dogovorfind = $em->getRepository('ListsDogovorBundle:Dogovor')
            ->findOneBy(array('dogovorGuid' => trim($invoice->dogovorGuid)));

        /** @var Organization  $customerfind */
        $customerfind = $em->getRepository('ListsOrganizationBundle:Organization')
            ->findOneBy(array('edrpou' => trim($invoice->customerEdrpou)));

        /** @var Organization  $performerfind */
        $performerfind = $em->getRepository('ListsOrganizationBundle:Organization')
            ->findOneBy(array('edrpou' => trim($invoice->performerEdrpou)));

        if ($customerfind) {
            $invoiceNew->setCustomer($customerfind);
            if (!array_key_exists($customerfind->getId(), $this->arrCostumersForSendMessages)) {
                $this->arrCostumersForSendMessages[$customerfind->getId()] = array();
            }
            if ($this->messageTemplate && !array_key_exists($this->messageTemplate, $this->arrCostumersForSendMessages[$customerfind->getId()])) {
                $this->arrCostumersForSendMessages[$customerfind->getId()][$this->messageTemplate]  = array();
            }
        }
        if ($performerfind) {
            $invoiceNew->setPerformer($performerfind);
        }

        if ($dogovorfind) {
            $invoiceNew->setDogovor($dogovorfind);
            $em->persist($invoiceNew);
            //$em->flush();
            if (!$invoiceFind && $invoiceNew->getDogovorId()) {
                $this->addReaspon($invoiceNew);
            }
        } else {
            if ($customerfind && $performerfind) {

                /** @var Dogovor  $dogovoradd */
                $dogovoradd = $em->getRepository('ListsDogovorBundle:Dogovor')
                    ->findOneBy(array(
                    'number' => $invoice->dogovorNumber,
                    'startdatetime' => new \DateTime($invoice->dogovorDate),
                    'customerId' => $customerfind->getId(),
                    'performerId' => $performerfind->getId()
                ));

                if ($dogovoradd) {

                    // подтвердить связь и 1С
                    $dogovoradd->setDogovorGuid(trim($invoice->dogovorGuid));
                    $em->persist($dogovoradd);

                    $invoiceNew->setDogovor($dogovoradd);
                    $em->persist($invoiceNew);
                    //$em->flush();
                    //$em->refresh($invoiceNew);

                    $this->addCronError(
                        $invoiceNew,
                        'join dogovor and 1c',
                        'add dogovorGuid ' . $dogovoradd->getId(),
                        json_encode($invoice)
                    );

                    if (!$invoiceFind) {
                        $this->addReaspon($invoiceNew);
                    }
                } else {
                    $em->persist($invoiceNew);
                    //$em->flush();
                    //$em->refresh($invoiceNew);
                    $this->addCronError($invoiceNew, 'error', 'dogovor not found', json_encode($invoice));

                    return false;
                }
            } else {

                if (!$customerfind && $performerfind) {
                    $this->messageTemplate = false;
                    $error = 'dogovor not found and customer';
                } elseif (!$performerfind && $customerfind) {
                    $error = 'dogovor not found and performer';
                } else {
                    $this->messageTemplate = false;
                    $error = 'dogovor not found and customer and  performer';
                }
                $em->persist($invoiceNew);
//                $em->flush();
//                $em->refresh($invoiceNew);
                $this->addCronError($invoiceNew, 'error', $error, json_encode($invoice));
            }
        }
        if ($this->messageTemplate && $invoiceNew->getDogovorNumber() && method_exists($customerfind, 'getId')) {
            if (!array_key_exists($invoiceNew->getDogovorNumber(), $this->arrCostumersForSendMessages[$customerfind->getId()][$this->messageTemplate])) {
                $this->arrCostumersForSendMessages[$invoiceNew->getCustomer()->getId()][$this->messageTemplate][$invoiceNew->getDogovorNumber()] = array();
            }
            $this->arrCostumersForSendMessages[$invoiceNew->getCustomer()->getId()][$this->messageTemplate][$invoiceNew->getDogovorNumber()][] = $invoiceNew->getId();
        }
    }

    /**
     * savejson
     *
     * @param object $json Description
     * 
     * @return boolen
     */
    private function savejson($json)
    {
        $count = count($json);
        foreach ($json as $key => $invoice) {
            echo ($count-$key)."\n";
            $invoiceFind = true;
            $this->messageTemplate = false;
            $invoiceNew = $this->saveinvoice($invoice);

            if (!$invoiceNew) {
                unset($json[$key]);
                continue;
            } elseif ($invoiceNew->getId()) {
                $invoiceFind = false;
            }

            $this->findDogovor($invoiceFind, $invoice, $invoiceNew);

            unset($json[$key]);
        }
        echo 'try add email for send'."\n";
        $this->sendEmails();
        echo 'try add cron for send email'."\n";
        $cron = $this->container->get('it_doors_cron.service');
        $cron->addSendEmails();
        echo 'cron successfully'."\n";
    }

    /**
     * addReaspon
     * 
     * @param Invoice $invoice
     */
    private function addReaspon(Invoice $invoice)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $companystructs = $em->getRepository('ListsDogovorBundle:DogovorCompanystructure')
            ->findBy(array('dogovorId' => $invoice->getDogovorId()));

        foreach ($companystructs as $company) {

            $invoicecompany = new InvoiceCompanystructure();
            $invoicecompany->setInvoice($invoice);
            $invoicecompany->setCompanystructure($company->getCompanyStructures());

            $em->persist($invoicecompany);
            //$em->flush();
        }
    }

    /**
     * addCronError
     * 
     * @param Invoice $invoice
     * @param string  $status
     * @param string  $reason
     * @param string  $descript
     * 
     * @return boolean
     */
    private function addCronError($invoice, $status, $reason, $descript)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $error = new Invoicecron();
        $error->setDate(new \DateTime());
        if ($invoice) {
            $error->setInvoice($invoice);
        }
        $error->setStatus($status);
        $error->setReason($reason);
        $error->setDescription($descript);
        $em->persist($error);
        //$em->flush();

        return true;
    }

    /**
     * Returns results for interval future invoice
     *
     * @var Container
     * 
     * @return array
     */
    public function getTabsInvoice()
    {
        $translator = $this->container->get('translator');
        $tabs = array();
        $tabs['act'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'act',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Act')
        );
        $tabs['invoice'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'invoice',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Invoice')
        );
        $tabs['contacts'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'contacts',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Contacts client')
        );
        $tabs['dogovor'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'dogovor',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Dogovor')
        );
        $tabs['responsible'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'responsible',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Responsible')
        );
        $tabs['organization'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'customer',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Customer')
        );
        $tabs['payments'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'payments',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Payments')
        );

        return $tabs;
    }

    /**
     * Returns results for interval future invoice
     *
     * @var Container
     * 
     * @return array
     */
    public function getTabsExpectedPay()
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var InvoicecronRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');

        $summa = $invoice->getSumma(date('Y-m-d'));

        $translator = $this->container->get('translator');
        $tabs = array();
        $tabs['today'] = array(
            'blockupdate' => 'ajax-tab-holder-2',
            'tab' => 'today',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_pay_show'),
            'text' => $translator->trans('Today') . ' ' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getSumma(date('Y-m-d', mktime(0, 0, 0, date("m"), date('d') + 1, date('Y'))));
        $tabs['tomorrow'] = array(
            'blockupdate' => 'ajax-tab-holder-2',
            'tab' => 'tomorrow',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_pay_show'),
            'text' => $translator->trans('Tomorrow') . ' ' . number_format($summa[0]['summa'], 2, ',', ' ')
        );

        return $tabs;
    }
    /**
     * Returns results for interval future invoice
     *
     * @var Container
     * 
     * @return array
     */
    public function getTabsInvoiceGrafics()
    {
        $translator = $this->container->get('translator');
        $tabs = array();
        $tabs['general'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'general',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_grafic_general'),
            'text' => $translator->trans('General')
        );
        $tabs['withoutacts'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'withoutacts',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_grafic_withoutacts'),
            'text' => $translator->trans('Without acts')
        );
        $tabs['individual'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'individual',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_grafic_individual', array('ajax' => 'true')),
            'text' => $translator->trans('Individual')
        );

        return $tabs;
    }
    /**
     * Returns results for interval future invoice
     *
     * @var Container
     * 
     * @return array
     */
    public function getTabsEmptyData()
    {
        $translator = $this->container->get('translator');
        $tabs = array();
        $tabs['delay'] = array(
            'blockupdate' => 'ajax-tab-holder-1',
            'tab' => 'delay',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_data_show'),
            'text' => $translator->trans('Delay')
        );
        $tabs['act'] = array(
            'blockupdate' => 'ajax-tab-holder-1',
            'tab' => 'act',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_data_show'),
            'text' => $translator->trans('Existence act')
        );

        return $tabs;
    }

    /**
     * Returns results for interval future invoice
     *
     * @var Container
     * 
     * @param integer $companystryctyre
     * @param array   $filters
     * 
     * @return tabs[]
     */
    public function getTabsInvoices($companystryctyre, $filters = null)
    {
        $translator = $this->container->get('translator');
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var InvoicecronRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');

        $summa = $invoice->getInvoicePeriodSum(1, 30, $companystryctyre, $filters);
        $tabs[] = array(
            'tab' => 30,
            'blockupdate' => 'ajax-tab-holder',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('from') . ' 1 ' . $translator->trans('to') . ' 30 ' . $translator->trans('day')
             .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePeriodSum(31, 60, $companystryctyre, $filters);
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 60,
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 31 ' . $translator->trans('to') . ' 60 ' . $translator->trans('days')
            .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePeriodSum(61, 120, $companystryctyre, $filters);
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 120,
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 61 ' . $translator->trans('to') . ' 120 ' . $translator->trans('days')
            .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePeriodSum(121, 180, $companystryctyre, $filters);
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 180,
            'class' => '',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 121 ' . $translator->trans('to') . ' 180 ' . $translator->trans('days')
            .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePeriodSum(180, 0, $companystryctyre, $filters);
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 181,
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 181 ' . $translator->trans('days')
            .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoiceCourtSum($companystryctyre, $filters);
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'court',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('court')
            .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePaySum($companystryctyre, $filters);
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'pay',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('pay')
            .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoiceFlowSum($companystryctyre, $filters);
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'flow',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('Flow')
            .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );

        return $tabs;
    }
     /**
     *  sendEmails
     */
    private function sendEmails()
    {
        if (count($this->arrCostumersForSendMessages) > 0) {

            $em = $this->container->get('doctrine')->getManager();

            /** @var Translator $translator */
            $translator = $this->container->get('translator');

            /** @var User  $userRobot */
            $userRobot = $em->getRepository('SDUserBundle:User')->find(0);

            $emailTo = $this->container->getParameter('email.from');
            $nameTo = $this->container->getParameter('name.from');

            $email = $this->container->get('it_doors_email.service');

            foreach ($this->arrCostumersForSendMessages as $customerId => $templates) {
                /** @var ModelContactRepository  $contacts */
                $contacts = $em->getRepository('ListsContactBundle:ModelContact')
                    ->getUsersForSendEmail($customerId);

                foreach ($templates as $template => $dogovors) {
                    foreach ($dogovors as $invoiceIds) {
                        /** @var InvoiceRepository  $invoices */
                        $invoices = $em->getRepository('ITDoorsControllingBundle:Invoice')
                            ->getInvoiceIds($invoiceIds);

                        $tableHTML =  $this->container->get('templating')->render("ITDoorsControllingBundle:Invoice:tableForEmail.html.twig", array(
                        'invoices' => $invoices,
                        'template' => $template
                    ));

                        foreach ($contacts as $user) {
                            if (empty($user['email'])) {
                                continue;
                            }
                            echo "send email for ".$user['email']."\n\n";
                            $idEmail = $email->send(
                                array($emailTo => $nameTo),
                                $template,
                                array(
                                    'users' => array(
                                        $user['email']
                                    ),
                                    'variables' => array(
                                        '${lastName}$' => $user['lastName'],
                                        '${firstName}$' => $user['firstName'],
                                        '${middleName}$' => $user['middleName'],
                                        '${number}$' => $invoices[0]['dogovorNumber'],
                                        '${date}$' => (!$invoices[0]['dogovorDate'] ? '' : $invoices[0]['dogovorDate']->format('d.m.Y')),
                                        '${performer}$' => $invoices[0]['performerName'],
                                        '${table}$' => $tableHTML
                                    )
                                )
                            );
                            /** @var ModelContact  $modelContact */
                            $modelContact = $em->getRepository('ListsContactBundle:ModelContact')->find($user['id']);
                            foreach ($invoices as $invoice) {
                                $message = new InvoiceMessage();
                                $message->setContact($modelContact);
                                $message->setUser($userRobot);
                                $message->setCreatedate(new \DateTime());
                                $message->setNote($translator->trans('Send', array(), 'ITDoorsControllingBundle').' <a href="'.$this->container->get('router')->generate('automailer_show', array('id' =>$idEmail)).'">email</a>');
                                $invoicef = $em->getRepository('ITDoorsControllingBundle:Invoice')->find($invoice['id']);
                                $message->setInvoice($invoicef);
                                $em->persist($message);
                                //$em->flush();
                            }
                        }
                    }
                }
            }
        }
    }
}