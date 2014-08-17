<?php

namespace Nfq\Fairytale\ApiBundle\Datasource;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileSource
{
    /** @var bool */
    protected $loaded = false;

    /** @var  FileLocator */
    protected $locator;

    /** @var  mixed */
    protected $data;

    public function isLoaded()
    {
        return $this->loaded;
    }

    private function checkLoaded()
    {
        if (!$this->isLoaded()) {
            throw new \LogicException('File is not loaded');
        }
    }

    public function setLocator(FileLocator $locator)
    {
        $this->locator = $locator;
    }

    private function transform($dataset)
    {
        return array_map([$this, 'transformOne'], $dataset, array_keys($dataset));
    }

    private function transformOne($entry, $id)
    {
        return array_replace(["id" => $id], $entry);
    }

    /**
     * @param string $resource
     * @return array
     */
    public function index($resource)
    {
        $this->checkLoaded();

        return $this->transform($this->data[$resource]);
    }

    /**
     * @param string $filename
     */
    public function load($filename)
    {
        $absolutePath = $this->locator->locate($filename);

        if (!file_exists($absolutePath)) {
            throw new FileException($absolutePath);
        }
        $this->data = json_decode(file_get_contents($absolutePath), true);

        $this->loaded = true;
    }

    /**
     * @param string $resource
     * @param string $identifier
     * @return array
     */
    public function read($resource, $identifier)
    {
        $this->checkLoaded();

        $item = @$this->data[$resource][$identifier] ?: null;

        if ($item) {
            return $this->transformOne($item, $identifier);
        }
    }

    public function update($resource, $identifier, $patch)
    {
        $this->checkLoaded();

        $item = @$this->data[$resource][$identifier] ?: null;

        if ($item) {
            $this->data[$resource][$identifier] = array_replace($this->data[$resource][$identifier], $patch);
            return $this->transformOne($this->data[$resource][$identifier], $identifier);
        }
    }

    public function delete($resource, $identifier)
    {
        $this->checkLoaded();

        if (@$this->data[$resource][$identifier]) {
            unset($this->data[$resource][(string)$identifier]);

            return true;
        } else {
            return false;
        }
    }

    public function create($resource, $data)
    {
        $this->checkLoaded();

        $id = @$data['id'] ?: md5(uniqid());

        if (isset($this->data[$resource][$id])) {
            throw new \InvalidArgumentException("Duplicate ID {$id}");
        }

        unset($data['id']);

        $this->data[$resource][(string)$id] = $data;
        return $this->transformOne($data, $id);
    }

    public function count($resource)
    {
        $this->checkLoaded();

        return count($this->data[$resource]);
    }
}
