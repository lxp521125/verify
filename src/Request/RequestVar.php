<?php
namespace XP\Request;

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
class RequestVar
{

    /**   规则   @var array */
    public $_ruleArray = [];

    /**   临时的处理后的参数收集  @var array  */
    private static $_paramArray = [];

    private static $_formArray = [];

    /**
     * 初始化时候获取到规则
     * @method __construct
     */
    public function __construct()
    {
        $rules = $this->rules();
        foreach ($rules as $k => $v) {
            $this->_ruleArray[strtolower($k)] = $v;
        }
    }

    /**
     * 需要覆盖的方法，返回数组是规则
     * @method rules
     * @return array
     */
    public function rules()
    {
        throw new \Exception(ExceptionCode::getMessage('NeedCoverButYouNot'), ExceptionCode::getConstant('NeedCoverButYouNot'));
    }

    /**
     * 外部调用的方法
     * @method run
     * @param  string $action_name 方法的名字在rules的方法中的key
     * @return mixed    处理后的参数
     */
    public function run($action_name)
    {
        $action_name = strtolower($action_name);
        $rule = isset($this->_ruleArray[$action_name]) ? $this->_ruleArray[$action_name] : false;
        if (! $rule) {
            return [];
        }
        if (isset($rule['commonRules'])) {
            $commonRules = $rule['commonRules'];
            unset($rule['commonRules']);
            $rule = array_merge($commonRules, $rule);
        }
        foreach ($rule as $k => $v) {
            $rs = null;
            $kk = explode(',', $k);
            foreach ($kk as $kv) {
                if (isset(self::$_paramArray[$kv])) {
                    $rs = self::format($kv, $v, self::$_paramArray);
                } else {
                    $rs = self::format($kv, $v, $_REQUEST);
                }
                if ($rs === null && (isset($v['require']) && $v['require'])) {
                    throw new \Exception('参数：' . $kv . ' 为必填项', ExceptionCode::getConstant('MustRequired'));
                }
                $kv = isset($v['name']) ? $v['name'] : $kv;
                self::$_paramArray[$kv] = $rs;
                if (isset($v['scene'])) {
                    if ($rs !== null)
                        self::$_formArray[$kv] = $rs;
                    elseif (isset(self::$_formArray[$kv]) && $rs === null)
                        unset(self::$_formArray[$kv]);
                }
            }
        }
        $result['param'] = self::$_paramArray;
        self::$_paramArray = null;
        $result['form'] = self::$_formArray;
        self::$_formArray = null;
        return $result;
    }

    /**
     * 获取安全的参数；处理好的参数，出现异常请换参数顺序，或者检查参数
     */
    public static function getSafeParam($key = '')
    {
        if (! isset(self::$_paramArray[$key])) {
            throw new \Exception(ExceptionCode::getMessage('NotSafeParam'), ExceptionCode::getConstant('NotSafeParam'));
        }
        return self::$_paramArray[$key];
    }

    /**
     * 统一格式化操作
     * 扩展参数请参见各种类型格式化操作的参数说明
     *
     * @param string $varName 变量名
     * @param array $rule 格式规则：
     * array(
     *  'name' => '变量名',
     *  'type' => '类型',
     *  'default' => '默认值',
     *  'format' => '格式化字符串'
     *  ...
     *  )
     * @param array $params 参数列表
     * @return miexd 格式后的变量
     */
    public static function format($varName, $rule, $params)
    {
        if (isset($rule['scene']) && $rule['scene'] == 2) {
            $value = null;
        } else {
            $value = isset($rule['default']) ? $rule['default'] : null;
        }
        
        $types = ! empty($rule['type']) ? strtolower($rule['type']) : 'string';
        $types = explode(',', $types);
        $rule['name'] = $varName;
        $value = isset($params[$varName]) ? $params[$varName] : $value;
        foreach ($types as $type) {
            if ($value === null && $type != 'file') {
                // 排除文件类型
                return $value;
            }
            $value = self::formatAllType($type, $value, $rule);
        }
        return $value;
    }

    /**
     * 统一分发处理
     * @param string $type 类型
     * @param string $value 值
     * @param array $rule 规则配置
     * @return mixed
     */
    protected static function formatAllType($type, $value, $rule)
    {
        $classname = 'XP\Request\RequestFormatter' . ucfirst($type);
        if (class_exists($classname)) {
            $formatter = new $classname();
        }
        
        if (! ($formatter instanceof RequestFormatter)) {
            throw new \Exception(ExceptionCode::getMessage('MustInstanceof'), ExceptionCode::getConstant('MustInstanceof'));
        }
        
        return $formatter->parse($value, $rule);
    }
}
