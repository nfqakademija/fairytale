<?php

namespace Nfq\Fairytale\CoreBundle\Actions\User;

use Im0rtality\ApiBundle\Actions\ActionResult;
use Im0rtality\ApiBundle\Actions\Instance\BaseInstanceAction;
use Im0rtality\ApiBundle\DataSource\DataSourceInterface;
use Im0rtality\ApiBundle\DataSource\Factory\DataSourceFactory;
use Nfq\Fairytale\CoreBundle\Entity\Reservation;
use Nfq\Fairytale\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetReturnedBooks extends BaseInstanceAction
{
    const NAME = 'user.returned';

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
        /** @var User $user */
        $user = $resource->read($identifier);
        if (!$user) {
            throw new NotFoundHttpException();
        }
        return ActionResult::collection(
            200,
            $user->getReservations()
                ->filter(
                    function (Reservation $reservation) {
                        return $reservation->getReturnedAt() !== null;
                    }
                )
                ->map(
                    function (Reservation $reservation) {
                        return $reservation->getBook();
                    }
                )
                ->toArray()
        );
    }

    /**
     * @param DataSourceFactory $factory
     */
    public function setFactory(DataSourceFactory $factory)
    {
        $this->factory = $factory;
    }
}
