<?php

namespace Nfq\Fairytale\CoreBundle\Upload;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UploadManager
{
    /** @var  string */
    private $uploadDir;

    /** @var  string */
    private $rootDir;

    /**
     * @param string $rootDir
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * @param string $uploadDir
     */
    public function setUploadDir($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function getPublicUploadsDir()
    {
        return sprintf('%s/../web/%s', $this->rootDir, $this->uploadDir);
    }

    public function store(GenericUpload $upload, UploadInterface $entity)
    {
        if (null === $upload->getFile()) {
            throw new BadRequestHttpException('No file is being uploaded');
        }

        if (null === $this->uploadDir) {
            throw new \InvalidArgumentException(sprintf('%s does not have an upload directory configured',
                get_class($this)));
        }

        $entity->setFileName($this->moveToStorage($upload->getFile()));
    }

    /**
     * @param File $originalFile
     * @return File
     */
    public function moveToStorage(File $originalFile)
    {
        $filename = sha1(uniqid(mt_rand(), true)) . '.' . $originalFile->getExtension();

        $filename = preg_replace('/(\\w{2})(\\w{2})(.*)/', '\1/\2/\3', $filename);

        $parts = explode('/', $filename);
        return $originalFile->move(join('/', [$this->getPublicUploadsDir(), $parts[0], $parts[1]]), end($parts));
    }
} 
