<?php
namespace core;
/**
 * 异常处理函数
 * @author suyq
 * @version 1.0
 */
class Exception {
    /**
     * 注册异常处理函数
     * @param \Exception $exception
     */
    public static function exceptionHandle(\Exception $exception) {
        $msg = self::replaceRoot($exception->getMessage())." in ".self::replaceRoot($exception->getFile())." on line ".$exception->getLine();
        $trace = $exception->getTrace();
        self::show($msg, self::listTrace($trace));
    }
    
    /**
     * 注册错误处理函数
     * @param integer $errno 错误的级别
     * @param string $errstr 错误的信息
     * @param string $errfile 发生错误的文件名
     * @param integer $errline 错误发生的行号
     * @param array $errcontext 错误发生时活动符号表
     */
    public static function errorHandle($errno, $errstr, $errfile, $errline, $errcontext) {
        $msg = self::replaceRoot($errstr)." in ".self::replaceRoot($errfile)." on line ".$errline;
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        self::show($msg, self::listTrace($trace));
    }
    
    /**
     * 处理trace数组
     * @param array $trace
     * @return array
     */
    private static function listTrace($trace) {
        $traceInfo = [];
        foreach ($trace as $t) {
            $traceInfo[] = [
                'file'=>isset($t['file']) ? self::replaceRoot($t['file']) : '',
                'line'=>isset($t['line']) ? $t['line'] : '',
                'code'=>isset($t['class']) ? $t['class'].$t['type'].$t['function'].'()' : (isset($t['function']) ? $t['function'].'()' : '')
            ];
        }
        return $traceInfo;
    }
    
    /**
     * 去掉错误信息的真实路径
     * @param string $path
     * @return mixed
     */
    private static function replaceRoot($path) {
        return str_replace([ROOT, '\\'], ['', '/'], $path);
    }
    
    /**
     * 获取错误视图文件
     * @return string
     */
    private static function getView() {
        return ROOT . "/view/trace.php";
    }
    
    /**
     * 显示错误trace
     * @param string $msg
     * @param array $trace
     */
    private static function show($msg, $trace = []) {
        while (ob_get_level() !== 0) ob_end_clean();
        ob_start();
        ob_implicit_flush(false);
        require self::getView();
        ob_end_flush();
        flush();
        die;
    }
}