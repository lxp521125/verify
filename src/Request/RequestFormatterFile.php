<?php

namespace XP\Request;


/**
 * RequestFormatterFile 格式化上传文件
 * @author      xiaopeng.lei 2016年4月29日11:51:47
 */
class RequestFormatterFile extends RequestFormatterBase implements RequestFormatter
{

    /**
     * 格式化文件类型
     *
     * @param array $rule array('name' => '', 'type' => 'file', 'default' => array(...), 'min' => '', 'max' => '', 'range' => array(...))
     *
     * @throws Exception
     */
    public function parse($value, $rule)
    {

        $default = isset($rule['default']) ? $rule['default'] : null;

        $index = $rule['name'];
        // 未上传
        if (!isset($_FILES[$index])) {
            // 有默认值 || 非必须
            if ($default !== null || (isset($rule['require']) && !$rule['require'])) {
                return $default;
            }
        }

        if (!isset($_FILES[$index]) || !isset($_FILES[$index]['error']) || !is_array($_FILES[$index])) {
            throw new \Exception(
                isset($rule['message']) ? $rule['message'] : sprintf('miss upload file: %s', $index)
                , ExceptionCode::getConstant('NoUploadFile')
            );
        }

        if ($_FILES[$index]['error'] != UPLOAD_ERR_OK) {
            throw new \Exception(
                isset($rule['message']) ? $rule['message'] : sprintf('fail to upload file with error = %s', $_FILES[$index]['error'])
                , ExceptionCode::getConstant('UploadFileError')
            );

        }

        $sizeRule         = $rule;
        $sizeRule['name'] = $sizeRule['name'] . '.size';
        $this->filterByRange($_FILES[$index]['size'], $sizeRule);

        if (!empty($rule['range']) && is_array($rule['range'])) {
            $rule['range'] = array_map('strtolower', $rule['range']);
            $this->formatEnumValue(strtolower($_FILES[$index]['type']), $rule);
        }

        //对于文件后缀进行验证
        if (!empty($rule['ext'])) {
            $ext = trim(strrchr($_FILES[$index]['name'], '.'), '.');
            if (is_string($rule['ext'])) {
                $rule['ext'] = explode(',', $rule['ext']);
            }
            if (!$ext) {
                throw new \Exception(
                    isset($rule['message']) ? $rule['message'] : sprintf('Not the file type  %s', json_encode($rule['ext']))
                    , ExceptionCode::getConstant('FileExtError')
                );
            }
            if (is_array($rule['ext'])) {
                $rule['ext'] = array_map('strtolower', $rule['ext']);
                $rule['ext'] = array_map('trim', $rule['ext']);
                if (!in_array(strtolower($ext), $rule['ext'])) {
                    throw new \Exception(
                        isset($rule['message']) ? $rule['message'] : sprintf('Not the file type  %s', json_encode($rule['ext']))
                        , ExceptionCode::getConstant('FileExtError')
                    );

                }
            }
        }

        return $_FILES[$index];
    }
}
