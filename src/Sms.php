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
    public $version = '2017-05-25';

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
     * 发送模板短信
     * @param string $phoneNumbers 短信接收号码。支持以逗号分隔的形式进行批量调用，批量上限为1000个手机号码
     * @param string $signName 短信签名
     * @param string $templateCode 短信模板ID
     * @param array $templateParam 短信模板变量替换JSON串。
     * @param string $outId 外部流水扩展字段
     * @return array
     */
    public function sendTemplateSms($phoneNumbers, $templateCode, array $templateParam = [], $signName = null, $outId = null)
    {
        return $this->get('', [
            'Action' => 'SendSms',
            'PhoneNumbers' => $phoneNumbers,
            'SignName' => $signName ? $signName : $this->signName,
            'TemplateCode' => $templateCode,
            'TemplateParam' => Json::encode($templateParam),
            'OutId' => $outId
        ]);
    }

    /**
     * 短信查询API
     * @param string $phoneNumber
     * @param int $sendDate
     * @param int $pageSize
     * @param int $currentPage
     * @param null $bizId
     * @return array
     */
    public function querySendDetails($phoneNumber, $sendDate, $pageSize = 10, $currentPage = 1, $bizId = null)
    {
        return $this->get('', [
            'Action' => 'QuerySendDetails',
            'PhoneNumber' => $phoneNumber,
            'BizId' => $bizId,
            'SendDate' => $sendDate,
            'PageSize' => $pageSize,
            'CurrentPage' => $currentPage
        ]);
    }
}