<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/12
 * Time: 上午11:14
 */

namespace Nova\framework;

class AutoLoader {

    public static $_LOADER;

    public function __construct()
    {
        spl_autoload_register([$this,'import']);
    }

    public static function init() {
        if (static::$_LOADER == NULL)
            static::$_LOADER = new self();
        return static::$_LOADER;
    }

    public function import($clazz) {
        $path = explode('\\',substr($clazz,strlen(APP_NAME)));
        $filepath = ROOT_DIR . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR,$path) . '.php';
        if (is_file($filepath))
            require $filepath;
    }

}


