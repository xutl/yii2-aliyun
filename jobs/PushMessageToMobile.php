<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun\jobs;

use Yii;
use yii\base\BaseObject;
use yii\helpers\Json;
use yii\queue\Queue;
use yii\queue\RetryableJobInterface;

/**
 * 推送消息到手机
 * @package xutl\aliyun\jobs
 */
class PushMessageToMobile extends BaseObject implements RetryableJobInterface
{
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
     * @var integer
     */
    private $_appKey;

    /**
     * @param Queue $queue
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        $this->_appKey = isset(Yii::$app->aliyun->params['CloudPush.appKey']) ? Yii::$app->aliyun->params['CloudPush.appKey'] : null;
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
        return $cloudPush->pushMessageToAndroid([
            'AppKey' => $this->_appKey,
            'Target' => $this->target,
            'TargetValue' => $this->targetValue,
            'Title' => $this->title,
            'Body' => $this->body,
        ]);
    }

    /**
     * 推送通知
     * @throws \yii\base\InvalidConfigException
     */
    public function sendToIOS()
    {
        $cloudPush = Yii::$app->aliyun->getCloudPush();
        return $cloudPush->pushMessageToiOS([
            'AppKey' => $this->_appKey,
            'Target' => $this->target,
            'TargetValue' => $this->targetValue,
            'Title' => $this->title,
            'Body' => $this->body,
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