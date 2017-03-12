
/**
 * RequestVar 变量格式化类
 *
 * 针对设定的规则进行对应的模块中的变量进行格式化操作
 *
 * - 1、根据字段与预定义变量对应关系，获取变量值
 * - 2、对变量进行类型转换
 * - 3、进行有效性判断过滤
 * - 4、按业务需求进行格式化
 *
 *参数都需要注册；
 *
 * 关键key：
 * ```
 * commonRules；
 * 使用方法:
 * 'commonRules'=>规则的数组
 * 例子:
 * 'commonRules'=>[['key'=>['name'=>'rules','min'=>10]],['key2'=>['type'=>'string']]]
 *```
 *
 *公共的规则：
 *```
 *name: 在过滤获得参数就是用这个key，
 *scene: 场景值1,2  使用任一个都会在form中添加字段 使用1就是使用default值，2为不使用default值即不传参form中就没有
 *type: 默认为string，可选的参数有int（整型）|float（浮点型）|boolean（布尔型）|date（时间类型）|array（数组类型）|callable（回调方法）|enum（某个范围）|file（文件），可以同时使用多个类型，注意有先后的顺序，类似'type'=>'callable,int'
 *default: 提供个默认值
 *require: 是否为必填项
 *min,max: int，float中为最小最大值,数组中为数组的长度，string中为字符的长度，date中时间戳按整数算
 *```
 *额外的规则：
 *```php
 *string：length（长度范围），regex（正则）
 *array( 'type' => 'string', 'default' => 'qq', 'min' => '1', 'max' => '4', 'regex' => '/\w+/',length=>'1,10')
 *
 *int|float:
 *array('type' => 'int', 'default' => 12, 'min' => '3', 'max' => '14')
 *array('type' => 'float', 'default' => '', 'min' => '', 'max' => '')
 *
 *boolean:字符串只有'ok', 'true', 'success', 'on', 'yes'会判断为成功，大于0 的数字为成功，其他情况有值为成功
 *array('type' => 'boolean', 'default' => 'ok')
 *
 *date:'format'（暂时固定为'timestamp'）
 *array('type' => 'date','default' => '非时间戳格式的string','format' => 'timestamp')
 *
 *array：'format'（'json/explode'，json为json_decode,explode为按照separator或默认‘，’拆分字符串，），'separator' （拆分字符为数组）
 *array('type' => 'array', 'format' => 'explode', 'separator' => ',')
 *
 * callable：'callback'（'回调函数'），'params'（'回调参数')，'path'（函数的地址）
 * array('type' => 'callable','callback'=>'checkId','path'=>'Home\Rules\UserRules','params'=>'123123')
 *
 *file：'range'(文件的类型，'application/octet-stream'),'ext'(后缀的类型，多种可以是数组或逗号隔开)
 * array('type' => 'file','range' => array(...),'ext'=>'txt,sql')
 *
 * enum：'range'（枚举的范围，类似为in）
 * array('type' => 'enum', 'range' => array(...))
 *```
 *
 * 在rules层写对应的控制器的rules控制器，同时继承BaseRules，
 * 写上rules方法返回一个数组，一级key为方法名，二级key为参数名，二级数组为对应参数的规则
 *
 * @author      xiaopeng.lei 2016年4月29日11:51:47
 */

使用方法：
---
在composer.json中指定项目位置

主要是基于thingphp制作的参数验证层。



