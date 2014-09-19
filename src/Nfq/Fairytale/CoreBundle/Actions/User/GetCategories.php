<?php

namespace Nfq\Fairytale\CoreBundle\Actions\User;

use Im0rtality\ApiBundle\Actions\ActionResult;
use Im0rtality\ApiBundle\Actions\Instance\BaseInstanceAction;
use Im0rtality\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\CoreBundle\Entity\Reservation;
use Nfq\Fairytale\CoreBundle\Entity\User;
use Nfq\Fairytale\CoreBundle\Util\Arrays;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetCategories extends BaseInstanceAction
{
    const NAME = 'user.categories';

    /**
     * Performs the action
     *
     * @param Request $request
     * @param DataSourceInterface $resource
     * @param string $identifier
     * @return ActionResult
     */
    public function execute(Request $request, DataSourceInterface $resource, $identifier)
    {
        /** @var User $user */
        $user = $resource->read($identifier);
        if (!$user) {
            throw new NotFoundHttpException();
        }

        /** @var Category[][] $bookCategories */
        $bookCategories = $user->getReservations()->map(
            function (Reservation $reservation) {
                return $reservation->getBook()->getCategories()->toArray();
            }
        )->toArray();

        if (count($bookCategories)) {
            // flatten
            $categories = call_user_func_array('array_merge', $bookCategories);
            $uniqueCategories = array_combine(Arrays::pick($categories, 'getId'), $categories);

            $idCounts = array_count_values(Arrays::pick($categories, 'getId'));
            arsort($idCounts);
            $histogram = array_map(
                function ($id) use ($uniqueCategories) {
                    return $uniqueCategories[$id];
                },
                array_keys($idCounts)
            );
        } else {
            $histogram = [];
        }

        return ActionResult::collection(
            200,
            $histogram
        );
    }
}
