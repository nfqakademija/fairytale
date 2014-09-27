<?php

namespace Nfq\Fairytale\CoreBundle\Actions\Book;

use Im0rtality\ApiBundle\Actions\ActionResult;
use Im0rtality\ApiBundle\Actions\Instance\BaseInstanceAction;
use Im0rtality\ApiBundle\DataSource\DataSourceInterface;
use Im0rtality\ApiBundle\DataSource\Factory\DataSourceFactory;
use Im0rtality\ApiBundle\EventListener\AuthorizationListener;
use Nfq\Fairytale\CoreBundle\Entity\Book;
use Nfq\Fairytale\CoreBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class AddComment extends BaseInstanceAction
{
    const NAME = 'book.comment';

    /** @var  DataSourceFactory */
    private $factory;

    /** @var  SecurityContextInterface */
    private $security;

    /**
     * @param DataSourceFactory $factory
     */
    public function setFactory(DataSourceFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param SecurityContextInterface $security
     */
    public function setSecurity(SecurityContextInterface $security)
    {
        $this->security = $security;
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
        /** @var Book $book */
        $book = $resource->read($identifier);

        if (!$book) {
            throw new NotFoundHttpException();
        }

        $data = [
            'user'   => $this->security->getToken()->getUser()->getId(),
            'book'   => $identifier,
            'createdAt' => new \DateTime(),
            'content'   => $request->attributes->get(AuthorizationListener::API_REQUEST_PAYLOAD)['content']
        ];

        /** @var Comment $comment */
        $comment = $this->factory->create('Nfq\Fairytale\CoreBundle\Entity\Comment')->create($data);

        $raw = [
            'id'      => $comment->getId(),
            'content' => $comment->getContent(),
            'user'    => ['id' => $comment->getUser()->getId()],
            'book'    => ['id' => $comment->getBook()->getId()],
        ];
        return ActionResult::instance(201, $raw);
    }
}
