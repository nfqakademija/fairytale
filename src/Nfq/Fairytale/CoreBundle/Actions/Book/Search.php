<?php

namespace Nfq\Fairytale\CoreBundle\Actions\Book;

use Doctrine\ORM\EntityManager;
use Im0rtality\ApiBundle\Actions\ActionResult;
use Im0rtality\ApiBundle\Actions\Collection\BaseCollectionAction;
use Im0rtality\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\CoreBundle\Entity\Book;
use Nfq\Fairytale\CoreBundle\Util\ImageResolvingInterface;
use Nfq\Fairytale\CoreBundle\Util\ImageResolvingTrait;
use Symfony\Component\HttpFoundation\Request;

class Search extends BaseCollectionAction implements ImageResolvingInterface
{
    use ImageResolvingTrait;

    const NAME = 'book.search';

    /**
     * Performs the action
     *
     * @param Request             $request
     * @param DataSourceInterface $resource
     * @param string              $identifier
     * @return ActionResult
     */
    public function execute(Request $request, DataSourceInterface $resource)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $resource->getDriver();
        $books = $entityManager->getRepository($resource->getResource())->createQueryBuilder('b')
            ->where('b.title LIKE :query')
            ->setMaxResults(5)
            ->setParameter('query', '%' . $request->query->get('q') . '%')
            ->getQuery()
            ->getResult();

        return ActionResult::collection(
            200,
            array_map(
                function (Book $book) {
                    return BookHelper::toRaw($book, [$this, 'resolveImages']);
                },
                $books
            )
        );
    }
}
