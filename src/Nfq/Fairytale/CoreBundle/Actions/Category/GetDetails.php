<?php

namespace Nfq\Fairytale\CoreBundle\Actions\Category;

use Im0rtality\ApiBundle\Actions\ActionResult;
use Im0rtality\ApiBundle\Actions\Instance\BaseInstanceAction;
use Im0rtality\ApiBundle\DataSource\DataSourceInterface;
use Im0rtality\ApiBundle\DataSource\Factory\DataSourceFactory;
use Nfq\Fairytale\CoreBundle\Actions\Book\BookHelper;
use Nfq\Fairytale\CoreBundle\Entity\Book;
use Nfq\Fairytale\CoreBundle\Entity\Category;
use Nfq\Fairytale\CoreBundle\Util\ImageResolvingInterface;
use Nfq\Fairytale\CoreBundle\Util\ImageResolvingTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetDetails extends BaseInstanceAction implements ImageResolvingInterface
{
    use ImageResolvingTrait;

    const NAME = 'category.details';

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
        /** @var Category $cat */
        $cat = $resource->read($identifier);
        if (!$cat) {
            throw new NotFoundHttpException();
        }

        $raw = [
            'id'    => $cat->getId(),
            'title' => $cat->getTitle(),
            'books' => $cat->getBooks()
                ->map(
                    function (Book $book) {
                        return BookHelper::toRaw($book, [$this, 'resolveImages']);
                    }
                )
                ->toArray()
        ];

        return ActionResult::instance(200, $raw);
    }
}
