<?php

namespace Nfq\Fairytale\CoreBundle\Upload;

interface UploadInterface
{
    /**
     * Returns original name of uploaded file
     *
     * @return string
     */
    public function getOriginalName();

    /**
     * Returns real name of stored file
     *
     * @return string
     */
    public function getStoredName();
}
