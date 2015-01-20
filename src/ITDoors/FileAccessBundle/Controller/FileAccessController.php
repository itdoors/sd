<?php

namespace ITDoors\FileAccessBundle\Controller;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * FileAccessController
 */
class FileAccessController extends Controller
{
    /**
     * Executes getFileIfAuthenticatedAction action
     *
     * @param Request $request
     *
     * @return string
     */
    public function getFileIfAuthenticatedAction(Request $request)
    {
        $path = $request->get('path');
        $timestamp = $request->get('timestamp');
        $fileAccessService = $this->container->get('i_tdoors_file_access.service');

        try {
            $response = new BinaryFileResponse($fileAccessService->getFileIfAuthenticated($path, $timestamp));

            if ($response) {
                return $response;
            }
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Sorry, there is no such file!');
        }

        return new RedirectResponse('/login');
    }

    /**
     * Executes getFileIfHasRoleAction action
     *
     * @param Request $request
     * 
     * @throws AccessDeniedException If there are no permissions to access this file
     *
     * @return string
     */
    public function getFileIfHasRoleAction(Request $request)
    {
        $path = $request->get('path');
        $timestamp = $request->get('timestamp');
        $role = $request->get('role');
        $fileAccessService = $this->container->get('i_tdoors_file_access.service');

        try {
            $response = new BinaryFileResponse($fileAccessService->getFileIfHasRole($path, $timestamp, $role));

            if ($response) {
                return $response;
            }
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Sorry, there is no such file!');
        }

        throw new AccessDeniedException('Sorry, you have no permissions to access this file!');

        return new Response();
    }
}
