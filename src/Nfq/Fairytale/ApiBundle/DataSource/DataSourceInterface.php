<?php
namespace Nfq\Fairytale\ApiBundle\DataSource;

interface DataSourceInterface
{
    /**
     * Get elements index (list) from element
     *
     * @param int|null    $limit
     * @param int|null    $offset
     * @param string|null $orderBy
     * @param string|null $order
     * @return array
     */
    public function index($limit = null, $offset = null, $orderBy = null, $order = null);

    /**
     * Read element from collection
     *
     * @param string $identifier
     * @return mixed
     */
    public function read($identifier);

    /**
     * Perform partial update on selected element
     *
     * @param string $identifier
     * @param array  $patch Associative array which is used to update given fields
     * @return mixed
     */
    public function update($identifier, $patch);

    /**
     * Removes element from collection
     *
     * @param string $identifier
     * @return bool
     */
    public function delete($identifier);

    /**
     * Creates element in collection
     *
     * @param array $data Data to create element from
     * @return mixed      Newly created element
     */
    public function create($data);

    /**
     * Returns number of elements in collection
     *
     * @return int
     */
    public function count();

    /**
     * Checks if specific object is owned by given user (used for ROLE_OWNER magic)
     *
     * @param object $object
     * @param object $user
     * @return bool True if user owns the object
     */
    public function isOwnedBy($object, $user);

    /**
     * Sets resource for current data source
     *
     * @param string $resource
     * @return $this
     */
    public function setResource($resource);

    /**
     * Returns the selected resource
     *
     * @return string
     */
    public function getResource();
}
