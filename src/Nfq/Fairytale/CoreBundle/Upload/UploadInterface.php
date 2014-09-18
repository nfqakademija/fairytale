<?php

namespace Nfq\Fairytale\CoreBundle\Upload;

interface UploadInterface
{
    /**
     * Returns file name
     *
     * @return string
     */
    public function getFileName();

    /**
     * Sets file name
     *
     * @param $fileName
     * @return void
     */
    public function setFileName($fileName);
}
