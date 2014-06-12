<?php

namespace ITDoors\EmailBundle\Controller;

use ITDoors\CommonBundle\Controller\BaseFilterController;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ITDoors\EmailBundle\Entity\File;
use Symfony\Component\Validator\Validation;

/**
 * Class TemplatesController
 * 
 * @package ITDoors\EmailBundle\Controller
 */
class FileController extends BaseFilterController
{

    /**
     * filesAction
     * 
     * @return render
     */
    public function uploadAction()
    {
        $request = $this->get('request');
        $files = $request->files;


        $json = array('res' => 'no');
        foreach ($files as $uploadedFile) {

            /** @var File $file */
            $file = new File();
            $file->setFile($uploadedFile);

            /** @var Validation $validator */
            $validator = $this->get('validator');
            $errors = $validator->validate($file);

            if (count($errors) > 0) {
                $json = array('error' => $errors[0]);

            } else {
                $name = uniqid();
                $directory = $this->container->getParameter('upload.file.path');
                $fileName = $name . '.' . $uploadedFile->getClientOriginalExtension();
                $session = $this->get('session');
                $filesArr = json_decode($session->get('files_upload', '{}'), true);
                $uploadedFile->move($directory, $fileName);
                $json = array('name' => $fileName);
                $filesArr[$fileName] = $uploadedFile->getClientOriginalName();
                $session->set('files_upload', json_encode($filesArr));
            }
        }

        return new Response(json_encode($json));
    }

    /**
     * deleteAction
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction()
    {
        $request = $this->get('request');
        $fileName = $request->get('name');
        $directory = '../web/files/upload/';
        if (is_file($directory . $fileName)) {
            if (unlink($directory . $fileName)) {
                $session = $this->get('session');
                $filesArr = json_decode($session->get('files_upload'), true);
                unset($filesArr[$fileName]);
                $session->set('files_upload', json_encode($filesArr));

                $json = array('res' => $session->get('files_upload'));
            } else {
                $json = array('error' => 'Eroor delete');
            }
        } else {
            $json = array('error' => 'File not found');
        }

        return new Response(json_encode($json));
    }
}
