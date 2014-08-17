<?php

namespace spec\Nfq\Fairytale\ApiBundle\Datasource;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Config\FileLocator;

/**
 * @mixin \Nfq\Fairytale\ApiBundle\Datasource\FileSource
 */
class FileSourceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\Datasource\FileSource');
    }

    function it_should_throw_if_file_not_loaded()
    {
        $this->shouldThrow('\LogicException')->during('index', ['foo']);
        $this->shouldThrow('\LogicException')->during('read', ['foo', 1]);
    }

    function it_should_know_if_file_is_loaded(FileLocator $locator)
    {
        $locator->locate('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);

        $this->shouldNotBeLoaded();
        $this->load('data.json');
        $this->shouldBeLoaded();
    }

    function it_should_throw_if_file_is_missing(FileLocator $locator)
    {
        $locator->locate('foo.json')->willReturn('foo.json');

        $this->setLocator($locator);

        $this->shouldThrow('Symfony\Component\HttpFoundation\File\Exception\FileException')
            ->during('load', ['foo.json']);
    }

    function it_should_return_index_of_data(FileLocator $locator)
    {
        $locator->locate('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);
        $this->load('data.json');

        $this->index('foo')->shouldHaveCount(2);
        $this->index('foo')->shouldBe(
            [
                [
                    'id'   => 1,
                    'name' => 'foo1',
                ],
                [
                    'id'   => 3,
                    'name' => 'foo3'
                ],
            ]
        );
    }

    function it_should_read_data_by_id(FileLocator $locator)
    {
        $locator->locate('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);
        $this->load('data.json');

        $this->read('foo', 1)->shouldBe(
            [
                'id'   => 1,
                'name' => 'foo1',
            ]
        );
    }

    function it_should_update_data_by_id(FileLocator $locator)
    {
        $locator->locate('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);
        $this->load('data.json');

        $this->update('foo', 1, ['bar' => 'qux'])->shouldBe(
            [
                'id'   => 1,
                'name' => 'foo1',
                'bar'  => 'qux',
            ]
        );

        $this->read('foo', 1)->shouldBe(
            [
                'id'   => 1,
                'name' => 'foo1',
                'bar'  => 'qux',
            ]
        );
    }

    function it_should_delete_data_by_id(FileLocator $locator)
    {
        $locator->locate('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);
        $this->load('data.json');

        $this->delete('foo', 1)->shouldBe(true);
    }

    function it_should_handle_missing_id(FileLocator $locator)
    {
        $locator->locate('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);
        $this->load('data.json');

        $this->read('foo', 2)->shouldBe(null);
        $this->update('foo', 2, [])->shouldBe(null);
        $this->delete('foo', 2)->shouldBe(false);
    }

    function it_should_create_item(FileLocator $locator)
    {
        $locator->locate('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);
        $this->load('data.json');

        $this->shouldThrow('InvalidArgumentException')->during('create', ['foo', ['name' => 'foo2', 'id' => 1]]);

        $this->create('foo', ['id' => 2, 'name' => 'foo2',])
            ->shouldBe(['id' => 2, 'name' => 'foo2',]);
    }

    function it_should_be_countable(FileLocator $locator)
    {
        $locator->locate('data.json')->willReturn(__DIR__ . '/fixtures/data.json');
        $this->setLocator($locator);
        $this->load('data.json');

        $this->count('foo')->shouldBe(2);
        $this->create('foo', []);
        $this->count('foo')->shouldBe(3);
    }
}
