<?php
/**
 * User: cy
 * Date: 2018/7/18
 * Time: 16:37
 */

namespace app\controllers;

use app\models\FilesToZip;
use Yii;
use app\models\FilesFilter;
use yii\web\Controller;

class CodeLogsController extends Controller
{
    public function actionIndex()
    {
        $filesFilter = new FilesFilter();
        $filesFilter->attributes = Yii::$app->params['codeLogs'];
        if (!$filesFilter->validate()) {
            $error = $filesFilter->getErrors();
            foreach ($error as $key => $val) {
                echo "验证失败字段: $key,原因: $val[0]<br/>";
            }
            exit;
        }
        $file_list = $filesFilter->find();
        if (empty($file_list)) {
            echo "检索的文件列表为空.";
            exit;
        }
        $zip_obj = new FilesToZip();
        $zip_cfg = Yii::$app->params['codeZip'];
        $zip_cfg['file_list'] = $file_list;
        $zip_obj->attributes = $zip_cfg;
        if (!$filesFilter->validate()) {
            $error = $filesFilter->getErrors();
            foreach ($error as $key => $val) {
                echo "验证失败字段: $key,原因: $val[0]<br/>";
            }
            exit;
        }
        if (!$zip_obj->save()) {
            echo "保存压缩包失败.";
            exit;
        }
        $local_file = Yii::$app->params['codeZip']['save_dir'] . Yii::$app->params['codeZip']['file_name'];
        $email_cfg = Yii::$app->params['codeEmail'];
        $res = Yii::$app->mailer->compose()
            ->setFrom($email_cfg['from'])
            ->setTo($email_cfg['to'])
            ->setSubject($email_cfg['subject'])
            ->setTextBody($email_cfg['textBody'])
            ->setHtmlBody($email_cfg['htmlBody'])
            ->attach($local_file)
            ->send();
        var_dump($res);
    }

}