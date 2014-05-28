<?php

namespace Lists\OrganizationBundle\Controller;

/**
 * DogovorAdminController class
 */
class DogovorAdminController extends SalesAdminController
{
    protected $filterNamespace = 'organization.dogovor.admin.filters';
    protected $baseRoute = 'lists_dogovor_admin_organization_index';
    protected $baseRoutePrefix = 'dogovor_admin';
}
