<?php

namespace Nfq\Fairytale\CoreBundle\Util;

interface ImageResolvingInterface
{
    /**
     * Which Imagine filters to apply on image
     *
     * @param string[] $filters
     */
    public function setFilters($filters);
} 
