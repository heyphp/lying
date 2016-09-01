<?php
use core\Router;
use core\Session;
use core\Logger;
use core\Request;
use core\Encrypt;
use core\Cookie;
use core\Http;
/**
 * App
 * @author suyq
 * @version 1.0
 */
final class App {
    /**
     * 全局配置
     * @var array
     */
    public static $config;
    
    /**
     * 数据库连接
     * @var \PDO
     */
    private static $db;
    
    /**
     * 自动加载,文件路径和命名空间对应
     * @param string $class 类名
     * @throws \Exception
     */
    public static function autoload($class) {
        $classFile = ROOT . '/' . str_replace('\\', '/', $class) . '.php';
        if (!file_exists($classFile)) throw new \Exception("Class $class not found", 404);
        require_once($classFile);
    }
    
    /**
     * 主入口
     */
    public static function run() {
        ob_start();
        ob_implicit_flush(false);
        list($class, $action) = Router::parse();
        if (!method_exists($class, $action)) throw new \Exception("Method $class->$action() not found", 404);
        echo((new $class())->$action());
        ob_end_flush();
        flush();
    }
    
    /**
     * 返回PDO实例
     * @return PDO
     */
    public static function db() {
        if (!self::$db instanceof \PDO) {
            self::$db = new \PDO(self::$config['db']['dsn'], self::$config['db']['user'], self::$config['db']['pwd']);
        }
        return self::$db;
    }
    
    /**
     * session操作
     * @return Session
     */
    public static function session() {
        return Session::getInstance();
    }
    
    /**
     * 日志
     * @return \core\Logger
     */
    public static function logger() {
        return Logger::getInstance();
    }
    
    /**
     * 请求
     * @return \core\Request
     */
    public static function request() {
        return Request::getInstance();
    }
    
    /**
     * 加密类
     * @return \core\Encrypt
     */
    public static function encrypt() {
        return Encrypt::getInstance();
    }
    
    /**
     * cookie类
     * @return \core\Cookie
     */
    public static function cookie() {
        return Cookie::getInstance();
    }
    
    /**
     * cURL http请求
     * @return \core\Http
     */
    public static function http() {
        return Http::getInstance();
    }
}
App::$config = require ROOT . '/config/config.php';
spl_autoload_register("App::autoload");
set_exception_handler([core\Exception::class, 'exceptionHandle']);
set_error_handler([core\Exception::class, 'errorHandle']);