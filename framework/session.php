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