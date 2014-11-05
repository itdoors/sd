<?php

namespace SD\TaskBundle\Classes;

/**
 * ViewerTaskAccess class
 */
class ViewerTaskAccess extends BasicTaskAccess
{

    /**
     * @return bool
     */
    public function canDeleteFile()
    {

        return false;
    }

    /**
     * @return bool
     */
    public function canAddViewer()
    {
        if ($this->getStage() != 'closed'
            && $this->getStage() != 'done'
            && $this->getStage() != 'undone') {

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canUploadFiles()
    {

        return false;
    }
}
