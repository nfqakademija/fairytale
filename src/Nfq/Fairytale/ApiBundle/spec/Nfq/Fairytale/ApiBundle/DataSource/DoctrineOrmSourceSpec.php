<?php

namespace spec\Nfq\Fairytale\ApiBundle\DataSource;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Nfq\Fairytale\ApiBundle\DataSource\ClassFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \Nfq\Fairytale\ApiBundle\DataSource\DoctrineOrmSource
 */
class DoctrineOrmSourceSpec extends ObjectBehavior
{
    function let()
    {
        $this->setResource('foo');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\DataSource\DoctrineOrmSource');
    }

    function it_should_read_entity(ManagerRegistry $registry, EntityManager $em, ObjectRepository $repo)
    {
        $repo->find(1)->willReturn(['foo' => 1]);
        $em->getRepository('foo')->willReturn($repo);
        $registry->getManager(null)->willReturn($em);

        $this->setRegistry($registry);
        $this->read(1)->shouldBe(['foo' => 1]);
    }

    function it_should_index_entity(ManagerRegistry $registry, EntityManager $em, ObjectRepository $repo)
    {
        $repo->findBy([], ['id' => 'DESC'], 1, 2)->willReturn([1, 2, 3]);
        $em->getRepository('foo')->willReturn($repo);
        $registry->getManager(null)->willReturn($em);

        $this->setRegistry($registry);
        $this->index(1, 2, 'id', 'DESC')->shouldBe([1, 2, 3]);
    }

    function it_should_count_entities(ManagerRegistry $registry, EntityManager $em, AbstractQuery $query)
    {
        $query->getSingleScalarResult()->willReturn(42);
        $em->createQuery('SELECT COUNT(r) FROM foo r')->willReturn($query);
        $registry->getManager(null)->willReturn($em);

        $this->setRegistry($registry);
        $this->count()->shouldBe(42);
    }

    function it_should_delete_entity(ManagerRegistry $registry, EntityManager $em)
    {
        $em->getPartialReference('foo', 1)->willReturn(42);
        $em->remove(42)->shouldBeCalled();
        $em->flush()->shouldBeCalled();
        $registry->getManager(null)->willReturn($em);

        $this->setRegistry($registry);
        $this->delete(1)->shouldBe(true);
    }

    function it_should_create_entity(
        ManagerRegistry $registry,
        EntityManager $em,
        ClassFactory $factory,
        ObjectRepository $repository
    ) {
        $entity = (object)['foo' => null, 'bar' => null];

        $repository->getClassName()->willReturn('foo');
        $factory->create('foo')->willReturn($entity);
        $em->getRepository('foo')->willReturn($repository);
        $em->persist($entity)->shouldBeCalled();
        $em->flush()->shouldBeCalled();
        $registry->getManager(null)->willReturn($em);

        $this->setRegistry($registry);
        $this->setClassFactory($factory);

        $entity->foo = 1;
        $entity->bar = 2;

        $this->create(['foo' => 1, 'bar' => 2])->shouldBe($entity);
    }

    function it_should_update_entity(ManagerRegistry $registry, EntityManager $em)
    {
        $entity = (object)['foo' => null, 'bar' => null];

        $em->getPartialReference('foo', 1)->willReturn($entity);
        $em->persist($entity)->shouldBeCalled();
        $em->flush()->shouldBeCalled();
        $registry->getManager(null)->willReturn($em);

        $this->setRegistry($registry);
        $this->update(1, ['foo' => 1, 'bar' => 2])->shouldBeKindaSame((object)['foo' => 1, 'bar' => 2]);
    }

    function getMatchers()
    {
        return [
            'beKindaSame' => function ($actual, $expected) {
                return serialize($actual) == serialize($expected);
            }
        ];
    }
}
