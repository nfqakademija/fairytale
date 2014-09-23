<?php

namespace Nfq\Fairytale\CoreBundle\Actions\User;

use Im0rtality\ApiBundle\Actions\ActionResult;
use Im0rtality\ApiBundle\Actions\Instance\BaseInstanceAction;
use Im0rtality\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\CoreBundle\Actions\Book\BookHelper;
use Nfq\Fairytale\CoreBundle\Entity\Comment;
use Nfq\Fairytale\CoreBundle\Entity\Rating;
use Nfq\Fairytale\CoreBundle\Entity\Reservation;
use Nfq\Fairytale\CoreBundle\Entity\User;
use Nfq\Fairytale\CoreBundle\Util\Doctrine;
use Nfq\Fairytale\CoreBundle\Util\ImageResolvingInterface;
use Nfq\Fairytale\CoreBundle\Util\ImageResolvingTrait;
use Symfony\Component\HttpFoundation\Request;

class GetDetails extends BaseInstanceAction implements ImageResolvingInterface
{
    use ImageResolvingTrait;

    const NAME = 'user.details';

    private $bookFilters = [];

    /**
     * @param string[] $bookFilters
     */
    public function setBookFilters($bookFilters)
    {
        $this->bookFilters = $bookFilters;
    }

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

        $raw = Doctrine::extractRaw($user);

        $raw['image'] = $this->resolveImages($user->getImage()->getFileName());
        $filters = $this->filters;
        $this->filters = $this->bookFilters;

        $raw['comments'] = $user->getComments()->map(function (Comment $comment) {
            $raw = Doctrine::extractRaw($comment);
            $raw['book'] = $comment->getBook()->getId();
            $raw['user'] = null;
            return $raw;
        })->toArray();

        $raw['ratings'] = $user->getRatings()->map(function (Rating $rating) {
            $raw = Doctrine::extractRaw($rating);
            $raw['book'] = $rating->getBook()->getId();
            $raw['user'] = null;
            return $raw;
        })->toArray();

        $raw['reservations'] = $user->getReservations()->map(function (Reservation $reservation) {
            $res = Doctrine::extractRaw($reservation);

            $res['book'] = BookHelper::toRaw($reservation->getBook(), [$this, 'resolveImages']);
            $res['user'] = null;

            return $res;
        })->toArray();

        $this->filters = $filters;
        return ActionResult::instance(200, $raw);
    }

}
