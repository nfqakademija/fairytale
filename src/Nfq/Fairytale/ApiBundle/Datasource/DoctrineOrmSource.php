<?php

namespace Nfq\Fairytale\ApiBundle\Datasource;

use Doctrine\ORM\EntityManager;

class DoctrineOrmSource implements DataSourceInterface
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

        $this->populateAndPersist($object, $patch);

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
        return true;
    }

    /**
     * @inheritdoc
     */
    public function create($data)
    {
        $object = $this->classFactory->create($this->entityManager->getRepository($this->resource)->getClassName());

        $this->populateAndPersist($object, $data);

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
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getResource()
    {
        return $this->resource;
    }

    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
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

        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
