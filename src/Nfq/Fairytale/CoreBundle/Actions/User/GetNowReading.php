<?php

namespace Nfq\Fairytale\CoreBundle\Actions\User;

use Im0rtality\ApiBundle\Actions\ActionResult;
use Im0rtality\ApiBundle\Actions\Instance\BaseInstanceAction;
use Im0rtality\ApiBundle\DataSource\DataSourceInterface;
use Im0rtality\ApiBundle\DataSource\Factory\DataSourceFactory;
use Nfq\Fairytale\CoreBundle\Entity\Reservation;
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
        return ActionResult::collection(200, array_map(function (Reservation $reservation) {
            return $reservation->getBook();
        }, $reservations));
    }

    /**
     * @param DataSourceFactory $factory
     */
    public function setFactory(DataSourceFactory $factory)
    {
        $this->factory = $factory;
    }
}
