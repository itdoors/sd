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
use ITDoors\ControllingBundle\Entity\InvoiceCompanystructure;

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
    protected $updateDatetime;
    /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';

    /**
     * __construct
     *
     * @param Container $container
     */
    public function __construct (Container $container)
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
     * @return string
     */
    public function parserFile ()
    {
        $this->updateDatetime = new \DateTime();
        $this->updateIvoiceInfo();
        $em = $this->container->get('doctrine')->getManager();
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

                    $this->savejson($json->invoice);
                    $this->addCronError(0, 'stop parser', $file, 'parser file');
                    $em->flush();
                    if (!is_dir($directory . 'old')) {
                        mkdir($directory . 'old', 0700);
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

            return 'File not found in derictory ' . "\n" . $directory;
        }
    }
    /**
     * saveinvoice
     * 
     * @param object $invoice
     * 
     * @return Invoice|boolean
     */
    private function saveinvoice ($invoice)
    {
        $em = $this->container->get('doctrine')->getManager();
        $invoiceNew = $em->getRepository('ITDoorsControllingBundle:Invoice')
            ->findOneBy(array (
                'guid' => trim($invoice->guid)
            ));
        if (!$invoiceNew) {
            echo ' - guid: "'.trim($invoice->guid).'" - not found ';
            $invoiceNew = $em->getRepository('ITDoorsControllingBundle:Invoice')
                ->findOneBy(array (
                'invoiceId' => trim($invoice->invoiceId),
                'date' => new \DateTime(trim($invoice->date))
            ));
            if ($invoiceNew) {
                echo ' - guid: "'.trim($invoice->guid).'" - update old invoice ';
                $invoiceNew->setGuid(trim($invoice->guid));
            }
        }
        $summaPaymens = 0;
        $dateFact = new \DateTime(trim($invoice->date));
        if (!$invoiceNew) {
            echo ' - guid: "'.trim($invoice->guid).'" - create new ';
            $invoiceNew = new Invoice();
            $invoiceNew->setCourt(0);
            $invoiceNew->setGuid(trim($invoice->guid));
            $invoiceNew->setInvoiceId(trim($invoice->invoiceId));

            if (count($invoice->payments) > 0) {
                foreach ($invoice->payments as $pay) {
                    $date = new \DateTime(trim($pay->date));
                    if ($dateFact->format('U') < $date->format('U')) {
                        $dateFact = $date;
                    }
                    $payments = new InvoicePayments();
                    $payments->setInvoice($invoiceNew);
                    $payments->setDate($date);
                    $payments->setSumma(trim($pay->summa));
                    if ($pay->bank != 'null') {
                        $payments->setBank(trim($pay->bank));
                    }
                    $em->persist($payments);
                    $summaPaymens += $pay->summa;
                }
            } else {
                $this->messageTemplate = 'invoice-not-pay';
            }
        } else {
            if (count($invoice->payments) > 0) {
                $paymentsOld = $em->getRepository('ITDoorsControllingBundle:InvoicePayments')
                    ->findBy(array ('invoiceId' => $invoiceNew->getId()));
                foreach ($paymentsOld as $payOld) {
                    $em->remove($payOld);
                }
                $sendEmailPay = false;
                $date = new \DateTime();
                foreach ($invoice->payments as $pay) {
                    $date = new \DateTime(trim($pay->date));
                    if ($dateFact->format('U') < $date->format('U')) {
                        $dateFact = $date;
                    }
                    $payments = new InvoicePayments();
                    $payments->setInvoice($invoiceNew);
                    $payments->setDate($date);
                    $payments->setSumma(trim($pay->summa));
                    if ($pay->bank != 'null') {
                        $payments->setBank(trim($pay->bank));
                    }
                    $em->persist($payments);
                    $summaPaymens += $pay->summa;

                    $days = ($date->format('U') - strtotime($pay->date)) / 24 / 3600;

                    if (in_array($days, array (1))) {
                        $sendEmailPay = true;
                    }
                }
                if ($sendEmailPay) {
                    $this->messageTemplate = 'invoice-pay';
                }
            } else {
                $date = new \DateTime();
                $invoiceNew->setDateFact(null);
                $days = ($date->format('U') - strtotime($invoiceNew->getDelayDate()->format('Y-m-d'))) / 24 / 3600;
                if (in_array($days, array (1))) {
                    $this->messageTemplate = 'invoice-not-pay';
                }
                $paymentsOld = $em->getRepository('ITDoorsControllingBundle:InvoicePayments')
                    ->findBy(array ('invoiceId' => $invoiceNew->getId()));
                foreach ($paymentsOld as $payOld) {
                    $em->remove($payOld);
                }
            }
        }

        $invoiceNew->setUpdateDatetime($this->updateDatetime);
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

        $summaActs = $this->addActs($invoiceNew, $invoice->acts);

        $debtSum = round(($summaActs ? $summaActs : 0 )-($summaPaymens ? $summaPaymens : 0), 2);
        if ($debtSum <= 0) {
            $invoiceNew->setDateFact($dateFact);
        }
        $invoiceNew->setDebitSum($debtSum >= 0 ? $debtSum : 0);
        echo ' debtSum: '.$debtSum .' ~ ';

        if (!empty($invoice->delayDate) && $invoice->delayDate != 'null') {
            $invoiceNew->setDelayDate(new \DateTime(trim($invoice->delayDate)));
        }
        if (is_numeric($invoice->delayDays)) {
            $invoiceNew->setDelayDays((int) $invoice->delayDays);
        }
        if (in_array($invoice->delayDaysType, array ('Б', 'б', 'Банковский'))) {
            $invoiceNew->setDelayDaysType('Б');
        } elseif (in_array($invoice->delayDaysType, array ('К', 'к', 'Календарный'))) {
            $invoiceNew->setDelayDaysType('К');
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
    /**
     * addActs
     * 
     * @param Invoice $invoiceNew
     * @param string  $acts
     *
     * @return string
     */
    private function addActs (Invoice $invoiceNew, $acts)
    {
        $summa = 0;
        $em = $this->container->get('doctrine')->getManager();

        if (method_exists($invoiceNew, 'getId')) {
            $actsOld = $em->getRepository('ITDoorsControllingBundle:InvoiceAct')
               ->findBy(array (
                   'invoiceId' => $invoiceNew->getId()
               ));
            foreach ($actsOld as $actOld) {
                $detailsFind = $em->getRepository('ITDoorsControllingBundle:InvoiceActDetal')
                    ->findBy(array ('invoiceActId' => $actOld->getId()));
                foreach ($detailsFind as $detailsFindOne) {
                    $em->remove($detailsFindOne);
                }
                $em->remove($actOld);
            }
        }

        foreach ($acts as $act) {
            $actFind = new InvoiceAct();
            $actFind->setNumber($act->actNumber);
            $actFind->setInvoice($invoiceNew);
            $actFind->setDate(new \DateTime(trim($act->actDate)));
            $actFind->setOriginal($act->actOriginal);
            $em->persist($actFind);
            $details = $act->actDetail;
            foreach ($details as $detail) {
                $detalAdd = new InvoiceActDetal();
                $detalAdd->setAct($actFind);
                $detalAdd->setCount($detail->count);
                $detalAdd->setMpk($detail->mpk);
                $detalAdd->setNote($detail->note);
                $detalAdd->setSumma($detail->summa);
                $summa += $detail->summa;
                $em->persist($detalAdd);
            }
        }

        return $summa;
    }
    private function findDogovor ($invoiceFind, $invoice, $invoiceNew)
    {
        $em = $this->container->get('doctrine')->getManager();

        /** @var Dogovor  $dogovorfind */
        $dogovorfind = $em->getRepository('ListsDogovorBundle:Dogovor')
            ->findOneBy(array ('dogovorGuid' => trim($invoice->dogovorGuid)));

        /** @var Organization  $customerfind */
        $customerfind = $em->getRepository('ListsOrganizationBundle:Organization')
            ->findOneBy(array ('edrpou' => trim($invoice->customerEdrpou)));

        /** @var Organization  $performerfind */
        $performerfind = $em->getRepository('ListsOrganizationBundle:Organization')
            ->findOneBy(array ('edrpou' => trim($invoice->performerEdrpou)));

        if ($customerfind) {
            $invoiceNew->setCustomer($customerfind);
            if (!array_key_exists($customerfind->getId(), $this->arrCostumersForSendMessages)) {
                $this->arrCostumersForSendMessages[$customerfind->getId()] = array ();
            }
            if (
                    $this->messageTemplate && !array_key_exists(
                        $this->messageTemplate,
                        $this->arrCostumersForSendMessages[$customerfind->getId()]
                    )
                ) {
                $this->arrCostumersForSendMessages[$customerfind->getId()][$this->messageTemplate] = array ();
            }
        }
        if ($performerfind) {
            $invoiceNew->setPerformer($performerfind);
        }

        if ($dogovorfind) {
            $invoiceNew->setDogovor($dogovorfind);
            $em->persist($invoiceNew);
            if (!$invoiceFind && $invoiceNew->getDogovorId()) {
                $this->addReaspon($invoiceNew);
            }
        } else {
            if ($customerfind && $performerfind) {

                /** @var Dogovor  $dogovoradd */
                $dogovoradd = $em->getRepository('ListsDogovorBundle:Dogovor')
                    ->findOneBy(array (
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
//                    unset($dogovoradd);

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
                $this->addCronError($invoiceNew, 'error', $error, json_encode($invoice));
            }
        }
        if ($this->messageTemplate && $invoiceNew->getDogovorNumber() && method_exists($customerfind, 'getId')) {
            $customerId = $invoiceNew->getCustomer()->getId();
            $dogovorN = $invoiceNew->getDogovorNumber();
            if (
                    !array_key_exists(
                        $invoiceNew->getDogovorNumber(),
                        $this->arrCostumersForSendMessages[$customerfind->getId()][$this->messageTemplate]
                    )
                ) {
                $this->arrCostumersForSendMessages[$customerId][$this->messageTemplate][$dogovorN] = array ();
            }
            $this->arrCostumersForSendMessages[$customerId][$this->messageTemplate][$dogovorN][] = $invoiceNew->getId();
        }
    }
    /**
     * savejson
     *
     * @param object $json Description
     * 
     * @return boolen
     */
    private function savejson ($json)
    {
        $count = count($json);
        $countInvoice = 0;
        $em = $this->container->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        foreach ($json as $key => $invoice) {

            echo $countInvoice . " ~ ";
            if ($countInvoice == 1000) {
                $em->flush();
                $em->clear();
                $countInvoice = 0;
            }
            ++$countInvoice;
            echo number_format(memory_get_usage() / 8000000, 0, ',', ' ')
                . "MB ~ more: ".($count - $key) . "\n";

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
            $json[$key] = null;
            unset($json[$key]);
            unset($invoiceNew);
        }
        echo 'Delete deleted invoice' . "\n";
        $this->deleteInvoice();
        echo 'Try add email for send' . "\n";
        $this->sendEmails();
        echo 'Try add cron for send email' . "\n";
        $cron = $this->container->get('it_doors_cron.service');
        $cron->addSendEmails();
        echo 'Cron successfully' . "\n";
    }
    /**
     * addReaspon
     * 
     * @param Invoice $invoice
     */
    private function addReaspon (Invoice $invoice)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $companystructs = $em->getRepository('ListsDogovorBundle:DogovorCompanystructure')
            ->findBy(array ('dogovorId' => $invoice->getDogovorId()));

        $db = $em->getConnection();
        $query = "
            DELETE FROM invoice_companystructure
                WHERE invoice_id = :invoiceId
           ";
        $stmt = $db->prepare($query);
        $params[':invoiceId'] = $invoice->getId();
        $stmt->execute($params);

        foreach ($companystructs as $company) {
            $invoicecompany = new InvoiceCompanystructure();
            $invoicecompany->setInvoice($invoice);
            $invoicecompany->setCompanystructure($company->getCompanyStructures());

            $em->persist($invoicecompany);
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
    private function addCronError ($invoice, $status, $reason, $descript)
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

        return true;
    }
    /**
     * Returns results for interval future invoice
     *
     * @var Container
     * 
     * @return array
     */
    public function getTabsInvoice ()
    {
        $translator = $this->container->get('translator');
        $tabs = array ();
        $tabs['act'] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'act',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Act')
        );
        $tabs['invoice'] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'invoice',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Invoice')
        );
        $tabs['contacts'] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'contacts',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Contacts client')
        );
        $tabs['dogovor'] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'dogovor',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Dogovor')
        );
        $tabs['responsible'] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'responsible',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Responsible')
        );
        $tabs['organization'] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'customer',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('Customer')
        );
        $tabs['payments'] = array (
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
    public function getTabsExpectedPay ()
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var InvoicecronRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');

        $date = date('Y-m-d', mktime(0, 0, 0, date("m"), date('d') + 3, date('Y')));
        $summa = $invoice->getSumma($date);

        $translator = $this->container->get('translator');
        $tabs = array ();
        $tabs['yesterday'] = array (
            'blockupdate' => 'ajax-tab-holder-2',
            'tab' => 'yesterday',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_pay_show'),
            'text' => $translator->trans('Yesterday') . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getSumma(date('Y-m-d'));
        $tabs['today'] = array (
            'blockupdate' => 'ajax-tab-holder-2',
            'tab' => 'today',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_pay_show'),
            'text' => $translator->trans('Today') . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $days = date("w") < 5 ? (int) date("w") : 0;
        $toMonday = 0;
        if ($days == 0) {
            if (date("w") == 0) {
                $toMonday = 0;
            } elseif (date("w") == 6) {
                $toMonday = 7;
            } elseif (date("w") == 5) {
                $toMonday = 7;
            }
        }
        $days =  1 - date("w") + $toMonday;
        if ($days > 0) {
            $summa = $invoice->getSumma(date('Y-m-d', mktime(0, 0, 0, date("m"), date('d') + $days, date('Y'))));
            $tabs['monday'] = array (
                'blockupdate' => 'ajax-tab-holder-2',
                'tab' => 'monday',
                'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_pay_show'),
                'text' => $translator->trans('Monday') . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
            );
        }
        $days = 2 - date("w") + $toMonday;
        if ($days > 0) {
            $summa = $invoice->getSumma(date('Y-m-d', mktime(0, 0, 0, date("m"), date('d') + $days, date('Y'))));
            $tabs['tuesday'] = array (
                'blockupdate' => 'ajax-tab-holder-2',
                'tab' => 'tuesday',
                'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_pay_show'),
                'text' => $translator->trans('Tuesday') . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
            );
        }
        $days = 3 - date("w")+$toMonday;
        if ($days > 0) {
            $summa = $invoice->getSumma(date('Y-m-d', mktime(0, 0, 0, date("m"), date('d') + $days, date('Y'))));
            $tabs['wednesday'] = array (
                'blockupdate' => 'ajax-tab-holder-2',
                'tab' => 'wednesday',
                'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_pay_show'),
                'text' => $translator->trans('Wednesday') . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
            );
        }
        $days = 4 - date("w")+$toMonday;
        if ($days > 0) {
            $summa = $invoice->getSumma(date('Y-m-d', mktime(0, 0, 0, date("m"), date('d') + $days, date('Y'))));
            $tabs['thursday'] = array (
                'blockupdate' => 'ajax-tab-holder-2',
                'tab' => 'thursday',
                'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_pay_show'),
                'text' => $translator->trans('Thursday') . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
            );
        }
        $days = 5 - date("w")+$toMonday;
        if ($days > 0) {
            $summa = $invoice->getSumma(date('Y-m-d', mktime(0, 0, 0, date("m"), date('d') + $days, date('Y'))));
            $tabs['friday'] = array (
                'blockupdate' => 'ajax-tab-holder-2',
                'tab' => 'friday',
                'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_pay_show'),
                'text' => $translator->trans('Friday') . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
            );
        }

        return $tabs;
    }
    /**
     * Returns results for interval future invoice
     *
     * @var Container
     * 
     * @return array
     */
    public function getTabsInvoiceGrafics ()
    {
        $translator = $this->container->get('translator');
        $tabs = array ();
        $tabs['individual'] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'individual',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_analytic_list'),
            'text' => $translator->trans('Statistics dept')
        );
        $tabs['analiz'] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'analiz',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_analytic_list'),
            'text' => $translator->trans('Analysis of payments')
        );
        $tabs['general'] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'general',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_analytic_list'),
            'text' => $translator->trans('General')
        );
        $tabs['withoutacts'] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'withoutacts',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_analytic_list'),
            'text' => $translator->trans('Without acts')
        );
        $tabs['responsible'] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'responsible',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_analytic_list'),
            'text' => $translator->trans('Responsible')
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
    public function getTabsEmptyData ()
    {
        $translator = $this->container->get('translator');
        $tabs = array ();
        $tabs['delay'] = array (
            'blockupdate' => 'ajax-tab-holder-1',
            'tab' => 'delay',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_data_show'),
            'text' => $translator->trans('Delay')
        );
        $tabs['act'] = array (
            'blockupdate' => 'ajax-tab-holder-1',
            'tab' => 'act',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_data_show'),
            'text' => $translator->trans('Existence act')
        );
        $tabs['dogovor'] = array (
            'blockupdate' => 'ajax-tab-holder-1',
            'tab' => 'dogovor',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_data_show'),
            'text' => $translator->trans('Existence dogovor')
        );

        return $tabs;
    }
    /**
     * Returns results for interval future invoice
     * 
     * @param integer $companystryctyre
     * @param array   $filters
     * 
     * @return tabs[]
     */
    public function getTabsInvoices ($companystryctyre, $filters = null)
    {
        $translator = $this->container->get('translator');
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var InvoicecronRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');

        $summa = $invoice->getInvoicePeriodSum(1, 30, $companystryctyre, $filters);
        $tabs[] = array (
            'tab' => 30,
            'blockupdate' => 'ajax-tab-holder',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('from') . ' 1 ' . $translator->trans('to') . ' 30 ' . $translator->trans('day')
            . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePeriodSum(31, 60, $companystryctyre, $filters);
        $tabs[] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 60,
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 31 ' . $translator->trans('to') . ' 60 ' . $translator->trans('days')
            . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePeriodSum(61, 120, $companystryctyre, $filters);
        $tabs[] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 120,
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 61 ' . $translator->trans('to') . ' 120 ' . $translator->trans('days')
            . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePeriodSum(121, 180, $companystryctyre, $filters);
        $tabs[] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 180,
            'class' => '',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 121 ' . $translator->trans('to') . ' 180 ' . $translator->trans('days')
            . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePeriodSum(180, 0, $companystryctyre, $filters);
        $tabs[] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 181,
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 181 ' . $translator->trans('days')
            . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoiceCourtSum($companystryctyre, $filters);
        $tabs[] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'court',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('court')
            . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePaySum($companystryctyre, $filters);
        $tabs[] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'pay',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('pay')
            . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoiceFlowSum($companystryctyre, $filters);
        $tabs[] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'flow',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('Flow')
            . '<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        if (!isset($filters['isFired'])) {
            $summa = $invoice->getInvoiceAllSum($companystryctyre, $filters);
            $summa = $summa[0]['summa'];
        } else {
            $summa = 0;
        }
        $tabs[] = array (
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'all',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('General')
            . '<br>' . number_format($summa, 2, ',', ' ')
        );

        return $tabs;
    }
    /**
     *  sendEmails
     */
    private function sendEmails ()
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

                        $tableHTML = $this->container->get('templating')
                            ->render("ITDoorsControllingBundle:Invoice:tableForEmail.html.twig", array (
                                'invoices' => $invoices,
                                'template' => $template
                            ));

                        foreach ($contacts as $user) {
                            if (empty($user['email'])) {
                                continue;
                            }
                            echo "send email for " . $user['email'] . "\n\n";
                            $idEmail = $email->send(
                                array ($emailTo => $nameTo),
                                $template,
                                array (
                                    'users' => array (
                                        $user['email']
                                    ),
                                    'variables' => array (
                                        '${lastName}$' => $user['lastName'],
                                        '${firstName}$' => $user['firstName'],
                                        '${middleName}$' => $user['middleName'],
                                        '${number}$' => $invoices[0]['dogovorNumber'],
                                        '${date}$' => (!$invoices[0]['dogovorDate'] ?
                                            '' : $invoices[0]['dogovorDate']->format('d.m.Y')),
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
                                $message->setNote(
                                    $translator->trans(
                                        'Send',
                                        array (),
                                        'ITDoorsControllingBundle'
                                    ) . ' <a href="' . $this->container->get('router')
                                    ->generate(
                                        'automailer_show',
                                        array ('id' => $idEmail)
                                    ) . '">email</a>'
                                );
                                $invoicef = $em->getRepository('ITDoorsControllingBundle:Invoice')
                                    ->find($invoice['id']);
                                $message->setInvoice($invoicef);
                                $em->persist($message);
                            }
                        }
                    }
                }
            }
        }
    }
    /**
     * removeInvoice
     */
    public function removeInvoice ()
    {
        $em = $this->container->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        $invoices = $em->getRepository('ITDoorsControllingBundle:Invoice')->findAll();

        $companystructs = $em->getRepository('ListsCompanystructureBundle:Companystructure')
                ->findAll();
        $memStart = memory_get_usage();
        $count = count($invoices);
        $countInvoice = 0;
        foreach ($invoices as $key => $invoice) {
            ++$countInvoice;
            echo $countInvoice. ' ~ '. number_format((memory_get_usage() - $memStart) / 8000000, 0, ',', ' ')
                . "MB ~ more: ".($count - $key) . "\n";

            foreach ($companystructs as $company) {
                $invoiceCompanystructures = $em->getRepository('ITDoorsControllingBundle:InvoiceCompanystructure')
                    ->findBy(array (
                        'invoiceId' => $invoice->getId(),
                        'companystructureId' => $company->getId()
                    ));
                if (count($invoiceCompanystructures) > 1) {
                    foreach ($invoiceCompanystructures as $key => $invoiceCompanystructure) {
                        if ($key > 0) {
                            $em->remove($invoiceCompanystructure);
                        }
                    }
                }
            }
            if ($invoice->getDate()->format('Y') < 2014 && $invoice->getDateFact() !== null) {
                $em->remove($invoice);
            }
            if ($countInvoice == 1000) {
                $em->flush();
                $countInvoice = 0;
            }
        }
        $em->flush();
    }
    /**
     * deleteInvoice
     */
    private function deleteInvoice ()
    {
        $em = $this->container->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        $invoices = $em->getRepository('ITDoorsControllingBundle:Invoice')->findAll();

        foreach ($invoices as $invoice) {
            if ($invoice->getUpdateDatetime() != $this->updateDatetime && $invoice->getDateFact() == null) {
                $em->remove($invoice);
            }
        }
        $em->flush();
    }
    private function updateIvoiceInfo ()
    {
        $em = $this->container->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        $invoices = $em->getRepository('ITDoorsControllingBundle:Invoice')->findAll();
        echo 'Start update info'."\n";
        $count = count($invoices);
        foreach ($invoices as $key => $invoice) {
            echo ($count-$key).' number:'.$invoice->getInvoiceId()." id:".$invoice->getId();

            $actSum = $em->getRepository('ITDoorsControllingBundle:InvoiceAct')
                ->getSum($invoice->getId());
            $paySum = $em->getRepository('ITDoorsControllingBundle:InvoicePayments')
                ->getSum($invoice->getId());
            $debtSum = ($actSum ? $actSum[1] : 0 )-($paySum ? $paySum[1] : 0);
            $invoice->setDebitSum($debtSum >=0 ? $debtSum : 0);
            if ($paySum >= $actSum) {
                $date = $em->getRepository('ITDoorsControllingBundle:InvoicePayments')
                    ->dateLastPay($invoice->getId());
                if (key_exists('date', $date[0])) {
                    $date = $date[0]['date'];
                } else {
                    $date = $invoice->getDate();
                }
                $invoice->setDateFact($date);
            }

            /** @var Dogovor  $dogovorfind */
            $dogovorfind = $em->getRepository('ListsDogovorBundle:Dogovor')
                ->findOneBy(array ('dogovorGuid' => $invoice->getDogovorGuid()));

            /** @var Organization  $customerfind */
            $customerfind = $em->getRepository('ListsOrganizationBundle:Organization')
                ->findOneBy(array ('edrpou' => $invoice->getCustomerEdrpou()));

            /** @var Organization  $performerfind */
            $performerfind = $em->getRepository('ListsOrganizationBundle:Organization')
                ->findOneBy(array ('edrpou' => $invoice->getPerformerEdrpou()));

            if (!$dogovorfind) {
                if ($customerfind && $performerfind) {

                    /** @var Dogovor  $dogovoradd */
                    $dogovoradd = $em->getRepository('ListsDogovorBundle:Dogovor')
                        ->findOneBy(array (
                        'number' => $invoice->getDogovorNumber(),
                        'startdatetime' => $invoice->getDogovorDate(),
                        'customerId' => $customerfind->getId(),
                        'performerId' => $performerfind->getId()
                    ));
                    if ($dogovoradd) {
                        // подтвердить связь и 1С
                        $dogovoradd->setDogovorGuid($invoice->getDogovorGuid());
                        $em->persist($dogovoradd);
                    }
                }
            }
            if ($customerfind) {
                $invoice->setCustomer($customerfind);
            }
            if ($performerfind) {
                $invoice->setPerformer($performerfind);
            }
            if ($dogovorfind) {
                echo ' add dogovor';
                $invoice->setDogovor($dogovorfind);
                $this->addReaspon($invoice);
            } else {
                echo ' dogovor not fount';
            }
            $em->persist($invoice);
             echo "\n";
        }
        $em->flush();
        echo 'End update info'."\n";
    }
    /**
     * Returns results for interval future invoice
     * 
     * @param integer $page
     * @param array   $filters
     * 
     * @return tabs[]
     */
    public function getIndividual ($page, $filters = null)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var OrganizationRepository $organizationRepository */
        $organizationRepository = $em->getRepository('ListsOrganizationBundle:Organization');
        /** @var InvoiceRepository $invoiceRepository */
        $invoiceRepository = $em->getRepository('ITDoorsControllingBundle:Invoice');

        $organizationsEntity = $organizationRepository->getForInvoiceAnalitic($filters);
        $paginator = $this->container->get($this->paginator);
        $organizations = $paginator->paginate($organizationsEntity, $page, 10);
        $result = array('paginator' => $organizations);

        $showDays = false;
        $dateStart = null;
        $dateStop = null;
        if (isset($filters['daterange']) && !empty($filters['daterange'])) {
            $dateArr = explode('-', $filters['daterange']);
            $start = new \DateTime(str_replace('.', '-', $dateArr[0]));
            $stop = new \DateTime(str_replace('.', '-', $dateArr[1]));
            if ($start->format('m.Y') === $stop->format('m.Y')) {
                $showDays = true;
            }
            $dateStart = $start->format('d.m.Y');
            $dateStop = $stop->format('d.m.Y');
        }

        $invoices = array();
        if ($organizations) {
            foreach ($organizations as $organization) {
                $invoices[$organization['id']] = array ();
                $invoices[$organization['id']]['name'] = $organization['customerName'];
                $invoices[$organization['id']]['invoices'] = array();
                $invoiceActs = $invoiceRepository->getForCustomerDebit($organization['id'], $filters);

                $minDate = $dateStart;
                $maxDate = $dateStop;
                if (!$dateStop) {
                    $maxDate = date('t.m.Y');
                }
                $minDateInvoice = $dateStart;
                $maxDateInvoice = date('t.m.Y');
                $invoiceAct = array();
                foreach ($invoiceActs as $act) {
                    if (!$showDays) {
                        $date = $act['date']->format('t.m.Y');
                    } else {
                        $date = $act['date']->format('d.m.Y');
                    }
                    if ((empty($minDate) || strtotime($date) < strtotime($minDate)) && !$dateStart) {
                        $minDate = $date;
                    }
                    if ((empty($maxDate) || strtotime($date) > strtotime($maxDate)) && !$dateStop) {
                        $maxDate = $date;
                    }
                    if ((empty($minDateInvoice) || strtotime($date) < strtotime($minDateInvoice))) {
                        $minDateInvoice = $date;
                    }
                    if ((empty($maxDateInvoice) || strtotime($date) > strtotime($maxDateInvoice))) {
                        $maxDateInvoice = $date;
                    }
                    if (!isset($invoiceAct[$date])) {
                        $invoiceAct[$date] = array();
                        $invoiceAct[$date]['all'] = 0;
                        $invoiceAct[$date]['debt'] = 0;
                    }
                    $invoiceAct[$date]['all'] += $act['allSumm']-$act['payAll'];
                    $invoiceAct[$date]['debt'] += $act['allSummDebit']-$act['payAll'];
                }
                $res = array();
                if (!$showDays) {
                    for ($i = strtotime($minDateInvoice);
                        $i <= strtotime($maxDateInvoice);
                        $i = mktime(0, 0, 0, date('m', $i)+1, 1, date('Y', $i))) {
                        $date = date('t.m.Y', $i);
                        $all = 0;
                        $debt = 0;
                        if (isset($invoiceAct[$date])) {
                            $all = $invoiceAct[$date]['all'];
                            $debt = $invoiceAct[$date]['debt'];
                        }

                        $res[$date] = array(
                            'all' => $all,
                            'debt' => $debt
                        );
                    }
                } else {
                    for ($i = strtotime($minDateInvoice);
                        $i <= strtotime($maxDateInvoice);
                        $i = mktime(0, 0, 0, date('m', $i), date('d', $i)+1, date('Y', $i))) {
                        $date = date('d.m.Y', $i);
                        $all = 0;
                        $debt = 0;
                        if (isset($invoiceAct[$date])) {
                            $all = $invoiceAct[$date]['all'];
                            $debt = $invoiceAct[$date]['debt'];
                        }
                        $res[$date] = array(
                            'all' => $all,
                            'debt' => $debt
                        );
                    }
                }
                foreach ($res as $data => $val) {
                    if (
                            ($dateStart && strtotime($dateStart) > strtotime($data))
                            or
                            ($dateStop && strtotime($dateStop) < strtotime($data))
                        ) {
                        unset($res[$data]);
                    }
                }
                $invoices[$organization['id']]['min'] = $minDate;
                $invoices[$organization['id']]['max'] = $maxDate;
                $invoices[$organization['id']]['invoices'] = $res;
            }
        }
        $result['entities'] = $invoices;
        $result['showDays'] = $showDays;

        return $result;
    }
    /**
     * Returns results for interval future invoice
     * 
     * @param integer $page
     * @param array   $filters
     * 
     * @return tabs[]
     */
    public function getAnaliz ($page, $filters = null)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var OrganizationRepository $organizationRepository */
        $organizationRepository = $em->getRepository('ListsOrganizationBundle:Organization');
        /** @var InvoiceRepository $invoiceRepository */
        $invoiceRepository = $em->getRepository('ITDoorsControllingBundle:Invoice');
        /** @var InvoicePaymentsRepository $invoicePayRepository */
        $invoicePayRepository = $em->getRepository('ITDoorsControllingBundle:InvoicePayments');

        $organizationsEntity = $organizationRepository->getForInvoiceAll($filters);
        $paginator = $this->container->get($this->paginator);
        $organizations = $paginator->paginate($organizationsEntity, $page, 10);
        $result = array('paginator' => $organizations);

        $showDays = false;
        $dateStart = null;
        $dateStop = null;
        if (isset($filters['daterange']) && !empty($filters['daterange'])) {
            $dateArr = explode('-', $filters['daterange']);
            $start = new \DateTime(str_replace('.', '-', $dateArr[0]));
            $stop = new \DateTime(str_replace('.', '-', $dateArr[1]));
            if ($start->format('m.Y') === $stop->format('m.Y')) {
                $showDays = true;
            }
            $dateStart = $start->format('d.m.Y');
            $dateStop = $stop->format('d.m.Y');
        }

        $invoices = array();
        if ($organizations) {
            foreach ($organizations as $organization) {
                $invoices[$organization['id']] = array ();
                $invoices[$organization['id']]['name'] = $organization['customerName'];
                $invoices[$organization['id']]['invoices'] = array();
                $invoiceActs = $invoiceRepository->getForAnaliticAll($organization['id'], $filters);
                $invoicePays = $invoicePayRepository->getForAnaliticAll($organization['id'], $filters);

                $minDate = $dateStart;
                $maxDate = $dateStop;
                if (!$dateStop) {
                    $maxDate = date('t.m.Y');
                }
                $minDateInvoice = $dateStart;
                $maxDateInvoice = date('t.m.Y');
                // общая сумма долга
                $invoiceAct = array();
                $debtAll = array();
                foreach ($invoiceActs as $act) {
                    if (!$showDays) {
                        $date = $act['date']->format('t.m.Y');
                        if (!isset($debtAll[$act['delayDate']->format('t.m.Y')])) {
                            $debtAll[$act['delayDate']->format('t.m.Y')] = 0;
                        }
                        $debtAll[$act['delayDate']->format('t.m.Y')] += $act['allSumm']- $act['payAll'];
                    } else {
                        $date = $act['date']->format('d.m.Y');
                        if (!isset($debtAll[$act['delayDate']->format('d.m.Y')])) {
                            $debtAll[$act['delayDate']->format('d.m.Y')] = 0;
                        }
                        $debtAll[$act['delayDate']->format('d.m.Y')] += $act['allSumm']-$act['payAll'];
                    }
                    if ((empty($minDate) || strtotime($date) < strtotime($minDate)) && !$dateStart) {
                        $minDate = $date;
                    }
                    if ((empty($maxDate) || strtotime($date) > strtotime($maxDate)) && !$dateStop) {
                        $maxDate = $date;
                    }
                    if ((empty($minDateInvoice) || strtotime($date) < strtotime($minDateInvoice))) {
                        $minDateInvoice = $date;
                    }
                    if ((empty($maxDateInvoice) || strtotime($date) > strtotime($maxDateInvoice))) {
                        $maxDateInvoice = $date;
                    }
                    if (!isset($invoiceAct[$date])) {
                        $invoiceAct[$date] = array();
                        $invoiceAct[$date]['all'] = 0;
                        $invoiceAct[$date]['debt'] = 0;
                    }
                    $invoiceAct[$date]['all'] += $act['allSumm'];
                    $invoiceAct[$date]['debt'] += 0;
                }

                // оплата
                $invoicePay = array();
                foreach ($invoicePays as $pay) {
                    if (!$showDays) {
                        $date = $pay['date']->format('t.m.Y');
                    } else {
                        $date = $pay['date']->format('d.m.Y');
                    }
                    if ((empty($minDate) || strtotime($date) < strtotime($minDate)) && !$dateStart) {
                        $minDate = $date;
                    }
                    if ((empty($maxDate) || strtotime($date) > strtotime($maxDate)) && !$dateStop) {
                        $maxDate = $date;
                    }
                    if ((empty($minDateInvoice) || strtotime($date) < strtotime($minDateInvoice))) {
                        $minDateInvoice = $date;
                    }
                    if ((empty($maxDateInvoice) || strtotime($date) > strtotime($maxDateInvoice))) {
                        $maxDateInvoice = $date;
                    }
                    if (!isset($invoicePay[$date])) {
                        $invoicePay[$date] = array();
                        $invoicePay[$date]['pay'] = 0;
                        $invoicePay[$date]['payDebt'] = 0;
                    }
                    $invoicePay[$date]['pay'] += $pay['summaPay'];
                    if ($pay['date']->format('U') > $pay['delayDate']->format('U')) {
                        $invoicePay[$date]['payDebt'] += $pay['summaPay'];
                    }
                }
                $all = 0;
                $debt = 0;
                $res = array();
                if (!$showDays) {
                    for ($i = strtotime($minDateInvoice);
                        $i <= strtotime($maxDateInvoice);
                        $i = mktime(0, 0, 0, date('m', $i)+1, 1, date('Y', $i))) {
                        $date = date('t.m.Y', $i);
                        if (isset($invoiceAct[$date])) {
                            $all += $invoiceAct[$date]['all'];
                            $debt += $invoiceAct[$date]['debt'];
                        }
                        if (isset($debtAll[$date])) {
                            $debt += $debtAll[$date];
                        }
                        if (isset($invoicePay[$date])) {
                            $all -= $invoicePay[$date]['pay'];
                            $debt -= $invoicePay[$date]['payDebt'];
                        }
                        if (!isset($invoiceAct[$date])) {
                            $invoiceAct[$date] = array();
                            $invoiceAct[$date]['all'] = $all;
                            $invoiceAct[$date]['debt'] = $debt;
                        }
                        $invoiceAct[$date]['all'] = $all;
                        $invoiceAct[$date]['debt'] = $debt;
                        $res[$date] = array(
                            'all' => $all,
                            'debt' => $debt
                        );
                    }
                } else {
                    for ($i = strtotime($minDateInvoice);
                        $i <= strtotime($maxDateInvoice);
                        $i = mktime(0, 0, 0, date('m', $i), date('d', $i)+1, date('Y', $i))) {
                        $date = date('d.m.Y', $i);
                        if (isset($invoiceAct[$date])) {
                            $all += $invoiceAct[$date]['all'];
                            $debt += $invoiceAct[$date]['debt'];
                        }
                        if (isset($debtAll[$date])) {
                            $debt += $debtAll[$date];
                        }
                        if (isset($invoicePay[$date])) {
                            $all -= $invoicePay[$date]['pay'];
                            $debt -= $invoicePay[$date]['payDebt'];
                        }
                        if (!isset($invoiceAct[$date])) {
                            $invoiceAct[$date] = array();
                            $invoiceAct[$date]['all'] = $all;
                            $invoiceAct[$date]['debt'] = $debt;
                        }
                        $invoiceAct[$date]['all'] = $all;
                        $invoiceAct[$date]['debt'] = $debt;
                        $res[$date] = array(
                            'all' => $all,
                            'debt' => $debt
                        );
                    }
                }
                foreach ($res as $data => $val) {
                    if (
                            ($dateStart && strtotime($dateStart) > strtotime($data))
                            or
                            ($dateStop && strtotime($dateStop) < strtotime($data))
                        ) {
                        unset($res[$data]);
                    }
                }
                $invoices[$organization['id']]['min'] = $minDate;
                $invoices[$organization['id']]['max'] = $maxDate;
                $invoices[$organization['id']]['invoices'] = $res;
            }
        }
        $result['entities'] = $invoices;
        $result['showDays'] = $showDays;

        return $result;
    }
    /**
     * Returns results for interval future invoice
     * 
     * @param integer $page
     * @param array   $filters
     * 
     * @return tabs[]
     */
    public function getResponsible ($page, $filters = null)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var CompanystructurenRepository $companystructureRepository */
        $companystructureRepository = $em->getRepository('ListsCompanystructureBundle:Companystructure');
        $result = $companystructureRepository->getForInvoiceAnalitic($filters);
        $newResult = array();
        foreach ($result as $key => $val) {
             $result[$key]['allSumma'] =  $val['allSumma']-$val['paySumma'];
             $result[$key]['allSummDebit'] = $val['allSummDebit']-$val['paySumma'];
             $newResult[$key] = $result[$key]['allSumma'];
        }
        array_multisort($newResult, SORT_ASC, $result);

        return $result;
    }
    /**
     * Returns results for interval future invoice
     * 
     * @param integer $page
     * @param array   $filters
     * 
     * @return tabs[]
     */
    public function getGeneral ($page, $filters = null)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var OrganizationRepository $organization */
        $organization = $em->getRepository('ListsOrganizationBundle:Organization');
        $result = $organization->getForInvoice($filters);
        $newResult = array();
        foreach ($result as $key => $val) {
             $result[$key]['allSumma'] =  $val['allSumma']-$val['paySumma'];
             $result[$key]['allSummDebit'] = $val['allSummDebit']-$val['paySumma'];
             $newResult[$key] = $result[$key]['allSumma'];
        }
        array_multisort($newResult, SORT_ASC, $result);

        return $result;
    }
    /**
     * Returns results for interval future invoice
     * 
     * @param integer $page
     * @param array   $filters
     * 
     * @return tabs[]
     */
    public function getWithoutacts ($page, $filters = null)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var OrganizationRepository $organization */
        $organization = $em->getRepository('ListsOrganizationBundle:Organization');
        $result = $organization->getForInvoice($filters);
        foreach ($result as $val) {
             $val['allSumma'] -= $val['paySumma'];
             $val['allSummDebit'] -= $val['paySumma'];
        }
        $newResult = array();
        foreach ($result as $key => $val) {
             $result[$key]['allSumma'] =  $val['allSumma']-$val['paySumma'];
             $result[$key]['allSummDebit'] = $val['allSummDebit']-$val['paySumma'];
             $newResult[$key] = $result[$key]['allSumma'];
        }
        array_multisort($newResult, SORT_ASC, $result);

        return $result;
    }
}
