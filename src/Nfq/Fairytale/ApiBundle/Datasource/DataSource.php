<?php
namespace Nfq\Fairytale\ApiBundle\Datasource;

interface DataSource
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
     * @param int|string $identifier
     * @return array
     */
    public function read($identifier);

    /**
     * Perform partial update on selected element
     *
     * @param int|string $identifier
     * @param array      $patch Associative array which is used to update given fields
     * @return array
     */
    public function update($identifier, $patch);

    /**
     * Removes element from collection
     *
     * @param int|string $identifier
     * @return bool
     */
    public function delete($identifier);

    /**
     * Creates element in collection
     *
     * @param array $data Data to create element from
     * @return array      Newly created element
     */
    public function create($data);

    /**
     * Returns number of elements in collection
     *
     * @return int
     */
    public function count();

    /**
     * Sets resource for current data source
     *
     * @param string $resource
     * @return $this
     */
    public function setResource($resource);
}
