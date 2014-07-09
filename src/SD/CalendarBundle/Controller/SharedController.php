<?php

namespace SD\CalendarBundle\Controller;

use Lists\HandlingBundle\Entity\HandlingMessageViewRepository;
use Lists\HandlingBundle\Services\HandlingMessageService;
use Lists\HandlingBundle\Entity\HandlingMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SharedController
 */
class SharedController extends SalesAdminController
{
    protected $baseRoutePrefix = 'shared';
    protected $baseTemplate = 'Shared';

}
