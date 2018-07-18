<?php
/**
 * User: cy
 * Date: 2018/7/15
 * Time: 18:18
 */

namespace app\models;


use yii\base\Model;

class EntryForm extends Model
{
    public $name;
    public $email;

    public function rules()
    {
        return [
            [['name','email'],'required'],
            ['email','email']
        ];
    }
}