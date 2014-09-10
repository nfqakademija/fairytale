<?php

namespace Nfq\Fairytale\ApiBundle\DataSource;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class DoctrineOrmSource implements DataSourceInterface
{
    /** @var  ClassFactory */
    protected $classFactory;

    /** @var  ManagerRegistry */
    protected $registry;

    /** @var  string|null */
    protected $managerName;

    /** @var  string */
    protected $resource;

    /**
     * @param ManagerRegistry $registry
     */
    public function setRegistry(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param null|string $managerName
     */
    public function setManagerName($managerName)
    {
        $this->managerName = $managerName;
    }

    /**
     * @inheritdoc
     */
    public function index($limit = null, $offset = null, $orderBy = null, $order = null)
    {
        $sort = $orderBy && $order ? [$orderBy => $order] : null;

        return $this->getManager()->getRepository($this->resource)->findBy([], $sort, $limit, $offset);
    }

    /**
     * @inheritdoc
     */
    public function read($identifier)
    {
        return $this->getManager()->getRepository($this->resource)->find($identifier);
    }

    /**
     * @inheritdoc
     */
    public function update($identifier, $patch)
    {
        $object = $this->getManager()->getPartialReference($this->resource, $identifier);

        $this->populateAndPersist($object, $patch);

        return $object;
    }

    /**
     * @inheritdoc
     */
    public function delete($identifier)
    {
        $entityManager = $this->getManager();

        $object = $entityManager->getPartialReference($this->resource, $identifier);
        $entityManager->remove($object);
        $entityManager->flush();
        return true;
    }

    /**
     * @inheritdoc
     */
    public function create($data)
    {
        $object = $this->classFactory->create(
            $this->getManager()->getRepository($this->resource)->getClassName()
        );

        $this->populateAndPersist($object, $data);

        return $object;
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        $query = "SELECT COUNT(r) FROM {$this->resource} r";
        return $this->getManager()->createQuery($query)->getSingleScalarResult();
    }

    /**
     * Returns elements that matches the query
     *
     * @param mixed $query
     * @return array
     */
    public function query($query)
    {
        return $this->getManager()->getRepository($this->resource)->findBy($query);
    }

    /**
     * @inheritdoc
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getResource()
    {
        return $this->resource;
    }

    public function setClassFactory(ClassFactory $factory)
    {
        $this->classFactory = $factory;
    }

    /**
     * @param $object
     * @param $data
     */
    private function populateObject($object, $data)
    {
        $ref = new \ReflectionObject($object);
        foreach ($data as $key => $value) {
            $property = $ref->getProperty($key);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        }
    }

    /**
     * @param $object
     * @param $data
     */
    private function populateAndPersist($object, $data)
    {
        $this->populateObject($object, $data);

        $entityManager = $this->getManager();
        $entityManager->persist($object);
        $entityManager->flush();
    }

    /**
     * @return EntityManager
     * @throws \InvalidArgumentException
     */
    private function getManager()
    {
        $manager = $this->registry->getManager($this->managerName);
        if (!$manager instanceof EntityManager) {
            throw new \InvalidArgumentException('DoctrineOrmSource only supports ORM EntityManager as $manager');
        }
        return $manager;
    }
}
