<?php
/**
 * User: cy
 * Date: 2018/7/18
 * Time: 15:52
 */

namespace app\models;


use yii\base\Model;

class FilesFilter extends Model
{
    public $search_dir;     //检索根目录
    public $type;           //检索文件类型
    public $max_size;       //文件最大大小(kb)
    public $min_size;       //文件最小大小(kb)
    public $max_create_time;//最大创建时间
    public $min_create_time;//最小创建时间
    public $max_update_time;//最大更新时间
    public $min_update_time;//最小更新时间
    public $filter_name;     //过滤文件名

    private $files = []; //符合条件的文件地址数组

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['search_dir', 'type'], 'required', 'message' => "检索目录和文件类型不能为空."],
            [['max_size', 'min_size', 'max_create_time', 'min_create_time', 'max_update_time', 'min_update_time'], 'integer', 'message' => "必须为整数."],
            ['search_dir', 'validateDir'],
            [['max_size', 'min_size', 'max_create_time', 'min_create_time', 'max_update_time', 'min_update_time'], 'validateSize'],
        ];
    }

    /**
     * Validates the size.
     * This method serves as the inline validation for size.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateSize($attribute, $params)
    {
        if ($this->max_size && $this->min_size) {
            if ($this->min_size > $this->max_size) {
                $this->addError($attribute, '最小文件大小设置不能超过最大文件大小设置.');
            }
        }
        if ($this->max_create_time && $this->min_create_time) {
            if ($this->min_create_time > $this->max_create_time) {
                $this->addError($attribute, '最小文件创建时间设置不能超过最大文件创建时间设置.');
            }
        }
        if ($this->max_update_time && $this->min_update_time) {
            if ($this->min_update_time > $this->max_update_time) {
                $this->addError($attribute, '最小文件更新时间设置不能超过最大文件更新时间设置.');
            }
        }
    }

    public function validateDir($attribute, $params)
    {
        if (!is_dir($this->search_dir)) {
            $this->addError($attribute, '检索目录不存在.');
        }
    }

    public function find($find_dir = '')
    {

        $find_dir = empty($find_dir) ? $this->search_dir : $find_dir;
        $dir_info = scandir($find_dir);
        foreach ($dir_info as $key => $value) {
            if ($value == '.' || $value == '..') {
                continue;
            }
            if (is_dir($find_dir . '/' . $value)) {
                $this->find($find_dir . '/' . $value);
            } else {
                $file_info = $this->getFileInfo($find_dir, $value);
                $File_obj = new FileValidate([
                    'maxSize' => $this->max_size,
                    'minSize' => $this->min_size,
                    'maxCreateTime' => $this->max_create_time,
                    'minCreateTime' => $this->min_create_time,
                    'maxUpdateTime' => $this->max_update_time,
                    'minUpdateTime' => $this->min_update_time,
                    'filterName' => $this->filter_name,
                    'filterType' => $this->type
                ]);
                $File_obj->attributes = $file_info;
                if ( $File_obj->validate() ) {
                    $this->files[] = array('dir' => $find_dir . '/' . $value, 'name' => $value);
                }
            }
        }
        return $this->files;
    }

    private function getFileInfo($dir, $filename)
    {
        $file_dir = $dir . '/' . $filename;
        return ['name' => mb_convert_encoding($filename, "UTF-8", "GB2312"),
            'type' => $this->getFileSuffix($filename),
            'size' => ceil(filesize($file_dir) / 1024),
            'update_time' => filemtime($file_dir),
            'create_time' => filectime($file_dir)
        ];
    }

    private function getFileSuffix($filename)
    {
        $arr = explode('.', $filename);
        return $arr[count($arr) - 1];
    }
}