<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use yii\helpers\Json;
use yii\base\InvalidConfigException;

/**
 * Class Sms
 * @package xutl\aliyun
 */
class Sms extends Rpc
{
    /**
     * @var string 网关地址
     */
    public $baseUrl = 'https://dysmsapi.aliyuncs.com/';

    /**
     * @var string Api接口版本
     */
    public $version = '1.0';

    /**
     * @var string 短信签名
     */
    public $signName;

    /**
     * 初始化直播
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty ($this->signName)) {
            throw new InvalidConfigException ('The "signName" property must be set.');
        }
    }

    /**
     * @param string $phoneNumbers 短信接收号码。支持以逗号分隔的形式进行批量调用，批量上限为1000个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
     * @param string $signName 短信签名
     * @param string $templateCode 短信模板ID
     * @param array $templateParam 短信模板变量替换JSON串。
     * @param string $outId 外部流水扩展字段
     * @return array
     */
    public function sendTemplateSms($phoneNumbers, $templateCode, array $templateParam = [], $signName = null, $outId = null)
    {
        return $this->get('', [
            'Action' => 'ForbidLiveStream',
            'PhoneNumbers' => $phoneNumbers,
            'SignName' => $signName,
            'TemplateCode' => $templateCode,
            'TemplateParam' => Json::encode($templateParam),
            'OutId' => $outId
        ]);
    }
}