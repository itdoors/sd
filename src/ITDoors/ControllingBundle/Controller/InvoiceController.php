<?php

namespace ITDoors\ControllingBundle\Controller;

use ITDoors\ControllingBundle\Entity\Invoice;
use ITDoors\ControllingBundle\Entity\Invoicecron;
use Lists\DogovorBundle\Entity\Dogovor;
use ITDoors\ControllingBundle\Entity\InvoiceCompanystructure;
use ITDoors\ControllingBundle\Entity\InvoiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Symfony\Component\HttpFoundation\Response;

/**
 * InvoiceController
 */
class InvoiceController extends BaseFilterController
{

    protected $filterNamespace = 'ajax.filter.namespace.report.invoice';

    /**
     *  indexAction
     * 
     * @return html Description
     */
    public function indexAction()
    {
        $session = $this->get('session');
        $period = $session->get('invoicePeriod', 30);


        return $this->render('ITDoorsControllingBundle:Invoice:index.html.twig', array(
                'period' => $period
        ));
    }

    /**
     *  showAction
     * 
     * @param int,string $period 30,60,120,180,181,court,pay
     * 
     * @return html Description
     */
    public function showAction($period)
    {
        $session = $this->get('session');

        $filterNamespace = $this->container->getParameter($this->getNamespace());

        if ($period != $session->get('invoicePeriod')) {
            $this->clearPaginator($filterNamespace);
            $session->set('invoicePeriod', $period);
        }

        $em = $this->getDoctrine()->getManager();
        /** @var ITDoors/ControllingBundle/Entity/InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');

        switch ($period) {
            case 30:
                $entities = $invoice->getInvoicePeriod(1, 30);
                $count = $invoice->getInvoicePeriodCount(1, 30);
                $sum = $invoice->getInvoicePeriodSum(1, 30);
                break;
            case 60:
                $entities = $invoice->getInvoicePeriod(31, 60);
                $count = $invoice->getInvoicePeriodCount(31, 60);
                $sum = $invoice->getInvoicePeriodSum(31, 60);
                break;
            case 120:
                $entities = $invoice->getInvoicePeriod(61, 120);
                $count = $invoice->getInvoicePeriodCount(61, 120);
                $sum = $invoice->getInvoicePeriodSum(61, 120);
                break;
            case 180:
                $entities = $invoice->getInvoicePeriod(121, 180);
                $count = $invoice->getInvoicePeriodCount(121, 180);
                $sum = $invoice->getInvoicePeriodSum(121, 180);
                break;
            case 181:
                $entities = $invoice->getInvoicePeriod(181, 0);
                $count = $invoice->getInvoicePeriodCount(181, 0);
                $sum = $invoice->getInvoicePeriodSum(181, 0);
                break;
            case 'court':
                $entities = $invoice->getInvoiceCourt();
                $count = $invoice->getInvoiceCourtCount();
                $sum = $invoice->getInvoiceCourtSum();
                break;
            case 'pay':
                $entities = $invoice->getInvoicePay();
                $count = $invoice->getInvoicePayCount();
                $sum = $invoice->getInvoicePaySum();
                break;
        }

        $page = $this->getPaginator($filterNamespace);
        if (!$page) {
            $page = 1;
        }

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        $entities->setHint('knp_paginator.count', $count);
        $pagination = $paginator->paginate($entities, $page, 20);
        $responsibles = array();

        foreach ($pagination as $val) {
            /** @var ITDoors/ControllingBundle/Entity/InvoiceCompanystructure */
            $responsibles[$val['id']] = $em
                ->getRepository('ITDoorsControllingBundle:InvoiceCompanystructure')
                ->findBy(array('invoiceId' => $val['id']));
        }

        return $this->render('ITDoorsControllingBundle:Invoice:show.html.twig', array(
                'period' => $period,
                'entities' => $pagination,
                'responsibles' => $responsibles,
                'sum' => $sum
        ));
    }

    /**
     *  invoiceAction
     * 
     * @param int       $invoiceid
     * @param string    $block
     * 
     * @return html Description
     */
    public function invoiceAction($invoiceid, $block)
    {
        $session = $this->get('session');
        $session->set('invoiceBlock', $block);
        $session->set('invoiceid', $invoiceid);

        $invoiceObj = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:Invoice')
            ->find($invoiceid);

        return $this->render('ITDoorsControllingBundle:Invoice:invoice.html.twig', array(
                'invoiceid' => $invoiceid,
                'block' => $block,
                'invoice' => $invoiceObj
        ));
    }

    /**
     *  invoiceshowAction
     * 
     * @param int $block
     * 
     * @return html Description
     */
    public function invoiceshowAction($block)
    {
        $session = $this->get('session');
        $session->set('invoiceBlock', $block);
        $invoiceid = $session->get('invoiceid');
        $em = $this->getDoctrine()->getManager();
        /** @var InvoiceRepository $invoice */
        $invoice = $em->getRepository('ITDoorsControllingBundle:Invoice');
        $invoiceObj = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:Invoice')
            ->find($invoiceid);
        $entitie = '';
        $organizationId = '';
        $dogovor = '';
        switch ($block) {
            case 'invoice':
                $dogovor = $invoiceObj->getDogovor();
                $organizationId = $dogovor->getCustomerId();
                $entitie = $invoiceObj;
                break;
            case 'organization':
                $dogovor = $this->getDoctrine()
                    ->getRepository('ListsDogovorBundle:Dogovor')
                    ->find($invoiceObj->getDogovor());
                $organizationId = $dogovor->getCustomerId();
                $entitie = $this->getDoctrine()
                    ->getRepository('ListsOrganizationBundle:Organization')
                    ->find($organizationId);
                break;
            case 'responsible':
                $entitie = $em->getRepository('ITDoorsControllingBundle:InvoiceCompanystructure')
                    ->findBy(array('invoiceId' => $invoiceid));
                break;
            case 'dogovor':
                $entitie = $this->getDoctrine()
                    ->getRepository('ListsDogovorBundle:Dogovor')
                    ->find($invoiceObj->getDogovor());
                ;
                break;
            case 'contacts':
                $dogovor = $this->getDoctrine()
                    ->getRepository('ListsDogovorBundle:Dogovor')
                    ->find($invoiceObj->getDogovor());
                $organizationId = $dogovor->getCustomerId();
                $entitie = $this->getDoctrine()
                    ->getRepository('ListsContactBundle:ModelContact')
                    ->findBy(array('modelName' => 'organization', 'modelId' => $organizationId));
                break;
            case 'history':
                $entitie = '';
                break;
        }

        return $this->render('ITDoorsControllingBundle:Invoice:table' . $block . '.html.twig', array(
                'entitie' => $entitie,
                'block' => $block,
                'organizationId' => $organizationId,
                'dogovor' => $dogovor,
                'invoice' => $invoiceObj
        ));
    }

    /**
     *  lastactionAction
     * 
     * @return html Description
     */
    public function lastactionAction()
    {
        $session = $this->get('session');
        $invoiceid = $session->get('invoiceid', false);
        if (!$invoiceid) {
            throw $this->createNotFoundException('Param "invoiceid" not found in session.');
        }
        $messages = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:InvoiceMessage')
            ->getInvoiceMessages((int) $invoiceid);

        return $this->render('ITDoorsControllingBundle:Invoice:lastaction.html.twig', array(
                'messages' => $messages,
                'invoiceid' => $invoiceid
        ));
    }

    /**
     *  cronAction
     * 
     * @return html Description
     */
    public function cronAction()
    {
        $logger = $this->get('logger');
        // directory
        $directory = '../app/share/1c/debt/';
        // name file
        $file = '544384351.json';

        if (is_file($directory . $file)) {
            $json = json_decode(file_get_contents($directory . $file));
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    $this->savejson($json->invoice);
                    break;
                case JSON_ERROR_DEPTH:
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('FATAL ERROR');
                    $error->setReason('json');
                    $error->setDescription('Достигнута максимальная глубина стека');
                    $em->persist($error);
                    $em->flush();
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('FATAL ERROR');
                    $error->setReason('json');
                    $error->setDescription('Некорректные разряды');
                    $em->persist($error);
                    $em->flush();
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('FATAL ERROR');
                    $error->setReason('json');
                    $error->setDescription('Некорректный управляющий символ');
                    $em->persist($error);
                    $em->flush();
                    break;
                case JSON_ERROR_SYNTAX:
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('FATAL ERROR');
                    $error->setReason('json');
                    $error->setDescription('error format JSON');
                    $em->persist($error);
                    $em->flush();
                    break;
                case JSON_ERROR_UTF8:
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('FATAL ERROR');
                    $error->setReason('json');
                    $error->setDescription('error UTF-8');
                    $em->persist($error);
                    $em->flush();
                    break;
                default:
                    $error = new Invoicecron();
                    $error->setDate(new \DateTime());
                    $error->setStatus('FATAL ERROR');
                    $error->setReason('json');
                    $error->setDescription('Не известная ошибка');
                    $em->persist($error);
                    $em->flush();
                    break;
            }
        } else {
            $error = new Invoicecron();
            $error->setDate(new \DateTime());
            $error->setStatus('FATAL ERROR');
            $error->setReason('file not found');
            $error->setDescription('Файл не найден '.$directory . $file);
            $em->persist($error);
            $em->flush();
        }

        return new Response('<html><body>Cron work!</body></html>');
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
            $em = $this->getDoctrine()->getManager();
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
                if (gettype($invoice->sum) == "double") {
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
                if (in_array($invoice->dogovor_act_original, array(0,1))) {
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
                if (in_array($invoice->court, array(0,1))) {
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
                // договор не найден
                if (!$dogovorfind) {

                    //customer заказчика ищем
                    $customerfind = $em->getRepository('ListsOrganizationBundle:Organization')
                        ->findOneBy(array('edrpou' => $invoice->customer_edrpou));
                    //performer исполнителья ищем
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
                        } else {

                            // подтвердить связь и 1С
                            $dogovoradd->setDogovorId1c($invoice->dogovor_id_1c);
                            $em->persist($dogovoradd);
                            $em->flush();

                            $error = new Invoicecron();
                            $error->setDate(new \DateTime());
                            $error->setInvoiceId($invoiceNew->getId());
                            $error->setStatus('error');
                            $error->setReason('add dogovor_id_1c in dogovor id = ' . $dogovoradd->getId());
                            $error->setDescription(json_encode($invoice));
                            $em->persist($error);
                            $em->flush();


                            $invoiceNew->setDogovorId($dogovoradd->getId);
                        }
                        // если не найден заказчик\исполнитель
                    } else {

                        $error = new Invoicecron();
                        $error->setDate(new \DateTime());
                        $error->setStatus('error');
                        // не найден заказчик
                        if (!$customerfind && $performerfind) {
                            $error->setReason('dogovor not font and customer');
                            // не найден исполнитель
                        } elseif (!$performerfind && $customerfind) {
                            $error->setReason('dogovor not font and performer');
                        } else {
                            $error->setReason('dogovor not font and customer and  performer');
                        }
                        $error->setDescription(json_encode($invoice));
                        $em->persist($error);
                        $error->setInvoiceId($invoiceNew->getId());
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
                if (in_array($invoice->court, array(0,1))) {
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
                if (in_array($invoice->dogovor_act_original, array(0,1))) {
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
        $error = new Invoicecron();
        $error->setDate(new \DateTime());
        $error->setStatus('cron work');
        $error->setReason('ok');
        $error->setDescription('cron work');
        $em->persist($error);
        $em->flush();
    }
}
