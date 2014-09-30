<?php


namespace Nfq\Fairytale\CoreBundle\Actions\Book;

use Nfq\Fairytale\CoreBundle\Entity\Author;
use Nfq\Fairytale\CoreBundle\Entity\Book;
use Nfq\Fairytale\CoreBundle\Entity\Category;
use Nfq\Fairytale\CoreBundle\Entity\Reservation;
use Nfq\Fairytale\CoreBundle\Util\Doctrine;

class BookHelper
{

    public static function toRaw(Book $book, $imageResolver)
    {
        /** @var Book $book */
        $book = Doctrine::extractEntity($book);
        $raw = Doctrine::extractRaw($book);

        $raw['status'] = self::getBookStatus($book);
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
            }
        )->toArray();

        $raw['image'] = $imageResolver($book->getImage()->getFileName());

        return $raw;
    }

    private static function getBookStatus(Book $book)
    {
        $reservations = $book->getReservations();
        /** @var Reservation $reservation */
        $dates = ['reserved' => [0], 'taken' => [0], 'returned' => [0]];
        foreach ($reservations as $reservation) {
            if ($reservation->getReturnedAt()) {
                if ($dates['returned'][0] < $reservation->getReturnedAt()->getTimestamp()) {
                    $dates['returned'] = [
                        $reservation->getReturnedAt()->getTimestamp(),
                        $reservation->getUser()->getId(),
                        $reservation->getTakenAt()->getTimestamp(),
                    ];
                }
            } elseif ($reservation->getTakenAt()) {
                $dates['taken'] = [
                    max($dates['taken'][0], $reservation->getTakenAt()->getTimestamp()),
                    $reservation->getUser()->getId(),
                ];
            } elseif ($reservation->getCreatedAt()) {
                $dates['reserved'] = [
                    max($dates['reserved'][0], $reservation->getCreatedAt()->getTimestamp()),
                    $reservation->getUser()->getId(),
                ];
            }
        }

        if ($dates['taken'][0] > $dates['returned'][0]) {
            if ($dates['reserved'][0] > $dates['taken'][0]) {
                return 'reserved';
            }
            return 'taken';
        } elseif ($dates['taken'][0] < $dates['returned'][0]) {
            if ($dates['reserved'][0] > $dates['returned'][2]) {
                return 'reserved';
            }
            return 'available';
        } elseif ($dates['reserved'][0] != 0) {
            return 'reserved';
        }

        return 'unknown';
    }
}
