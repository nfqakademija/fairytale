<?php

namespace Nfq\Fairytale\ApiBundle\Datasource;

use Doctrine\ORM\EntityManager;

class DoctrineOrmSource implements DataSource
{
    /** @var  ClassFactory */
    protected $classFactory;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  string */
    protected $resource;

    /**
     * @inheritdoc
     */
    public function index($limit = null, $offset = null, $orderBy = null, $order = null)
    {
        $sort = $orderBy && $order ? [$orderBy => $order] : null;

        return $this->entityManager->getRepository($this->resource)->findBy([], $sort, $limit, $offset);
    }

    /**
     * @inheritdoc
     */
    public function read($identifier)
    {
        return $this->entityManager->getRepository($this->resource)->find($identifier);
    }

    /**
     * @inheritdoc
     */
    public function update($identifier, $patch)
    {
        $object = $this->entityManager->getPartialReference($this->resource, $identifier);

        $ref = new \ReflectionObject($object);
        foreach ($patch as $key => $value) {
            $property = $ref->getProperty($key);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        }

        $this->entityManager->persist($object);
        $this->entityManager->flush();

        return $object;
    }

    /**
     * @inheritdoc
     */
    public function delete($identifier)
    {
        $object = $this->entityManager->getPartialReference($this->resource, $identifier);
        $this->entityManager->remove($object);
        $this->entityManager->flush();
    }

    /**
     * @inheritdoc
     */
    public function create($data)
    {
        $object = $this->classFactory->create($this->resource);

        $ref = new \ReflectionObject($object);
        foreach ($data as $key => $value) {
            $property = $ref->getProperty($key);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        }

        $this->entityManager->persist($object);
        $this->entityManager->flush();

        return $object;
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return $this->entityManager->createQuery("SELECT COUNT(r) FROM {$this->resource} r")->getSingleScalarResult();
    }

    /**
     * @inheritdoc
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setClassFactory(ClassFactory $factory)
    {
        $this->classFactory = $factory;
    }
}
