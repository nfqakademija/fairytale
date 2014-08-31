<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

class ActionResult
{
    const SIMPLE = 'simple';
    const INSTANCE = 'instance';
    const COLLECTION = 'collection';

    /** @var  mixed */
    protected $result;

    /** @var  string */
    protected $type;

    /** @var  int */
    protected $statusCode;

    /**
     * @param $statusCode
     * @param $result
     * @param $type
     */
    private function __construct($statusCode, $result, $type)
    {
        $this->result = $result;
        $this->statusCode = $statusCode;
        $this->type = $type;
    }

    /**
     * @param $statusCode
     * @param $result
     * @return ActionResult
     */
    public static function simple($statusCode, $result)
    {
        return new ActionResult($statusCode, $result, self::SIMPLE);
    }

    /**
     * @param $statusCode
     * @param $result
     * @return ActionResult
     */
    public static function instance($statusCode, $result)
    {
        return new ActionResult($statusCode, $result, self::INSTANCE);
    }

    /**
     * @param $statusCode
     * @param $result
     * @return ActionResult
     */
    public static function collection($statusCode, $result)
    {
        return new ActionResult($statusCode, $result, self::COLLECTION);
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
