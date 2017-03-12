<?php
namespace XP\Request;

/**
 * RequestFormatterInt 格式化整型
 *
 * @author      xiaopeng.lei 2016年4月29日11:51:47
 */

class RequestFormatterInt extends RequestFormatterBase implements RequestFormatter
{

    /**
     * 对整型进行格式化
     *
     * @param mixed $value 变量值
     * @param array $rule array('min' => '最小值', 'max' => '最大值')
     * @return int/string 格式化后的变量
     *
     */
    public function parse($value, $rule)
    {
        return intval($this->filterByRange(intval($value), $rule));
    }
}
