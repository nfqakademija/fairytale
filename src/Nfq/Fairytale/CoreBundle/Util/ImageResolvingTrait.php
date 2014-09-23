<?php

namespace Nfq\Fairytale\CoreBundle\Util;

use Liip\ImagineBundle\Imagine\Cache\CacheManagerAwareTrait;

trait ImageResolvingTrait
{
    use CacheManagerAwareTrait;

    /** @var  string[] */
    protected $filters = [];

    /**
     * Which Imagine filters to apply on image
     *
     * @param string[] $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * Resolves relative image path to absolute URLs to configured Imagine filter results
     *
     * @param string $fileName
     * @return string[]
     */
    public function resolveImages($fileName)
    {
        $applyFilter = function ($filter) use ($fileName) {
            return $this->cacheManager->getBrowserPath($fileName, $filter);
        };

        return array_combine($this->filters, array_map($applyFilter, $this->filters));
    }
}
