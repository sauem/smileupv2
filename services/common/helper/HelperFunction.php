<?php
namespace common\helper;

use common\models\ActivityLog;
use common\models\Domain;
use common\models\Message;
use common\models\Setting;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use \common\models\Conversation;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;

class HelperFunction
{
    /**
     * Get first error message
     * @param $model ActiveRecord | \matrozov\couchbase\ActiveRecord | \yii\redis\ActiveRecord | Model
     * @return string
     */
    public static function getFirstErrorModel($model)
    {
        $message = '';
        foreach ($model->getFirstErrors() as $er) {
            $message = $er;
            break;
        }
        return $message;
    }

    /**
     * Convert vi to slug
     * @param $str
     * @return null|string|string[]
     */
    public static function convert_vi_to_en($str)
    {
        $str = trim($str);
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }

    /**
     * Log error về folder log
     * @param $message
     */
    public static function error_log($message)
    {
        $message = "[" . date("d-m-Y H:i:s") . "]$message" . "\n";
        $log_path = LOG_PATH . DIRECTORY_SEPARATOR . \Yii::$app->name . date("dmY") . ".log";

        if (!file_exists($log_path)) {
            $fh = fopen($log_path, 'w');
            fclose($fh);
        }

        error_log($message, 3, $log_path);
    }

    /**
     * Random trong khoảng length ký tự a-z0-9
     * @param int $random_string_length
     * @return string
     */
    public static function randomString($random_string_length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $random_string_length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }

    /**
     * Return query list về dạng next back rows
     *
     * @param ActiveQuery $query
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function executeList($query, $page = 0, $limit = 20)
    {

        $data = [
            'next' => true,
            'back' => true,
            'rows' => [],
            'page' => $page,
            'limit' => $limit
        ];
        if (!$page || $page <= 0) {
            $page = 1;
        }

        $query->limit($limit + 1);
        $query->offset(($page - 1) * $limit);

        $data['rows'] = $query->asArray(true)->all();
        $rowCount = count($data['rows']);

        if ($page >= 1) {
            $data['back'] = false;
        }

        if ($rowCount < $limit) {
            $data['next'] = false;
        }

        return $data;
    }

    public static function doCurl($url, $isPost, $params, $isJsonParams)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $isPost ? "POST" : "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($isJsonParams) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($params))
            );
        }

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            HelperFunction::error_log("error: " . curl_error($ch));
            return false;
        }
        curl_close($ch);
        return json_decode($response, true);
    }

    public static function toBeginOfDateTimestamp($dateString)
    {
        if (empty($dateString)) {
            return \DateTime::createFromFormat("d/m/Y H:i:s",
                date("d/m/Y 00:00:00", time()))->getTimestamp();
        }

        $timeF = \DateTime::createFromFormat("d/m/Y H:i:s"
            , $dateString . " 00:00:00");
        return $timeF->getTimestamp();
    }

    public static function toEndOfDateTimeStamp($dateString)
    {
        if (empty($dateString)) {
            return \DateTime::createFromFormat("d/m/Y H:i:s",
                date("d/m/Y 23:59:59", time()))->getTimestamp();
        }

        $timeT = \DateTime::createFromFormat("d/m/Y H:i:s"
            , $dateString . " 23:59:59");
        return $timeT->getTimestamp();
    }

    private static $DOMAIN = null;

    /**
     * @return Domain
     * @throws BadRequestHttpException
     */
    public static function getDomain(){
        $domainName = strtolower(parse_url(Url::base(true), PHP_URL_HOST));
        if(!$domainName){
            throw new BadRequestHttpException("Wronggg!");
        }
        if (HelperFunction::$DOMAIN == null) {
            //Check cache
            HelperFunction::$DOMAIN = \Yii::$app->cache->get(DOMAIN_CACHE . $domainName);
            if (!HelperFunction::$DOMAIN) {
                //Check in database
                HelperFunction::$DOMAIN = Domain::find()->where(['domain' => $domainName])->one();
                if (!HelperFunction::$DOMAIN) {
                    throw new BadRequestHttpException("Your domain has not registered yet!");
                }
            }
        }
        return HelperFunction::$DOMAIN;
    }
}
