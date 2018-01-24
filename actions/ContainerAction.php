<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun\actions;

use Yii;
use yii\base\Action;
use yii\helpers\Json;
use yii\base\InvalidConfigException;
use yii\web\UnauthorizedHttpException;

/**
 * 阿里云容器镜像Webhook回调
 * 需要Request 组件支持解析POST JSON
 * @package xutl\aliyun\actions
 */
class ContainerAction extends Action
{
    /**
     * @var callable success callback with signature: `function($params)`
     */
    public $callback;

    /**
     * @var string 签名密钥
     */
    public $token = '';

    /**
     * 初始化
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty ($this->callback)) {
            throw new InvalidConfigException ('The "callback" property must be set.');
        }
    }

    /**
     * 处理直播通知回调
     * @param string $token
     * @return mixed
     * @throws UnauthorizedHttpException
     */
    public function run($token = '')
    {
        if ($token != $this->token) {
            throw new UnauthorizedHttpException();
        }
        $params = Yii::$app->request->post();
        Yii::info(Json::encode($params), __METHOD__);
        return call_user_func($this->callback, $params);
    }
}