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

    /** @var  string */
    protected $resource;

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
     * @return array
     */
    public function index()
    {
        $this->checkLoaded();

        return $this->transform($this->data);
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

        $this->data = json_decode(file_get_contents($absolutePath), true)[$this->resource];

        $this->loaded = true;
    }

    /**
     * @param string $identifier
     * @return array
     */
    public function read($identifier)
    {
        $this->checkLoaded();

        $item = @$this->data[$identifier] ?: null;

        if ($item) {
            return $this->transformOne($item, $identifier);
        }
    }

    public function update($identifier, $patch)
    {
        $this->checkLoaded();

        $item = @$this->data[$identifier] ?: null;

        if ($item) {
            $this->data[$identifier] = array_replace(
                $this->data[$identifier],
                $patch
            );
            return $this->transformOne($this->data[$identifier], $identifier);
        }
    }

    public function delete($identifier)
    {
        $this->checkLoaded();

        if (@$this->data[$identifier]) {
            unset($this->data[(string)$identifier]);

            return true;
        } else {
            return false;
        }
    }

    public function create($data)
    {
        $this->checkLoaded();

        $id = @$data['id'] ?: md5(uniqid());

        if (isset($this->data[$id])) {
            throw new \InvalidArgumentException("Duplicate ID {$id}");
        }

        unset($data['id']);

        $this->data[(string)$id] = $data;
        return $this->transformOne($data, $id);
    }

    public function count()
    {
        $this->checkLoaded();

        return count($this->data);
    }

    public function setResource($resource)
    {
        $this->resource = $resource;
    }
}