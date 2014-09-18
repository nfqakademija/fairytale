<?php

namespace Nfq\Fairytale\CoreBundle\Upload;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class GenericUpload
{
    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
} 
