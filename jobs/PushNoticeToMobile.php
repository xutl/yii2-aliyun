<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun\jobs;

use Yii;
use yii\helpers\Json;
use yii\queue\Queue;
use yii\base\BaseObject;
use yii\queue\RetryableJobInterface;

/**
 * Class PushNoticeToMobile
 * @package sixiang\group\jobs
 */
class PushNoticeToMobile extends BaseObject implements RetryableJobInterface
{
    /**
     * @var string AppKey信息
     */
    public $appKey;

    /**
     * @var string 推送目标
     */
    public $target = 'ALL';

    /**
     * @var string 目标
     */
    public $targetValue = 'all';

    /**
     * @var string 标题
     */
    public $title;

    /**
     * @var string 内容
     */
    public $body;

    /**
     * @var array 扩展参数
     */
    public $extParameters;

    /**
     * @var string iOS APNS ENV
     */
    public $apnsEnv = 'PRODUCT';

    /**
     * @var integer
     */
    private $_appKey;

    /**
     * @param Queue $queue
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        if (YII_ENV_DEV) {
            $this->apnsEnv = 'DEV';
        }
        $this->_appKey = isset(Yii::$app->aliyun->params['CloudPush.appKey']) ? Yii::$app->aliyun->params['CloudPush.appKey'] : null;
        $this->extParameters = Json::encode($this->extParameters);
        $this->sendToAndroid();
        $this->sendToIOS();
    }

    /**
     * 推送通知
     * @throws \yii\base\InvalidConfigException
     */
    public function sendToAndroid()
    {
        $cloudPush = Yii::$app->aliyun->getCloudPush();
        return $cloudPush->pushNoticeToAndroid([
            'AppKey' => $this->_appKey,
            'Target' => $this->target,
            'TargetValue' => $this->targetValue,
            'Title' => $this->title,
            'Body' => $this->body,
            'ExtParameters' => $this->extParameters,//JSON
        ]);
    }

    /**
     * 推送通知
     * @throws \yii\base\InvalidConfigException
     */
    public function sendToIOS()
    {
        $cloudPush = Yii::$app->aliyun->getCloudPush();
        return $cloudPush->pushNoticeToIOS([
            'AppKey' => $this->_appKey,
            'Target' => $this->target,
            'TargetValue' => $this->targetValue,
            'ApnsEnv' => $this->apnsEnv,
            'Title' => $this->title,
            'Body' => $this->body,
            'ExtParameters' => $this->extParameters,//JSON
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getTtr()
    {
        return 60;
    }

    /**
     * @inheritdoc
     */
    public function canRetry($attempt, $error)
    {
        return $attempt < 3;
    }
}