<?php
namespace XP\Request;

/**
 * RequestFormatterFloat 格式化浮点类型
 *
 * @author      xiaopeng.lei 2016年4月29日11:51:47
 */

class RequestFormatterFloat extends RequestFormatterBase implements RequestFormatter
{

    /**
     * 对浮点型进行格式化
     *
     * @param mixed $value 变量值
     * @param array $rule array('min' => '最小值', 'max' => '最大值')
     * @return float/string 格式化后的变量
     *
     */
    public function parse($value, $rule)
    {
        if(isset($rule['precision'])){
            return floatval($this->filterByRange(floatval(round(floatval($value),$rule['precision'])), $rule));
        }else{
            return floatval($this->filterByRange(floatval($value), $rule));
        }
        
    }
}
