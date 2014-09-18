<?php

namespace Nfq\Fairytale\CoreBundle\Actions\Book;

use Nfq\Fairytale\ApiBundle\Actions\ActionResult;
use Nfq\Fairytale\ApiBundle\Actions\Instance\BaseInstanceAction;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\CoreBundle\Entity\Author;
use Nfq\Fairytale\CoreBundle\Entity\Book;
use Nfq\Fairytale\CoreBundle\Entity\Category;
use Nfq\Fairytale\CoreBundle\Util\ImageResolvingInterface;
use Nfq\Fairytale\CoreBundle\Util\ImageResolvingTrait;
use Symfony\Component\HttpFoundation\Request;

class GetDetails extends BaseInstanceAction implements ImageResolvingInterface
{
    use ImageResolvingTrait;

    const NAME = 'book.details';

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
        /** @var Book $book */
        $book = $resource->read($identifier);

        // convert Book object to assoc array so we can inject more data
        $ref = new \ReflectionObject($book);
        $props = $ref->getProperties();

        $raw = [];
        foreach ($props as $prop) {
            $prop->setAccessible(true);
            $raw[$prop->getName()] = $prop->getValue($book);
        }

        $raw['categories'] =
            $book->getCategories()->map(function (Category $category) {
                return [
                    'id'    => $category->getId(),
                    'title' => $category->getTitle(),
                ];
            })->toArray();

        $raw['authors'] = $book->getAuthors()->map(function (Author $author) {
            return [
                'id'   => $author->getId(),
                'name' => $author->getName(),
            ];
        })->toArray();

        $raw['image'] = $this->resolveImages($book->getImage()->getFileName());

        $raw = array_replace($raw, [
            'status' => 'unknown', // 'available', 'reserved', 'taken',
        ]);
        return ActionResult::instance(200, $raw);
    }
}
