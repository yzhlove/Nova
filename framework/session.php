<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/12
 * Time: 上午11:18
 */

namespace Nova\framework;

class Session {

    private static $_SESSION_ID;
    private static $_REDIS_CACHE;
    private static $_USER_IP;

    public static function start() {
        session_set_save_handler(
            [__CLASS__,'open'],
            [__CLASS__,'close'],
            [__CLASS__,'read'],
            [__CLASS__,'write'],
            [__CLASS__,'destroy'],
            [__CLASS__,'gc']
        );
    }

    public static function open() {
        self::generateSessionSid();
        self::$_REDIS_CACHE = Redis::getInstance();
        return true;
    }

    public static function read() {
        $session_value = self::$_REDIS_CACHE->get(self::$_SESSION_ID,SESSION_TABLE_NAME);
        if ($session_value)
            $_SESSION = $session_value;
        return true;
    }

    public static function write() {
        if (!empty($_SESSION))
            self::$_REDIS_CACHE->set(self::$_SESSION_ID,$_SESSION,SESSION_TABLE_NAME,SESSION_TIMEOUT);
        return true;
    }

    public static function destory() {
        if (self::$_REDIS_CACHE->exists(self::$_SESSION_ID,SESSION_TABLE_NAME))
            self::$_REDIS_CACHE->delete(self::$_SESSION_ID,SESSION_TABLE_NAME);
        setcookie(SESSION_NAME,self::$_SESSION_ID,1,COOK_PATH,COOK_DOMAIN,FALSE);
        return true;
    }

    public static function close() {
        return true;
    }

    public static function gc() {
        return true;
    }

    # 生成一个session_sid
    public static function generateSessionSid() {
        self::$_USER_IP = Tools::remoteIP();
        $arrayList = $_COOKIE;
        if (is_null(self::$_SESSION_ID) && empty($arrayList[SESSION_NAME])) {
            self::$_SESSION_ID = function_exists('com_create_guid')
                ? md5(self::$_USER_IP . com_create_guid()) : md5(self::$_USER_IP . uniqid(mt_rand(),true));
            self::$_SESSION_ID .= sprintf('%08x',crc32(self::$_SESSION_ID));
            setcookie(SESSION_NAME,self::$_SESSION_ID,time() + SESSION_TIMEOUT,COOK_PATH,COOK_DOMAIN,FALSE);
            $_COOKIE[SESSION_NAME] = self::$_SESSION_ID;
        } else
            self::$_SESSION_ID = $arrayList[SESSION_NAME];
        return self::$_SESSION_ID;
    }

}