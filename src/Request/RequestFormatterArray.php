<?php
namespace XP\Request;

/**
 * RequestFormatterArray 格式化数组
 *
 * @author      xiaopeng.lei 2016年4月29日11:51:47
 */

class RequestFormatterArray extends RequestFormatterBase implements RequestFormatter
{

    /**
     * 对数组格式化/数组转换
     * @param string $value 变量值
     * @param array $rule array('name' => '', 'type' => 'array', 'default' => '', 'format' => 'json/explode', 'separator' => '', 'min' => '', 'max' => '')
     * @return array
     */
    public function parse($value, $rule)
    {
        $rs = $value;
        if (!is_array($rs)) {
            $ruleFormat = !empty($rule['format']) ? strtolower($rule['format']) : '';
            if ($ruleFormat == 'explode') {
                $rs = explode(isset($rule['separator']) ? $rule['separator'] : ',', $rs);
            } elseif ($ruleFormat == 'json') {
                $rs = json_decode($rs, true);
                if (json_last_error() || null === $rs) {
                    throw new \Exception(isset($rule['message']) ? $rule['message'] : sprintf('%s  can not match json格式', $rule['name']), ExceptionCode::getConstant('JsonNotMatch'));
                }
            } else {
                $rs = array($rs);
            }
        }
        $rule['name'] = $rule['name'].'  to array count num ';
        $this->filterByRange(count($rs), $rule);
        return $rs;
    }
}
