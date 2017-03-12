<?php
namespace XP\Request;

/**
 * RequestFormatter 格式化接口
 *
 * @author      xiaopeng.lei 2016年4月29日11:51:47
 */

interface RequestFormatter
{
	/**
	 * parse 要格式化的方法
	 * @method parse
	 * @param  mixed $value 值
	 * @param  mixed $rule  规则
	 * @return 格式化的值
	 */
    public function parse($value, $rule);
}
