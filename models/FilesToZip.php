<?php
/**
 * User: cy
 * Date: 2018/7/18
 * Time: 19:28
 */

namespace app\models;


use yii\base\Model;

class FilesToZip extends Model
{
    public $file_list;
    public $save_dir;
    public $file_name;

    private $file_name_list = [];

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file_list', 'save_dir', 'file_name'], 'required', 'message' => "参数缺失."],
        ];
    }

    public function save()
    {
        $this->initDirAndFile();
        $zip = new \ZipArchive();
        $res = $zip->open($this->save_dir . $this->file_name, \ZipArchive::OVERWRITE);
        if ($res) {
            foreach ($this->file_list as $key => $value) {
                while (in_array($value['name'], $this->file_name_list)) {
                    $value['name'] = $this->reName($value['name']);
                }
                $zip->addFile($value['dir'], $value['name']);
                $this->file_name_list[] = $value['name'];
            }
            $zip->close();
            return true;
        } else {
            return false;
        }
    }

    //同名文件重命名
    public function reName($filename)
    {
        $preg = '/\(+[\d]+\)\.(?!.*\(+[\d]+\)\.)/';
        if (preg_match($preg, $filename)) {
            return preg_replace_callback($preg, function ($matches) {
                return preg_replace_callback('/[\d]+/', function ($matches) {
                    return ++$matches[0];
                }, $matches[0]);
            }, $filename);
        } else {
            $name_arr = explode('.', $filename);
            $new_str = '';
            foreach ($name_arr as $key => $value) {
                if ($key + 1 == count($name_arr)) {
                    $new_str = substr($new_str, 0, -1);
                    $new_str .= '(1).' . $value;
                } else {
                    $new_str .= $value . '.';
                }
            }
            return $new_str;
        }
    }


    public function initDirAndFile()
    {
        is_dir($this->save_dir) or mkdir($this->save_dir, 0777, true);
        fopen($this->save_dir . $this->file_name, "w");
    }
}