<?php
namespace XP\Request;


/**
 * RequestFormatterEnum 格式化枚举类型
 *
 * @author      xiaopeng.lei 2016年4月29日11:51:47
 */

class RequestFormatterEnum extends RequestFormatterBase implements RequestFormatter
{

    /**
     * 检测枚举类型
     * @param string $value 变量值
     * @param array $rule array('name' => '', 'type' => 'enum', 'default' => '', 'range' => array(...))
     * @return 当不符合时返回$rule
     */
    public function parse($value, $rule)
    {
        $this->formatEnumRule($rule);

        $this->formatEnumValue($value, $rule);

        return $value;
    }

    /**
     * 检测枚举规则的合法性
     * @param array $rule array('name' => '', 'type' => 'enum', 'default' => '', 'range' => array(...))
     * @throws throw new \Exception
     */
    protected function formatEnumRule($rule)
    {
        if (!isset($rule['range'])) {
            throw new \Exception(
                isset($rule['message']) ? $rule['message'] : sprintf('miss %s \'s enum range ', $rule['name'])
                , ExceptionCode::getConstant('MissRange')
            );
        }

        if (empty($rule['range'])) {
            throw new \Exception(
                isset($rule['message']) ? $rule['message'] : sprintf('%s \'s enum range can not be empty, or value not in range', $rule['name'])
                , ExceptionCode::getConstant('RangeEmpty')
            );
        }
    }
}
