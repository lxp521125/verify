<?php
namespace XP\Request;

/**
 * RequestFormatterBoolean 格式化布尔值
 *
 * @author      xiaopeng.lei 2016年4月29日11:51:47
 */

class RequestFormatterBoolean extends RequestFormatterBase implements RequestFormatter
{

    /**
     * 对布尔型进行格式化
     *
     * @param mixed $value 变量值
     * @param array $rule
     * @return boolean
     *
     */
    public function parse($value, $rule)
    {
        $rs = $value;

        if (!is_bool($value)) {
            if (is_numeric($value)) {
                $rs = $value > 0 ? true : false;
            } else if (is_string($value)) {
                $rs = in_array(strtolower($value), array('ok', 'true', 'success', 'on', 'yes'))
                ? true : false;
            } else {
                $rs = $value ? true : false;
            }
        }

        return $rs;
    }
}
