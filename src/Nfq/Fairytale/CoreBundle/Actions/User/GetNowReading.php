<?php

namespace Nfq\Fairytale\CoreBundle\Actions\User;

use Nfq\Fairytale\ApiBundle\Actions\ActionResult;
use Nfq\Fairytale\ApiBundle\Actions\Instance\BaseInstanceAction;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\ApiBundle\DataSource\Factory\DataSourceFactory;
use Symfony\Component\HttpFoundation\Request;

class GetNowReading extends BaseInstanceAction
{
    const NAME = 'user.now_reading';

    /** @var  DataSourceFactory */
    protected $factory;

    /**
     * Performs the action
     *
     * @param Request             $request
     * @param DataSourceInterface $resource
     * @param string              $identifier
     * @return ActionResult
     */
    public function execute(Request $request, DataSourceInterface $resource, $identifier)
    {
        $resource = $this->factory->create('Nfq\Fairytale\CoreBundle\Entity\Reservation');

        $reservations = $resource->query(['user' => $identifier, 'takenAt' => null, 'returnedAt' => null]);
        return ActionResult::collection(200, [count($reservations) ? $reservations[0]->getBook() : null]);
    }

    /**
     * @param DataSourceFactory $factory
     */
    public function setFactory(DataSourceFactory $factory)
    {
        $this->factory = $factory;
    }
}
