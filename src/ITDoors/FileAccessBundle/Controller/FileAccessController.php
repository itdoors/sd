<?php

namespace ITdoors\FileAccessBundle\Controller;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * Executes getFile action
     *
     * @param Request $request
     *
     * @return string
     */
    public function getFileIfAuthenticatedAction(Request $request)
    {
        $path = $request->get('path');
        $fileAccessService = $this->container->get('i_tdoors_file_access.service');

        try {
            $response = BinaryFileResponse($fileAccessService->getFileIfAuthenticated($path));

            if ($response) {
                return $response;
            }
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Sorry, there is no such file!');
        }

        return new RedirectResponse('/login');
    }
}
