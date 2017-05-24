<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use yii\httpclient\Response;
use yii\base\InvalidConfigException;

/**
 * Class Dm
 * @package xutl\aliyun
 */
class Dm extends Rpc
{
    /**
     * @var string 网关地址
     */
    public $baseUrl = 'https://dm.aliyuncs.com';

    /**
     * @var string Api接口版本
     */
    public $version = '2015-11-23';

    public $messageConfig = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * 单一发信接口
     * @param string $accountName 管理控制台中配置的发信地址
     * @param bool $replyToAddress 是否使用管理控制台中配置的回信地址（状态必须是验证通过）
     * @param int $addressType 取值范围0~1: 0为随机账号；1为发信地址
     * @param string $toAddress 目标地址，多个Email地址可以逗号分隔，最多100个地址。
     * @param string $fromAlias 发信人昵称,长度小于15个字符 例如:发信人昵称设置为”小红”，发信地址为”test@example.com”，收信人看到的发信地址为"小红"<test@example.com>
     * @param string $subject 邮件主题，建议填写
     * @param string $htmlBody 邮件html正文
     * @param string $textBody 邮件text正文
     * @return array
     */
    public function singleSendMail($accountName, $replyToAddress, $addressType, $toAddress, $fromAlias = null, $subject = null, $htmlBody = null, $textBody = null)
    {
        $params = [
            'Action' => 'SingleSendMail',
            'AccountName' => $accountName,
            'ReplyToAddress' => $replyToAddress,
            'AddressType' => $addressType,
            'ToAddress' => $toAddress,
        ];
        if (!empty($fromAlias)) {
            $params['FromAlias'] = $fromAlias;
        }
        if (!empty($subject)) {
            $params['Subject'] = $subject;
        }
        if (!empty($htmlBody)) {
            $params['HtmlBody'] = $htmlBody;
        }
        if (!empty($textBody)) {
            $params['TextBody'] = $textBody;
        }
        return $this->post('', $params);
    }

    /**
     * 批量发信接口
     * @param string $accountName 管理控制台中配置的发信地址
     * @param int $addressType 取值范围0~1: 0为随机账号；1为发信地址
     * @param string $templateName 预先创建且通过审核的模板名称
     * @param string $receiversName 预先创建且上传了收件人的收件人列表名称
     * @param string $tagName 邮件标签名称
     * @return array
     */
    public function batchSendMail($accountName, $addressType, $templateName, $receiversName, $tagName = null)
    {
        $params = [
            'Action' => 'BatchSendMail',
            'AccountName' => $accountName,
            'AddressType' => $addressType,
            'TemplateName' => $templateName,
            'ReceiversName' => $receiversName,
        ];
        if (!empty($tagName)) {
            $params['TagName'] = $tagName;
        }
        return $this->post('', $params);
    }
}