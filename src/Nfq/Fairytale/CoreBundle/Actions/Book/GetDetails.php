<?php

namespace Nfq\Fairytale\CoreBundle\Actions\Book;

use Nfq\Fairytale\ApiBundle\Actions\ActionResult;
use Nfq\Fairytale\ApiBundle\Actions\Instance\BaseInstanceAction;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Nfq\Fairytale\CoreBundle\Entity\Author;
use Nfq\Fairytale\CoreBundle\Entity\Book;
use Nfq\Fairytale\CoreBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

class GetDetails extends BaseInstanceAction
{
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

        $raw['categories'] = array_map(
            function (Category $category) {
                return [
                    'id'    => $category->getId(),
                    'title' => $category->getTitle(),
                ];
            },
            $raw['categories']->toArray()
        );

        $raw['authors'] = array_map(
            function (Author $author) {
                return [
                    'id'   => $author->getId(),
                    'name' => $author->getName(),
                ];
            },
            $raw['authors']->toArray()
        );

        $raw = array_replace($raw, [
            'status' => 'unknown', // 'available', 'reserved', 'taken',
        ]);
        return ActionResult::instance(200, $raw);
    }
}
