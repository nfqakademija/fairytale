<?php

namespace Nfq\Fairytale\CoreBundle\Actions\User;

use Im0rtality\ApiBundle\Actions\ActionResult;
use Im0rtality\ApiBundle\Actions\Instance\BaseInstanceAction;
use Im0rtality\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\CoreBundle\Actions\Book\BookHelper;
use Nfq\Fairytale\CoreBundle\Entity\Reservation;
use Nfq\Fairytale\CoreBundle\Entity\User;
use Nfq\Fairytale\CoreBundle\Util\ImageResolvingInterface;
use Nfq\Fairytale\CoreBundle\Util\ImageResolvingTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetReservation extends BaseInstanceAction implements ImageResolvingInterface
{
    use ImageResolvingTrait;

    const NAME = 'user.reservation';

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
            array_slice(
                $user->getReservations()->map(
                    function (Reservation $reservation) {
                        return BookHelper::toRaw($reservation->getBook(), [$this, 'resolveImages']);
                    }
                )->toArray(), 0, 1)
        );
    }
}
