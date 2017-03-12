<?php
namespace XP\Request;


/**
 * RequestFormatterBase 公共基类
 *
 * - 提供基本的公共功能，便于子类重用
 *
 * @author      xiaopeng.lei 2016年4月29日11:51:47
 */

class RequestFormatterBase
{

    /**
     * 根据min，max范围进行控制，
     * 执行三个方法，
     * 1.判断规则
     * 2.检测最小
     * 3.检测最大
     * @method filterByRange
     * @param  mixed        $value 值
     * @param  mixed        $rule  规则
     * @return mixed   值
     */
    protected function filterByRange($value, $rule)
    {

        $this->filterRangeMinLessThanOrEqualsMax($rule);

        $this->filterRangeCheckMin($value, $rule);

        $this->filterRangeCheckMax($value, $rule);

        return $value;
    }

    protected function filterRangeMinLessThanOrEqualsMax($rule)
    {
        if (isset($rule['min']) && isset($rule['max']) && $rule['min'] > $rule['max']) {
            throw new \Exception(
                isset($rule['message']) ? $rule['message'] : sprintf('min should <= max, but now %s min = %s and max = %s', $rule['name'], $rule['min'], $rule['max'])
                , ExceptionCode::getConstant('MinGTMax')
            );
        }
    }

    protected function filterRangeCheckMin($value, $rule)
    {
        if (isset($rule['min']) && $value < $rule['min']) {
            throw new \Exception(
                isset($rule['message']) ? $rule['message'] : sprintf('%s should >= %d, but now %s = %d', $rule['name'], $rule['min'], $rule['name'], $value)
                , ExceptionCode::getConstant('MinGTValue')
            );
        }
    }

    protected function filterRangeCheckMax($value, $rule)
    {
        if (isset($rule['max']) && $value > $rule['max']) {
            throw new \Exception(isset($rule['message']) ? $rule['message'] : sprintf('%s should <= %d, but now %s = %d', $rule['name'], $rule['max'], $rule['name'], $value)
                , ExceptionCode::getConstant('MaxLTValue')
            );
        }
    }

    /**
     * 格式化枚举类型
     * @param string $value 变量值
     * @param array $rule array('name' => '', 'type' => 'enum', 'default' => '', 'range' => array(...))
     * @throws Exception_BadRequest
     */
    protected function formatEnumValue($value, $rule)
    {
        if (!in_array($value, $rule['range'])) {
            throw new \Exception(isset($rule['message']) ? $rule['message'] : sprintf('%s should be in %s, but now %s = %s', $rule['name'], implode(' or ', $rule['range']), $rule['name'], $value)
                , ExceptionCode::getConstant('NotBetween')
            );
        }
    }

}
