<?php

namespace Nfq\Fairytale\ApiBundle\Datasource;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\KernelInterface;

class FileSource implements DataSourceInterface
{
    /** @var bool */
    protected $loaded = false;

    /** @var  KernelInterface */
    protected $locator;

    /** @var  mixed */
    protected $data;

    /** @var  string */
    protected $resource;

    /** @var  string */
    protected $file;

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
        $inc = function ($value) {
            return $value + 1;
        };
        return array_map([$this, 'transformOne'], $dataset, array_map($inc, array_keys($dataset)));
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
        $this->file = $this->locator->locateResource($filename);

        if (!file_exists($this->file)) {
            throw new FileException($this->file);
        }

        $this->data = json_decode(file_get_contents($this->file), true);

        $this->loaded = true;
    }

    /**
     * @inheritdoc
     */
    public function read($identifier)
    {
        $this->checkLoaded();

        $item = @$this->data[$this->resource][$identifier - 1] ?: null;

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

        $item = @$this->data[$this->resource][$identifier - 1] ?: null;

        if ($item) {
            $this->data[$this->resource][$identifier - 1] = array_replace(
                $this->data[$this->resource][$identifier - 1],
                $patch
            );
            file_put_contents($this->file, json_encode($this->data, JSON_PRETTY_PRINT));
            return $this->transformOne($this->data[$this->resource][$identifier - 1], $identifier);
        }
    }

    /**
     * @inheritdoc
     */
    public function delete($identifier)
    {
        $this->checkLoaded();

        if (@$this->data[$this->resource][$identifier - 1]) {
            unset($this->data[$this->resource][$identifier - 1]);
            file_put_contents($this->file, json_encode($this->data, JSON_PRETTY_PRINT));

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

        if (isset($data['id'])) {
            unset($data['id']);
        }

        $this->data[$this->resource][] = $data;
        file_put_contents($this->file, json_encode($this->data, JSON_PRETTY_PRINT));

        return $this->transformOne($data, count($this->data[$this->resource]));
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
