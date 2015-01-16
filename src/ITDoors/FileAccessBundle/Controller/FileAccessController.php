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
    public function getFileAction(Request $request)
    {
        $path = $request->get('path');
        $securityContext = $this->container->get('security.context');
        $fileAccessService = $this->container->get('i_tdoors_file_access.service');

        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            try {
                $response = BinaryFileResponse($fileAccessService->getFile($path));

                return $response;
            } catch (FileNotFoundException $e) {
                throw new NotFoundHttpException('Sorry, there is no such file!');
            }
        }

        return new RedirectResponse('/login');
    }
}
