<?php

namespace ITDoors\ControllingBundle\Services;

use Symfony\Component\DependencyInjection\Container;
//use Symfony\Component\Translation\Translator;
use ITDoors\ControllingBundle\Entity\Invoicecron;
use ITDoors\ControllingBundle\Entity\Invoice;

/**
 * Calendar Service class
 */
class ParserService
{
    /**
     * @var Container $container
     */
    protected $container;

    /**
     * @var Translator $translator
     */
    protected $translator;

    /**
     * __construct
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->translator = $this->container->get('translator');
    }

    /**
     * Returns dashboard filter choices of events for calendar
     *
     * @param string $directory Directory
     * 
     * @return mixed[]
     */
    public function findFile ($directory)
    {
        $em = $this->container->get('doctrine')->getManager();
        
        $file = false;
        
        if (is_dir($directory)) {
            if ($dh = opendir($directory)) {
                while (($fil = readdir($dh)) !== false) {
                    if (filetype($directory . $fil) == 'file') {
                        $findwork = $em
                            ->getRepository('ITDoorsControllingBundle:Invoicecron')
                            ->findBy(array('reason' => $fil));
                        if (!$findwork) {
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
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('start parser');
                    $error->setReason($file);
                    $error->setDescription('parser file');
                    $em->persist($error);
                    $em->flush();

                    $this->savejson($json->invoice);

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
     * @param json $json Description
     * 
     * @return boolen
     */
    private function savejson($json)
    {

        foreach ($json as $invoice) {
            // поиск в базе
            $em = $this->container->get('doctrine')->getManager();
            $invoiceObj = $em->getRepository('ITDoorsControllingBundle:Invoice')
                ->findOneBy(array('invoiceId' => $invoice->invoice_id));

            // Не найден счет
            if (!$invoiceObj) {
                // добавления invoice
                $invoiceNew = new Invoice();
                $invoiceNew->setInvoiceId($invoice->invoice_id);
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
                if (!empty($invoice->dogovor_act_note)) {
                    $invoiceNew->setDogovorActNote($invoice->dogovor_act_note);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovor_act_note');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (is_numeric($invoice->dogovor_act_summa)) {
                    $invoiceNew->setDogovorActSumma($invoice->dogovor_act_summa);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovor_act_summa');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (is_numeric($invoice->dogovor_act_count)) {
                    $invoiceNew->setDogovorActCount($invoice->dogovor_act_count);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovor_act_count');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }

                $invoiceNew->setDogovorId1c($invoice->dogovor_id_1c);
                $invoiceNew->setDogovorNumber($invoice->dogovor_number);
                $invoiceNew->setDogovorName($invoice->dogovor_name);
                if (!empty($invoice->dogovor_date)) {
                    $invoiceNew->setDogovorDate(new \DateTime($invoice->dogovor_date));
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovor_date');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (is_numeric($invoice->dogovor_uuie)) {
                    $invoiceNew->setDogovorUUIE($invoice->dogovor_uuie);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovor_uuie');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                $invoiceNew->setDogovorActName($invoice->dogovor_act_name);
                if (!empty($invoice->dogovor_act_date)) {
                    $invoiceNew->setDogovorActDate(new \DateTime($invoice->dogovor_act_date));
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovor_act_date');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (in_array($invoice->dogovor_act_original, array(0, 1))) {
                    $invoiceNew->setDogovorActOriginal($invoice->dogovor_act_original);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovor_act_original');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }


                if (!empty($invoice->delay_date)) {
                    $invoiceNew->setDelayDate(new \DateTime($invoice->delay_date));
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovor_act_original');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (is_numeric($invoice->delay_days)) {
                    $invoiceNew->setDelayDays((int) $invoice->delay_days);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('delay_days');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (in_array($invoice->delay_days_type, array('Б', 'К'))) {
                    $invoiceNew->setDelayDaysType($invoice->delay_days_type);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('delay_days_type');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (!empty($invoice->date_fact)) {
                    $invoiceNew->setDateFact(new \DateTime($invoice->date_fact));
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
                $invoiceNew->setCustomerName($invoice->customer_name);
                $invoiceNew->setCustomerEdrpou($invoice->customer_edrpou);
                $invoiceNew->setPerformerName($invoice->performer_name);
                $invoiceNew->setPerformerEdrpou($invoice->performer_edrpou);

                // get  dogovor id 1C
                $dogovorfind = $em->getRepository('ListsDogovorBundle:Dogovor')
                    ->findOneBy(array('dogovorId1c' => $invoice->dogovor_id_1c));
                // договор не связан с 1С
                if (!$dogovorfind) {

                    //customer заказчика ищем
                    $customerfind = $em->getRepository('ListsOrganizationBundle:Organization')
                        ->findOneBy(array('edrpou' => $invoice->customer_edrpou));
                    //performer исполнителя ищем
                    $performerfind = $em->getRepository('ListsOrganizationBundle:Organization')
                        ->findOneBy(array('edrpou' => $invoice->performer_edrpou));

                    // если заказчик и исполнитель найден
                    if ($customerfind && $performerfind) {
                        // ищем договор на 4 полям
                        $dogovoradd = $em->getRepository('ListsDogovorBundle:Dogovor')
                            ->findOneBy(array(
                            'number' => $invoice->dogovor_number,
                            'startdatetime' => $invoice->dogovor_date,
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
                            $dogovoradd->setDogovorId1c($invoice->dogovor_id_1c);
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



                // Найден  счет
            } else {
//                $status[] = $invoice->invoice_id . ' found (need update:
//                 date_fact, court,dogovor_act_oroginal,
//                  delay_days,delay_days_type) ';
                // обновить данные, (Дата оплаты,
                //  статус в суде,
                //  наличие оригинала акта, отсрочка)

                if (!empty($invoice->date_fact)) {
                    $invoiceObj->setDateFact(new \DateTime($invoice->date_fact));
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
                if (in_array($invoice->dogovor_act_original, array(0, 1))) {
                    $invoiceObj->setDogovorActOriginal($invoice->dogovor_act_original);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('error');
                    $error->setReason('dogovor_act_original');
                    $error->setInvoiceId($invoiceObj->getId());
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (is_numeric($invoice->delay_days)) {
                    $invoiceObj->setDelayDays((int) $invoice->delay_days);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setInvoiceId($invoiceObj->getId());
                    $error->setStatus('error');
                    $error->setReason('delay_days');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                if (in_array($invoice->delay_days_type, array('Б', 'К'))) {
                    $invoiceObj->setDelayDaysType((int) $invoice->delay_days_type);
                } else {
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setInvoiceId($invoiceObj->getId());
                    $error->setStatus('error');
                    $error->setReason('delay_days_type');
                    $error->setDescription(json_encode($invoice));
                    $em->persist($error);
                    $em->flush();
                    continue;
                }
                $em->flush();
            }
        }
    }
}
