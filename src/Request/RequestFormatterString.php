<?php

namespace XP\Request;

/**
 * RequestFormatterString  格式化字符串
 *

 * @author      xiaopeng.lei 2016年4月29日11:51:47
 */

class RequestFormatterString extends RequestFormatterBase implements RequestFormatter
{

    /**
     * 对字符串进行格式化
     *
     * @param mixed $value 变量值
     * @param array $rule array('length' => ‘长度1,2’)
     * @return string 格式化后的变量
     *
     */
    public function parse($value, $rule)
    {
        $rs = $value;
        if (array_key_exists('length', $rule) && !empty($rule['length'])) {

            $value = strval($value);
            $len   = explode(',', $rule['length']);
            if (2 == count($len)) {
                $rule['min'] = $len[0] > $len[1] ? $len[1] : $len[0];
                $rule['max'] = $len[0] > $len[1] ? $len[0] : $len[1];
            } elseif (1 == count($len)) {
                $rule['max'] = $len[0];
            }
        }
        $rs = strval($this->filterByStrLen($value, $rule));
        if (array_key_exists('regex', $rule) && !empty($rule['regex'])) {
            $this->filterByRegex($rs, $rule);
        }
        return $rs;
    }

    /**
     * 根据字符串长度进行截取
     */
    protected function filterByStrLen($value, $rule)
    {
        $lenRule         = $rule;
        $lenRule['name'] = $lenRule['name'] . '.len';
        $lenValue        = strlen($value);
        $this->filterByRange($lenValue, $lenRule);
        return $value;
    }

    /**
     * 进行正则匹配
     */
    protected function filterByRegex($value, $rule)
    {
        //如果你看到此行报错，说明提供的正则表达式不合法
        if (preg_match($rule['regex'], $value) <= 0) {
            throw new \Exception(isset($rule['message']) ? $rule['message'] : sprintf('%s can not match %s', $rule['name'], $rule['regex']), ExceptionCode::getConstant('RegexNotMatch'));
        }
    }

}
