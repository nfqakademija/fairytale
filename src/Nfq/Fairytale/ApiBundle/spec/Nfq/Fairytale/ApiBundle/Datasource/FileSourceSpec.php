<?php

namespace spec\Nfq\Fairytale\ApiBundle\DataSource;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @mixin \Nfq\Fairytale\ApiBundle\DataSource\FileSource
 */
class FileSourceSpec extends ObjectBehavior
{
    function let()
    {
        $this->setResource('foo');
        copy(__DIR__ . '/fixtures/data.original.json', __DIR__ . '/fixtures/data.json');
    }

    function letgo()
    {
        unlink(__DIR__ . '/fixtures/data.json');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\DataSource\FileSource');
    }

    function it_should_throw_if_file_not_loaded()
    {
        $this->shouldThrow('\LogicException')->during('index');
        $this->shouldThrow('\LogicException')->during('read', [1]);
    }

    function it_should_create_item(KernelInterface $locator)
    {
        $locator->locateResource('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);
        $this->load('data.json');

        $this->create(['name' => 'foo3',])
            ->shouldBe(['id' => 3, 'name' => 'foo3',]);
    }

    function it_should_know_if_file_is_loaded(KernelInterface $locator)
    {
        $locator->locateResource('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);

        $this->shouldNotBeLoaded();
        $this->load('data.json');
        $this->shouldBeLoaded();
    }

    function it_should_throw_if_file_is_missing(KernelInterface $locator)
    {
        $locator->locateResource('foo.json')->willReturn('foo.json');

        $this->setLocator($locator);

        $this->shouldThrow('Symfony\Component\HttpFoundation\File\Exception\FileException')
            ->during('load', ['foo.json']);
    }

    function it_should_return_index_of_data(KernelInterface $locator)
    {
        $locator->locateResource('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);
        $this->load('data.json');

        $this->index()->shouldBe(
            [
                [
                    'id'   => 1,
                    'name' => 'foo1',
                ],
                [
                    'id'   => 2,
                    'name' => 'foo2'
                ],
            ]
        );
    }

    function it_should_read_data_by_id(KernelInterface $locator)
    {
        $locator->locateResource('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);
        $this->load('data.json');

        $this->read(1)->shouldBe(
            [
                'id'   => 1,
                'name' => 'foo1',
            ]
        );
    }

    function it_should_update_data_by_id(KernelInterface $locator)
    {
        $locator->locateResource('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);
        $this->load('data.json');

        $this->update(1, ['bar' => 'qux'])->shouldBe(
            [
                'id'   => 1,
                'name' => 'foo1',
                'bar'  => 'qux',
            ]
        );

        $this->read(1)->shouldBe(
            [
                'id'   => 1,
                'name' => 'foo1',
                'bar'  => 'qux',
            ]
        );
    }

    function it_should_delete_data_by_id(KernelInterface $locator)
    {
        $locator->locateResource('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);
        $this->load('data.json');

        $this->delete(1)->shouldBe(true);
    }

    function it_should_handle_missing_id(KernelInterface $locator)
    {
        $locator->locateResource('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);
        $this->load('data.json');

        $this->read(3)->shouldBe(null);
        $this->update(3, [])->shouldBe(null);
        $this->delete(3)->shouldBe(false);
    }

    function it_should_be_countable(KernelInterface $locator)
    {
        $locator->locateResource('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);
        $this->load('data.json');

        $this->count()->shouldBe(2);
        $this->create([]);
        $this->count()->shouldBe(3);
    }
}
