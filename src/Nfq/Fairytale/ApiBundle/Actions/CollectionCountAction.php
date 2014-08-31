<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

use Nfq\Fairytale\ApiBundle\Datasource\Factory\DatasourceFactory;
use Symfony\Component\HttpFoundation\Request;

class CollectionCountAction implements CollectionActionInterface
{
    /** @var  DatasourceFactory */
    protected $factory;

    /**
     * @param DatasourceFactory $factory
     */
    public function setFactory(DatasourceFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @inheritdoc
     */
    public function execute(Request $request, $resource)
    {
        return [
            [
                'count' => $this->factory->create($resource)->count()
            ],
            200
        ];
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'collection.count';
    }
}
