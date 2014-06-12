<?php

namespace ITDoors\ControllingBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use ITDoors\ControllingBundle\Entity\Invoicecron;
use ITDoors\ControllingBundle\Entity\InvoicecronRepository;
use ITDoors\ControllingBundle\Entity\Invoice;
use Symfony\Component\BrowserKit\Response;

/**
 * Invoice Service class
 */
class InvoiceService
{

    /**
     * @var Container $container
     */
    protected $container;

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
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var InvoicecronRepository $invoicecron */
        $invoicecron = $em->getRepository('ITDoorsControllingBundle:Invoicecron');

        $fileName = false;

        $dh = opendir($directory);
        if ($dh) {
            while (($file = readdir($dh)) !== false) {
                if (filetype($directory . $file) == 'file') {
                    $findfile = $invoicecron->findBy(array('reason' => $file));
                    if (!$findfile) {
                        $fileName = $file;
                        continue;
                    }
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
        $directory = 'app/share/1c/debt/';


        if (!is_dir($directory)) {
            return false;
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
                    $this->savejson($json->invoice);
                    $this->addCronError(0, 'stop parser', $file, 'parser file');
                    break;
                default:
                    $this->addCronError(0, 'FATAL ERROR', $file, json_last_error());
            }
        } else {
            $this->addCronError(0, 'ok', 'file not found', 'new file not found');
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
        $em = $this->container->get('doctrine')->getManager();
        $invoiceNew = $em->getRepository('ITDoorsControllingBundle:Invoice')
            ->findOneBy(array('invoiceId' => $invoice->invoiceId));
        if (!$invoiceNew) {
            $invoiceNew = new Invoice();
            $invoiceNew->setCourt(0);
            $invoiceNew->setInvoiceId($invoice->invoiceId);
            if (!empty($invoice->dateFact) && $invoice->dateFact != 'null') {
                $invoiceNew->setDateFact(new \DateTime($invoice->dateFact));
            }
        } else {
            if (!empty($invoice->dateFact) && $invoice->dateFact != 'null') {
                $invoiceNew->setDateFact(new \DateTime($invoice->dateFact));
            } else {
                $invoiceNew->setDateFact(null);
            }
        }

        $invoiceNew->setDogovorGuid($invoice->dogovorGuid);
        $invoiceNew->setDogovorNumber($invoice->dogovorNumber);
        $invoiceNew->setDogovorName($invoice->dogovorName);
        $invoiceNew->setDogovorActNote($invoice->dogovorActNote);
        $invoiceNew->setDogovorActName($invoice->dogovorActName);
        $invoiceNew->setCustomerName($invoice->customerName);
        $invoiceNew->setCustomerEdrpou($invoice->customerEdrpou);
        $invoiceNew->setPerformerName($invoice->performerName);
        $invoiceNew->setPerformerEdrpou($invoice->performerEdrpou);

        if (!empty($invoice->delayDate) && $invoice->delayDate != 'null') {
            $invoiceNew->setDelayDate(new \DateTime($invoice->delayDate));
        }
        if (is_numeric($invoice->delayDays)) {
            $invoiceNew->setDelayDays((int) $invoice->delayDays);
        }
        if (in_array($invoice->delayDaysType, array('Б', 'К', 'б', 'к'))) {
            $invoiceNew->setDelayDaysType($invoice->delayDaysType);
        }
        if (!empty($invoice->dateFact) && $invoice->dateFact != 'null') {
            $invoiceNew->setDateFact(new \DateTime($invoice->dateFact));
        }
        if (!empty($invoice->dogovorDate) && $invoice->dogovorDate != 'null') {
            $invoiceNew->setDogovorDate(new \DateTime($invoice->dogovorDate));
        }
        if (!empty($invoice->date) && $invoice->date != 'null') {
            $invoiceNew->setDate(new \DateTime($invoice->date));
        } else {
            $this->addCronError(false, 'error data', 'date', json_encode($invoice));

            return false;
        }
        if (is_numeric($invoice->sum)) {
            $invoiceNew->setSum($invoice->sum);
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
            ->findOneBy(array('dogovorGuid' => $invoice->dogovorGuid));

        if ($dogovorfind) {
            $invoiceNew->setDogovor($dogovorfind);
            $em->persist($invoiceNew);
            $em->flush();
            if (!$invoiceFind) {
                $this->addReaspon($invoiceNew);
            }
        } else {

            /** @var Organization  $customerfind */
            $customerfind = $em->getRepository('ListsOrganizationBundle:Organization')
                ->findOneBy(array('edrpou' => $invoice->customerEdrpou));

            /** @var Organization  $performerfind */
            $performerfind = $em->getRepository('ListsOrganizationBundle:Organization')
                ->findOneBy(array('edrpou' => $invoice->performerEdrpou));

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
                    $dogovoradd->setDogovorGuid($invoice->dogovorGuid);
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
                $em->persist($invoiceNew);
                $em->flush();
                $em->refresh($invoiceNew);

                if (!$customerfind && $performerfind) {
                    $error = 'dogovor not found and customer';
                } elseif (!$performerfind && $customerfind) {
                    $error = 'dogovor not found and performer';
                } else {
                    $error = 'dogovor not found and customer and  performer';
                }
                $this->addCronError($invoiceNew, 'error', $error, json_encode($invoice));
            }
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
        foreach ($json as $key => $invoice) {

            $invoiceFind = true;
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
            $invoicecompany->setCompanystructureId($company->getCompanystructureId());

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
        $tabs['history'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'history',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_invoice_show'),
            'text' => $translator->trans('History')
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
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'today',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_expected_pay_show'),
            'text' => $translator->trans('Today') . ' ' . number_format($summa[0]['summa'], 2, ',', ' ')
        );
        $summa = $invoice->getSumma(date('Y-m-d', mktime(0, 0, 0, date("m"), date('d') + 1, date('Y'))));
        $tabs['tomorrow'] = array(
            'blockupdate' => 'ajax-tab-holder',
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

        return $tabs;
    }

    /**
     * @return Response
     */
    public function sendMail()
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');

        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('send@example.com')
            ->setTo('senj@mail.ru')
            ->setBody('You should see me from the profiler!');

        $this->container->get('mailer')->send($message);

        return new Response('send');
    }
}
