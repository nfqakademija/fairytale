<?php

namespace Nfq\Fairytale\CoreBundle\Actions\Category;

use Im0rtality\ApiBundle\Actions\ActionResult;
use Im0rtality\ApiBundle\Actions\Collection\BaseCollectionAction;
use Im0rtality\ApiBundle\DataSource\DataSourceInterface;
use Im0rtality\ApiBundle\DataSource\Factory\DataSourceFactory;
use Nfq\Fairytale\CoreBundle\Actions\Book\BookHelper;
use Nfq\Fairytale\CoreBundle\Entity\Book;
use Nfq\Fairytale\CoreBundle\Util\ImageResolvingInterface;
use Nfq\Fairytale\CoreBundle\Util\ImageResolvingTrait;
use Symfony\Component\HttpFoundation\Request;

class GetNew extends BaseCollectionAction implements ImageResolvingInterface
{
    use ImageResolvingTrait;

    const NAME = 'category.new';

    /** @var  DataSourceFactory */
    private $factory;

    /**
     * @param DataSourceFactory $factory
     */
    public function setFactory(DataSourceFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Performs the action
     *
     * @param Request             $request
     * @param DataSourceInterface $resource
     * @return ActionResult
     */
    public function execute(Request $request, DataSourceInterface $resource)
    {
        $resource = $this->factory->create('Nfq\Fairytale\CoreBundle\Entity\Book');
        $books = $resource->index(9, 0, 'createdAt', 'DESC');
        if ($books instanceof \Traversable) {
            $books = iterator_to_array($books);
        }

        return ActionResult::instance(
            200,
            [
                'id'    => 'new',
                'title' => 'Naujos',
                'books' =>
                    array_map(
                        function (Book $book) {
                            return BookHelper::toRaw($book, [$this, 'resolveImages']);
                        },
                        $books
                    )
            ]
        );
    }
}
