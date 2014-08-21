<?php

namespace Nfq\Fairytale\ApiBundle\Datasource;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\KernelInterface;

class FileSource implements DataSource
{
    /** @var bool */
    protected $loaded = false;

    /** @var  KernelInterface */
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

    public function setLocator(KernelInterface $locator)
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
     * @inheritdoc
     */
    public function index($limit = null, $offset = null, $orderBy = null, $order = null)
    {
        $this->checkLoaded();

        return $this->transform($this->data[$this->resource]);
    }

    /**
     * @param string $filename
     */
    public function load($filename)
    {
        $absolutePath = $this->locator->locateResource($filename);

        if (!file_exists($absolutePath)) {
            throw new FileException($absolutePath);
        }

        $this->data = json_decode(file_get_contents($absolutePath), true);

        $this->loaded = true;
    }

    /**
     * @inheritdoc
     */
    public function read($identifier)
    {
        $this->checkLoaded();

        $item = @$this->data[$this->resource][$identifier] ?: null;

        if ($item) {
            return $this->transformOne($item, $identifier);
        }
    }

    /**
     * @inheritdoc
     */
    public function update($identifier, $patch)
    {
        $this->checkLoaded();

        $item = @$this->data[$this->resource][$identifier] ?: null;

        if ($item) {
            $this->data[$this->resource][$identifier] = array_replace(
                $this->data[$this->resource][$identifier],
                $patch
            );
            return $this->transformOne($this->data[$this->resource][$identifier], $identifier);
        }
    }

    /**
     * @inheritdoc
     */
    public function delete($identifier)
    {
        $this->checkLoaded();

        if (@$this->data[$this->resource][$identifier]) {
            unset($this->data[$this->resource][(string)$identifier]);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function create($data)
    {
        $this->checkLoaded();

        $id = @$data['id'] ?: md5(uniqid());

        if (isset($this->data[$this->resource][$id])) {
            throw new \InvalidArgumentException("Duplicate ID {$id}");
        }

        unset($data['id']);

        $this->data[$this->resource][(string)$id] = $data;
        return $this->transformOne($data, $id);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        $this->checkLoaded();

        return count($this->data[$this->resource]);
    }

    /**
     * @inheritdoc
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getResource()
    {
        return $this->resource;
    }
}
