<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/12
 * Time: 上午11:08
 */

# 初始化程序

namespace Nova\framework;

# 引入配置文件
require CONF_DIR . '/config.php';

# 引入自动类加载
require 'autoloader.php';

# 初始化自动加载
AutoLoader::init();

# 启动session
Session::start();

# 启动核心处理程序
$core = new Core();
$core->run();