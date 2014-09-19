<?php


namespace Nfq\Fairytale\CoreBundle\Actions\Book;

use Nfq\Fairytale\CoreBundle\Entity\Author;
use Nfq\Fairytale\CoreBundle\Entity\Book;
use Nfq\Fairytale\CoreBundle\Entity\Category;
use Nfq\Fairytale\CoreBundle\Util\Doctrine;

class BookHelper
{

    public static function toRaw(Book $book, $imageResolver)
    {
        $book = Doctrine::extractEntity($book);
        $raw = Doctrine::extractRaw($book);

        $raw['categories'] = $book->getCategories()->map(
            function (Category $category) {
                return ['id' => $category->getId(), 'title' => $category->getTitle()];
            }
        )->toArray();

        $raw['authors'] = $book->getAuthors()->map(
            function (Author $author) {
                return [
                    'id'   => $author->getId(),
                    'name' => $author->getName(),
                ];
            })->toArray();

        $raw['image'] = $imageResolver($book->getImage()->getFileName());

        return $raw;
    }
} 
