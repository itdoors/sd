<?php

namespace ITDoors\ControllingBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use ITDoors\ControllingBundle\Entity\Invoicecron;
use ITDoors\ControllingBundle\Entity\InvoicecronRepository;
use ITDoors\ControllingBundle\Entity\Invoice;
use ITDoors\ControllingBundle\Entity\InvoicePayments;
use ITDoors\ControllingBundle\Entity\InvoiceMessage;

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
     * @var Container
     * 
     * @return mixed[]
     */
    public function parserFile()
    {
        $directory = $this->container->getParameter('1C.file.path');

        if (!is_dir($directory)) {
            $this->addCronError(0, 'ok', 'directory not found', $directory);

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
                    if (!is_dir($directory.'old')) {
                        mkdir($directory.'old', 0700);
                    }
                    rename($directory.$file, $directory.'old/'.$file);
                    break;
                default:
                    $this->addCronError(0, 'FATAL ERROR', $file, json_last_error());
            }
        } else {
            $this->addCronError(0, 'ok', 'file not found', 'new file not found');

            return 'File not found';
        }
    }

    /**
     * saveinvoice
     * 
     * @param abject $invoice
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

            if ($invoice->dateFact != 'null') {
                $summa = 0;
                $dateFact = null;
                foreach ($invoice->dateFact as $pay) {
                    $dateFact = new \DateTime(trim($pay->date));
                    $payments = new InvoicePayments();
                    $payments->setInvoice($invoiceNew);
                    $payments->setDate($dateFact);
                    $payments->setSumma(trim($pay->summa));
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
            if ($invoice->dateFact != 'null') {
                $paymentsOld = $em->getRepository('ITDoorsControllingBundle:InvoicePayments')->findBy(array('invoiceId' => $invoiceNew->getId()));
                foreach ($paymentsOld as $payOld) {
                    $em->remove($payOld);
                }
                $summa = 0;
                $dateFact = null;
                foreach ($invoice->dateFact as $pay) {
                    $dateFact = new \DateTime(trim($pay->date));
                    $payments = new InvoicePayments();
                    $payments->setInvoice($invoiceNew);
                    $payments->setDate($dateFact);
                    $payments->setSumma(trim($pay->summa));
                    $em->persist($payments);
                    $summa += trim($pay->summa);
                }

                if ($summa != 0) {
                    $this->messageTemplate = 'invoice-pay';
                }

                if ($summa >= $invoice->sum && $dateFact != null) {
                    $invoiceNew->setDateFact($dateFact);
                }
            } else {
                $invoiceNew->setDateFact(null);
                $date = date('Y-m-d');
                $days = (strtotime($date)-strtotime($invoiceNew->getDelayDate()->format('Y-m-d')))/24/3600;
                if (in_array($days, array(0))) {
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
        $invoiceNew->setBank(trim($invoice->bank));
        $invoiceNew->setDogovorActName(trim($invoice->dogovorActName));
        $invoiceNew->setCustomerName(trim($invoice->customerName));
        $invoiceNew->setCustomerEdrpou(trim($invoice->customerEdrpou));
        $invoiceNew->setPerformerName(trim($invoice->performerName));
        $invoiceNew->setPerformerEdrpou(trim($invoice->performerEdrpou));

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
        if (!empty($invoice->dogovorActDate) && $invoice->dogovorActDate != 'null') {
            $invoiceNew->setDogovorActDate(new \DateTime(trim($invoice->dogovorActDate)));
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
        if (!empty($invoice->dogovorAct)) {
            $invoiceNew->setDogovorAct(json_encode($invoice->dogovorAct));
        } else {
            $this->addCronError(false, 'error data', 'dogovorAct', json_encode($invoice));

            return false;
        }
        if (in_array($invoice->dogovorActOriginal, array('0', '1'))) {
            $invoiceNew->setDogovorActOriginal($invoice->dogovorActOriginal);
        } else {
            $this->addCronError(false, 'error data', 'dogovorActOriginal', json_encode($invoice));

            return false;
        }

        return $invoiceNew;
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
            $em->flush();
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
                    $em->flush();
                    $em->refresh($invoiceNew);

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
                    $em->flush();
                    $em->refresh($invoiceNew);
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
                $em->flush();
                $em->refresh($invoiceNew);
                $this->addCronError($invoiceNew, 'error', $error, json_encode($invoice));
            }
        }
        if ($this->messageTemplate && $invoiceNew->getDogovorNumber()) {
            if (!array_key_exists($invoiceNew->getDogovorNumber(), $this->arrCostumersForSendMessages[$customerfind->getId()][$this->messageTemplate])) {
                $this->arrCostumersForSendMessages[$invoiceNew->getCustomer()->getId()][$this->messageTemplate][$invoiceNew->getDogovorNumber()] = array();
            }
            $this->arrCostumersForSendMessages[$invoiceNew->getCustomer()->getId()][$this->messageTemplate][$invoiceNew->getDogovorNumber()][] = $invoiceNew->getId();
        }
    }

    /**
     *  savejson
     *
     * @param object $json Description
     * 
     * @return boolen
     */
    private function savejson($json)
    {
        $count = count($json);
        foreach ($json as $key => $invoice) {
            echo ($count-$key-1)."\n";
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
        echo 'try add send email'."\n";
        $this->sendEmails();
        echo 'try add cron'."\n";
        $cron = $this->container->get('it_doors_cron.service');
        $cron->addSendEmails();
        echo 'add cron successfully'."\n";
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
            $em->flush();
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
        $em->flush();

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
     * @return tabs[]
     */
    public function getTabsInvoices()
    {
        $translator = $this->container->get('translator');
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var InvoicecronRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');

        $summa = $invoice->getInvoicePeriodSum(1, 30);
        $tabs[] = array(
            'tab' => 30,
            'blockupdate' => 'ajax-tab-holder',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('from') . ' 1 ' . $translator->trans('to') . ' 30 ' . $translator->trans('day')
             .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePeriodSum(31, 60);
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 60,
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 31 ' . $translator->trans('to') . ' 60 ' . $translator->trans('days')
            .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePeriodSum(61, 120);
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 120,
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 61 ' . $translator->trans('to') . ' 120 ' . $translator->trans('days')
            .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePeriodSum(121, 180);
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 180,
            'class' => '',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 121 ' . $translator->trans('to') . ' 180 ' . $translator->trans('days')
            .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePeriodSum(180, 0);
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 181,
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 181 ' . $translator->trans('days')
            .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoiceCourtSum();
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'court',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('court')
            .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoicePaySum();
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'pay',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('pay')
            .'<br>' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getInvoiceFlowSum();
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
        var_dump($this->arrCostumersForSendMessages);echo "\n";
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

                        $table = '<table style="width:100%;text-align:center"><tr>'
                                . '<td>'.$translator->trans('№', array(), 'ITDoorsControllingBundle').'</td>'
                                . '<td>'.$translator->trans('Date', array(), 'ITDoorsControllingBundle').'</td>'
                                . '<td>'.$translator->trans('Invoice amount', array(), 'ITDoorsControllingBundle').'</td>'
                                . '</tr>';
                        $dogovors = array();
                        foreach ($invoices as $invoice) {
                            $table .= '<tr>'
                                    . '<td>'.$invoice['invoiceId'].'</td>'
                                    . '<td>'.$invoice['date']->format('d.m.Y').'</td>'
                                    . '<td>'.$invoice['sum']-$invoice['paymentsSumma'].'</td>'
                                    . '</tr>';
                        }
                        $table .= '</table>';

                        foreach ($contacts as $user) {
                            echo "send email for ".$user['email']."\n";
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
                                        '${number}$' => $invoice['dogovorNumber'],
                                        '${date}$' => (!$invoice['dogovorDate'] ? '' : $invoice['dogovorDate']->format('d.m.Y')),
                                        '${performer}$' => $invoice['performerName'],
                                        '${table}$' => $table
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
                                $em->flush();
                            }
                        }
                    }
                }
            }
        }
    }
}