<?php
namespace XP\Request;

/**
 * ExceptionCode 异常处理类
 * @author xiaopeng.lei 2016年4月29日15:09:21
 */
class ExceptionCode
{

    /**
     * $_allConstant 定义的异常常量参数code
     * @var array
     */
    private static $_allConstant = [
        'NoThisConst'        => 0x11000001, //常量不存在；10000001
        'MustRequired'       => 0x11000002, //参数为必填
        'MustInstanceof'     => 0x11000003, //必须继承方法
        'MinGTMax'           => 0x11000004, //最小值大于最大值
        'MinGTValue'         => 0x11000005, //最小值大于验证值
        'MaxLTValue'         => 0x11000006, //最大值小于验证值
        'NotBetween'         => 0x11000007, //超出给定范围
        'RegexNotMatch'      => 0x11000008, //正则不匹配
        'NoFunctionCallback' => 0x11000009, //没有设置参数callback
        'ThePathNoCallback'  => 0x11000010, //提供的地址没有找到方法
        'ThisNoCallback'     => 0x11000011, //当前类中没有没有找到方法
        'MissRange'          => 0x11000012, //缺少range参数
        'RangeEmpty'         => 0x11000013, //enum中range参数为空
        'NoUploadFile'       => 0x11000014, //没有上传文件
        'UploadFileError'    => 0x11000015,
        'FileExtError'       => 0x11000016,
        'NotSafeParam'       => 0x11000017,
        'NeedCoverButYouNot' => 0x11000018,
        'JsonNotMatch'       => 0x11000019,
    ];
    /**
     * $_allConstantMessage 中文解释
     * @var array
     */
    private static $_allConstantMessage = [
        'NoThisConst'        => '常量不存在',
        'MustRequired'       => '参数为必填',
        'MustInstanceof'     => '必须继承方法',
        'MinGTMax'           => '最小值大于最大值',
        'MinGTValue'         => '最小值大于验证值',
        'MaxLTValue'         => '最大值小于验证值',
        'NotBetween'         => '超出给定范围',
        'RegexNotMatch'      => '正则不匹配',
        'NoFunctionCallback' => '没有设置参数callback',
        'ThePathNoCallback'  => '提供的地址没有找到方法',
        'ThisNoCallback'     => '当前类中没有没有找到方法',
        'MissRange'          => '缺少range参数',
        'RangeEmpty'         => 'enum中range参数为空',
        'NoUploadFile'       => '没有上传文件',
        'UploadFileError'    => '上传文件失败',
        'FileExtError'       => '错误的文件后缀',
        'NotSafeParam'       => '此参数为不安全，未验证参数',
        'NeedCoverButYouNot' => '需要覆盖rules方法，你没有',
        'JsonNotMatch'       => '格式不对，不是json格式',
    ];

    /**
     * 获取错误码的常量值
     * @method getConstant
     * @param  string      $key 错误码英文名
     * @return int
     */
    public static function getConstant($key = 'status')
    {
        if (array_key_exists($key, self::$_allConstant)) {
            return self::$_allConstant[$key];
        } else {
            throw new \Exception('请先定义该常量，不存在！', 0x11000001);
        }
    }

    /**
     * 获取错误码的中文解释
     * @method getMessage
     * @param  string     $key 错误码英文名
     * @return string          错误码的解释
     */
    public static function getMessage($key = 'status')
    {
        if (array_key_exists($key, self::$_allConstantMessage)) {
            return self::$_allConstantMessage[$key];
        } else {
            throw new \Exception('请先定义该常量，不存在！', 0x11000001);
        }
    }

}
