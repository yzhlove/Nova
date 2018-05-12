<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/12
 * Time: 上午11:14
 */

namespace Nova\framework;

class Core {

    public function run() {
        $this->setReporting();
    }

    # 设置站点的错误报告等级
    public function setReporting() {

        if (DEBUG_MODE === true) {
            error_reporting(E_ALL);
            ini_set('display_errors','On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors','Off');
            ini_set('log_errors','On');
            ini_set('error_log',LOG_DIR . 'err.log');
        }
    }

    # 路由规则
    # $_REQUEST['act'] => class
    # $_REQUEST['st']  => method
    public function route() {
        if (!isset($_REQUEST['act']))
            $_REQUEST['act'] = 'index';
        if (!isset($_REQUEST['st']))
            $_REQUEST['st'] = 'main';
        # class
        $clazz = "Nova\\app\\controllers\\" . $this->strToClass($_REQUEST['act']);
        # 判断class是否存在
        if (!class_exists($clazz)) {
            header('HTTP/1.1 404 Not Found Controllers!');
            die($clazz);
        }
        # 实例化控制器
        $cont = new $clazz;
        # 判断method是否存在
        if (!method_exists($clazz,$_REQUEST['st'])) {
            header('HTTP/1.1 404 Not Found Method!');
            exit;
        }
        $cont->$_REQUEST['st']();
    }

    private function strToClass($str) {
        $clazz = '';
        $res = explode('_',$str);
        foreach ($res as $vale)
            $clazz .= ucfirst($vale);
        return $clazz;
    }

}