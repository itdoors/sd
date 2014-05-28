<?php
namespace ITDoors\ControllingBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use ITDoors\ControllingBundle\Entity\Invoicecron;
use ITDoors\ControllingBundle\Entity\InvoicecronRepository;
use ITDoors\ControllingBundle\Entity\Invoice;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpKernel\Fragment\Tests\FragmentRenderer\Renderer;

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
     * @var Container
     * 
     * @return mixed[]
     */
    public function parserFile ()
    {
        $directory = '../app/share/1c/debt/';
        $file = false;

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var InvoicecronRepository $invoicecron*/
        $invoicecron = $em->getRepository('ITDoorsControllingBundle:Invoicecron');

        if (is_dir($directory)) {
            if ($dh = opendir($directory)) {
                while (($fil = readdir($dh)) !== false) {
                    if (filetype($directory . $fil) == 'file') {
                        $findfile = $invoicecron->findBy(array('reason' => $fil));
                        if (!$findfile) {
                            $file = $fil;
                            continue;
                        }
                    }
                }
                closedir($dh);
            }
        }
        if ($file && is_file($directory . $file)) {
            $json = json_decode(file_get_contents($directory . $file));
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    /** @var Invoicecron $error */
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('start parser');
                    $error->setReason($file);
                    $error->setDescription('parser file');
                    $em->persist($error);
                    $em->flush();

                    $this->savejson($json->invoice);

                    /** @var Invoicecron $error */
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('stop parser');
                    $error->setReason($file);
                    $error->setDescription('parser file');
                    $em->persist($error);
                    $em->flush();

                    break;
                case JSON_ERROR_DEPTH:
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('FATAL ERROR');
                    $error->setReason($file);
                    $error->setDescription($directory . $file . ' Max long');
                    $em->persist($error);
                    $em->flush();
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('FATAL ERROR');
                    $error->setReason($file);
                    $error->setDescription($directory . $file . 'Некорректные разряды');
                    $em->persist($error);
                    $em->flush();
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('FATAL ERROR');
                    $error->setReason($file);
                    $error->setDescription($directory . $file . ' symvol corect');
                    $em->persist($error);
                    $em->flush();
                    break;
                case JSON_ERROR_SYNTAX:
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('FATAL ERROR');
                    $error->setReason($file);
                    $error->setDescription($directory . $file . 'error format JSON');
                    $em->persist($error);
                    $em->flush();
                    break;
                case JSON_ERROR_UTF8:
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('FATAL ERROR');
                    $error->setReason($file);
                    $error->setDescription($directory . $file . 'error UTF-8');
                    $em->persist($error);
                    $em->flush();
                    break;
                default:
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('FATAL ERROR');
                    $error->setReason($file);
                    $error->setDescription($directory . $file . 'Не известная ошибка');
                    $em->persist($error);
                    $em->flush();
                    break;
            }
        } else {
            $error = new Invoicecron();
            $error->setDate(new \DateTime());
            if ($file) {
                $error->setStatus('ERROR failure');
                $error->setReason('file not found');
                $error->setDescription('Файл был и пропал )  ' . $directory . $file);
            } else {
                $error->setStatus('ok');
                $error->setReason('file not found');
                $error->setDescription('Файл не найден ');
            }
            $em->persist($error);
            $em->flush();
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
        foreach ($json as $invoice) {
            // поиск в базе
            $em = $this->container->get('doctrine')->getManager();
            $invoiceObj = $em->getRepository('ITDoorsControllingBundle:Invoice')
                ->findOneBy(array('invoiceId' => $invoice->invoiceId));

            // Не найден счет
            if (!$invoiceObj) {
                // добавления invoice
                $invoiceNew = new Invoice();
                $invoiceNew->setInvoiceId($invoice->invoiceId);
                if (!empty($invoice->date)) {
                    $invoiceNew->setDate(new \DateTime($invoice->date));
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('date');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (is_numeric($invoice->sum)) {
                    $invoiceNew->setSum($invoice->sum);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('sum');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (!empty($invoice->dogovorActNote)) {
                    $invoiceNew->setDogovorActNote($invoice->dogovorActNote);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovorActNote');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (is_numeric($invoice->dogovorActSumma)) {
                    $invoiceNew->setDogovorActSumma($invoice->dogovorActSumma);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovorActSumma');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (is_numeric($invoice->dogovorActCount)) {
                    $invoiceNew->setDogovorActCount($invoice->dogovorActCount);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovorActCount');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }

                $invoiceNew->setDogovorId1c($invoice->dogovorId1c);
                $invoiceNew->setDogovorNumber($invoice->dogovorNumber);
                $invoiceNew->setDogovorName($invoice->dogovorName);
                if (!empty($invoice->dogovorDate)) {
                    $invoiceNew->setDogovorDate(new \DateTime($invoice->dogovorDate));
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovorDate');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (is_numeric($invoice->dogovorUuie)) {
                    $invoiceNew->setDogovorUUIE($invoice->dogovorUuie);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovorUuie');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                $invoiceNew->setDogovorActName($invoice->dogovorActName);
                if (!empty($invoice->dogovorActName)) {
                    $invoiceNew->setDogovorActDate(new \DateTime($invoice->dogovorActName));
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovorActName');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (in_array($invoice->dogovorActOriginal, array(0, 1))) {
                    $invoiceNew->setDogovorActOriginal($invoice->dogovorActOriginal);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovorActOriginal');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }


                if (!empty($invoice->delayDate)) {
                    $invoiceNew->setDelayDate(new \DateTime($invoice->delayDate));
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('delayDate');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (is_numeric($invoice->delayDays)) {
                    $invoiceNew->setDelayDays((int) $invoice->delayDays);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('delayDays');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (in_array($invoice->delayDaysType, array('Б', 'К'))) {
                    $invoiceNew->setDelayDaysType($invoice->delayDaysType);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('delayDaysType');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (!empty($invoice->dateFact)) {
                    $invoiceNew->setDateFact(new \DateTime($invoice->dateFact));
                }
                if (in_array($invoice->court, array(0, 1))) {
                    $invoiceNew->setCourt($invoice->court);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('court');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                $invoiceNew->setCustomerName($invoice->customerName);
                $invoiceNew->setCustomerEdrpou($invoice->customerEdrpou);
                $invoiceNew->setPerformerName($invoice->performerName);
                $invoiceNew->setPerformerEdrpou($invoice->performerEdrpou);

                /** @var Dogovor  $dogovorfind*/
                $dogovorfind = $em->getRepository('ListsDogovorBundle:Dogovor')
                    ->findOneBy(array('dogovorId1c' => $invoice->dogovorId1c));
                // договор не связан с 1С
                if (!$dogovorfind) {

                     /** @var Organization  $customerfind*/
                    $customerfind = $em->getRepository('ListsOrganizationBundle:Organization')
                        ->findOneBy(array('edrpou' => $invoice->customerEdrpou));
                     /** @var Organization  $performerfind*/
                    $performerfind = $em->getRepository('ListsOrganizationBundle:Organization')
                        ->findOneBy(array('edrpou' => $invoice->performerEdrpou));

                    // если заказчик и исполнитель найден
                    if ($customerfind && $performerfind) {
                         /** @var Dogovor  $dogovoradd*/
                        $dogovoradd = $em->getRepository('ListsDogovorBundle:Dogovor')
                            ->findOneBy(array(
                            'number' => $invoice->dogovorNumber,
                            'startdatetime' =>  new DateTime($invoice->dogovorDate),
                            'customer_id' => $customerfind->getId(),
                            'performer_id' => $performerfind->getId(),
                        ));

                        if (!$dogovoradd) {
                            // можно поискать договор по 2 полям
//                            $dogovoradd = $em->getRepository('ListsDogovorBundle:Dogovor')
//                            ->findOneBy(array(
//                                'number' => $invoice->dogovor_number,
//                                'startdatetime' => $invoice->dogovor_date,
//                                'customer_id' => null,
//                                'performer_id' => null,
//                            ));
                            if ($dogovoradd) {

                            } else {



                                // добавить договор
//                            $dogovorNew = new Dogovor();
//                            $dogovorNew->setNumber($invoice->dogovor_number);
//                            $dogovorNew->setName($invoice->dogovor_name);
//                            $dogovorNew->setStartdatetime(new \DateTime($invoice->dogovor_date));
//                            $dogovorNew->setCustomerId($customerfind->getId());
//                            $dogovorNew->setPerformerId($performerfind->getId());
//                            $dogovorNew->setDogovorId1c($invoice->dogovor_id_1c);
//                            $em->persist($dogovorNew);
//                            $em->flush();
//                            $invoiceNew->setDogovor($dogovorNew);
                                $em->persist($invoiceNew);
                                $em->flush();


                                $error = new Invoicecron();
                                $error->setDate(new \DateTime());
                                $error->setInvoiceId($invoiceNew->getId());
                                $error->setStatus('error');
                                $error->setReason('dogovor not found');
                                $error->setDescription(json_encode($invoice));
                                $em->persist($error);
                                $em->flush();
                                continue;
                            }
                        } else {

                            // подтвердить связь и 1С
                            $dogovoradd->setDogovorId1c($invoice->dogovorId1c);
                            $em->persist($dogovoradd);
                            $em->flush();

                            $error = new Invoicecron();
                            $error->setDate(new \DateTime());
                            $error->setInvoiceId($invoiceNew->getId());
                            $error->setStatus('join dogovor and 1c');
                            $error->setReason('add dogovor_id_1c in dogovor id = ' . $dogovoradd->getId());
                            $error->setDescription(json_encode($invoice));
                            $em->persist($error);
                            $em->flush();


                            $invoiceNew->setDogovorId($dogovoradd->getId);
                        }
                        // если не найден заказчик\исполнитель
                    } else {
                        $em->persist($invoiceNew);
                        $em->flush();
                        $em->refresh($invoiceNew);

                        $error = new Invoicecron();
                        $error->setDate(new \DateTime());
                        $error->setStatus('error');
                        // не найден заказчик
                        if (!$customerfind && $performerfind) {
                            // можно найти довогор по 3 полям
                            $error->setReason('dogovor not found and customer');
                            // не найден исполнитель
                        } elseif (!$performerfind && $customerfind) {
                            // можно найти довогор по 3 полям
                            $error->setReason('dogovor not found and performer');
                        } else {
                            $error->setReason('dogovor not found and customer and  performer');
                        }
                        $error->setDescription(json_encode($invoice));
                        $error->setInvoice($invoiceNew);
                        $em->persist($error);
                        $em->flush();
                    }
                    // договор найден по id 1C
                } else {
                    $invoiceNew->setDogovorId($dogovorfind->getId());
                    // save invoice
                    $em->persist($invoiceNew);
                    $em->flush();
                }


                // отвественых по договору =отвественных по счету
                if (!empty($invoiceNew->getDogovorId())) {
                    $companystructs = $em->getRepository('ListsDogovorBundle:DogovorCompanystructure')
                        ->findBy(array('dogovorId' => $invoiceNew->getDogovorId()));
                    foreach ($companystructs as $company) {
                        // add invoice_companystructure
                        $invoicecompany = new InvoiceCompanystructure();
                        $invoicecompany->setInvoiceId($invoiceNew->getId());
                        $invoicecompany->setCompanystructureId($company->getCompanystructureId());

                        $em->persist($invoicecompany);
                        $em->flush();
                    }
                }
            // Find Invoice
            } else {
//                $status[] = $invoice->invoice_id . ' found (need update:
//                 date_fact, court,dogovor_act_oroginal,
//                  delay_days,delay_days_type) ';
                // обновить данные, (Дата оплаты,
                //  статус в суде,
                //  наличие оригинала акта, отсрочка)

                if (!empty($invoice->dateFact)) {
                    $invoiceObj->setDateFact(new \DateTime($invoice->dateFact));
                }
                if (in_array($invoice->court, array(0, 1))) {
                    $invoiceObj->setCourt($invoice->court);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('court');
                    $error->setInvoiceId($invoiceObj->getId());
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (in_array($invoice->dogovorActOriginal, array(0, 1))) {
                    $invoiceObj->setDogovorActOriginal($invoice->dogovorActOriginal);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovorActOriginal');
                    $error->setInvoiceId($invoiceObj->getId());
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (is_numeric($invoice->delayDays)) {
                    $invoiceObj->setDelayDays((int) $invoice->delayDays);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setInvoiceId($invoiceObj->getId());
                    $error->setStatus('error');
                    $error->setReason('delayDays');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (in_array($invoice->delayDaysType, array('Б', 'К'))) {
                    $invoiceObj->setDelayDaysType((int) $invoice->delayDaysType);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setInvoiceId($invoiceObj->getId());
                    $error->setStatus('error');
                    $error->setReason('delayDaysType');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                $em->flush();
            }
        }
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
     * @return tabs[]
     */
    public function getTabsInvoices()
    {
        $translator = $this->container->get('translator');
        $tabs[] = array(
            'tab' => 30,
            'blockupdate' => 'ajax-tab-holder',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('from') . ' 1 ' . $translator->trans('to') . ' 30 ' . $translator->trans('day')
        );
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 60,
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 31 ' . $translator->trans('to') . ' 60 ' . $translator->trans('days')
        );
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 120,
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 61 ' . $translator->trans('to') . ' 120 ' . $translator->trans('days')
        );
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 180,
            'class' => '',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 121 ' . $translator->trans('to') . ' 180 ' . $translator->trans('days')
        );
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 181,
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator
                ->trans('from') . ' 181 ' . $translator->trans('days')
        );
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'court',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('court')
        );
        $tabs[] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'pay',
            'url' => $this->container->get('router')->generate('it_doors_controlling_invoice_show'),
            'text' => $translator->trans('pay')
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

        /** @var InvoiceRepository $invoice*/
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
