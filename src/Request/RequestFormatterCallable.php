<?php
namespace XP\Request;


/**
 * RequestFormatterCallable 格式化回调类型
 *
 * @author      xiaopeng.lei 2016年4月29日11:51:47
 */

class RequestFormatterCallable extends RequestFormatterBase implements RequestFormatter
{

    /**
     * 对回调类型进行格式化
     *
     * @param mixed $value 变量值
     * @param array $rule array('callback' => '回调函数', 'params' => '第三个参数')
     * @return boolean/string 格式化后的变量
     *
     */
    public function parse($value, $rule)
    {
        $classpath = $this;
        if (!isset($rule['callback'])) {
            throw new \Exception(
                isset($rule['message']) ? $rule['message'] : sprintf('缺少callback参数 for rule:  %s ', $rule['name'])
                , ExceptionCode::getConstant('NoFunctionCallback')
            );
        } else {
            if (isset($rule['path'])) {
                $classpath = $rule['path'];
                vendor(str_replace(['.', '\\'], ['_', '.'], $rule['path']), 'vendor');
                $classpath = new $rule['path'];
                if (!is_callable(array($classpath, $rule['callback']))) {
                    throw new \Exception(
                        isset($rule['message']) ? $rule['message'] : sprintf('在提供的  %s 中，无callback方法 for rule: %s', $rule['path'], $rule['name'])
                        , ExceptionCode::getConstant('ThePathNoCallback')
                    );
                }
            } else {
                if (!is_callable(array($this, $rule['callback']))) {
                    throw new \Exception(
                        isset($rule['message']) ? $rule['message'] : sprintf('当前类中无callback方法 for rule: %s', $rule['path'], $rule['name'])
                        , ExceptionCode::getConstant('ThisNoCallback')
                    );
                }
            }

        }

        if (isset($rule['params'])) {
            return call_user_func(array($classpath, $rule['callback']), $value, $rule, $rule['params']);
        } else {
            return call_user_func(array($classpath, $rule['callback']), $value, $rule);
        }
    }
}
