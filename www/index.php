<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/12
 * Time: 上午11:00
 */

# 自己动手实现mvc
define('APP_NAME','Nova');
define('ROOT_DIR',__DIR__ . '/..');
define('APP_DIR',ROOT_DIR . '/app');
define('CONF_DIR',ROOT_DIR . '/config');
define('FRAME_WORK_DIR',ROOT_DIR . '/framework');
define('LOG_DIR',ROOT_DIR . '/logs');
define('WWW_DIR',__DIR__ . '/');


# 设置时区
define('TIMEZONE','Asia/Shanghai');
# 初始化php.ini文件里面的时区为上海
ini_set('data.timezone',TIMEZONE);

# 引入初始化小程序
require FRAME_WORK_DIR . '/init.php';




