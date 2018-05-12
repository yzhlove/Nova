<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/12
 * Time: 上午11:14
 */


namespace Nova\framework;

class Redis extends \Redis
{

    private static $_instance;
    public $_group_name = REDIS_ROOT;
    private $_temp_name = 'temp:';
    private $_redis;
    private $groupPath = REDIS_ROOT;
    public $redisKey = "";

    const _REDIS_HOST = "127.0.0.1";
    const _REDIS_PORT = "6379";

    public function __construct()
    {
        $this->_redis = new \Redis();
        $this->_redis->connect(static::_REDIS_HOST, static::_REDIS_PORT);
    }

    public static function getInstance($redisKey = REDIS_ROOT)
    {
        if (!(self::$_instance[$redisKey] instanceof self))
            self::$_instance[$redisKey] = new self;
        self::$_instance[$redisKey]->redisKey = $redisKey;
        return self::$_instance[$redisKey];
    }

    public function setGroup($groupName = '')
    {
        if (empty($groupName))
            return FALSE;
        $this->_group_name = $groupName;
        $this->groupPath = implode(':', explode('/', $groupName)) . ':';
        return TRUE;
    }

    public function set($key, $data, $groupName = "", $timeout = SESSION_TIMEOUT)
    {
        if (is_array($data))
            $data = json_encode($data);
        return $this->_redis->setex($this->getRedisKey($key, $groupName), $timeout, $data);
    }

    public function get($key, $groupName = "")
    {
        $temp = $this->_redis->get($this->getRedisKey($key, $groupName));
        $result = json_decode($temp, JSON_HEX_TAG);
        return empty($result) ? $temp : $result;
    }

    public function delete($key, $groupName = "")
    {
        return $this->_redis->delete($this->getRedisKey($key,$groupName));
    }

    public function exists($key,$groupName = "") {
        return $this->_redis->exists($this->getRedisKey($key,$groupName));
    }

    private function getRedisKey($key, $groupName)
    {
        if (empty($groupName))
            $groupName = $this->_group_name . $this->_temp_name;
        else
            $groupName = $this->_group_name . $groupName;
        return $groupName . $key;

    }

}