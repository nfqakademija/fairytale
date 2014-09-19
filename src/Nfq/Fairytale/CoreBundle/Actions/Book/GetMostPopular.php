<?php

namespace Nfq\Fairytale\CoreBundle\Actions\Book;

use Doctrine\Common\Collections\Collection;
use Im0rtality\ApiBundle\Actions\ActionResult;
use Im0rtality\ApiBundle\Actions\Collection\BaseCollectionAction;
use Im0rtality\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\CoreBundle\Entity\Book;
use Nfq\Fairytale\CoreBundle\Util\ImageResolvingInterface;
use Nfq\Fairytale\CoreBundle\Util\ImageResolvingTrait;
use Symfony\Component\HttpFoundation\Request;

class GetMostPopular extends BaseCollectionAction implements ImageResolvingInterface
{
    use ImageResolvingTrait;

    const NAME = 'books.popular';

    /**
     * Performs the action
     *
     * @param Request             $request
     * @param DataSourceInterface $resource
     * @return ActionResult
     */
    public function execute(Request $request, DataSourceInterface $resource)
    {
        $books = $resource->index();
        if ($books instanceof \Traversable) {
            $books = iterator_to_array($books);
        }

        usort($books, function (Book $left, Book $right) {
                return $left->getReservations()->count() - $right->getReservations()->count();
            }
        );

        $popular = array_slice($books, 0, 10);

        return ActionResult::collection(
            200,
            array_map(function (Book $book) {
                    return BookHelper::toRaw($book, [$this, 'resolveImages']);
                }, $popular
            )
        );
    }
}
