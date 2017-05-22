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
 * Class Live
 * @package xutl\aliyun
 */
class Live extends Rpc
{
    public $baseUrl = 'https://live.aliyuncs.com/';

    /**
     * @var string Api接口版本
     */
    public $version = '2016-11-01';

    /**
     * @var string 推流域名
     */
    public $domain;

    /**
     * @var string 应用名称
     */
    public $appName;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty ($this->domain)) {
            throw new InvalidConfigException ('The "domain" property must be set.');
        }
        if (empty ($this->appName)) {
            throw new InvalidConfigException ('The "appName" property must be set.');
        }
    }

    /**
     * 禁止推流
     * @param string $streamName
     * @return string
     */
    public function forbidLiveStream($streamName)
    {
        return $this->get('', [
            'Action' => 'ForbidLiveStream',
            'DomainName' => $this->domain,
            'AppName' => $this->appName,
            'StreamName' => $streamName,
            'LiveStreamType' => 'publisher',
            'ResumeTime' => gmdate('Y-m-d\TH:i:s\Z', mktime(0, 0, 0, 1, 1, 2099))
        ]);
    }

    /**
     * 允许推流
     * @param string $streamName
     * @return string
     */
    public function startLiveStream($streamName)
    {
        return $this->get('', [
            'Action' => 'ResumeLiveStream',
            'DomainName' => $this->domain,
            'AppName' => $this->appName,
            'StreamName' => $streamName,
            'LiveStreamType' => 'publisher'
        ]);
    }

    /**
     * 实时查询在线人数的请求参数
     * @param null|string $streamName
     * @param null|int $startTime
     * @param null|int $endTime
     * @return Response
     */
    public function describeLiveStreamOnlineUserNum($streamName = null, $startTime = null, $endTime = null)
    {
        $params = [
            'Action' => 'DescribeLiveStreamOnlineUserNum',
            'DomainName' => $this->domain,
            'AppName' => $this->appName
        ];
        if (!empty($streamName)) {
            $params['StreamName'] = $streamName;
        }
        if (!empty($startTime) && !empty($endTime)) {
            $params['StartTime'] = gmdate('Y-m-d\TH:i:s\Z', $startTime);
            $params['EndTime'] = gmdate('Y-m-d\TH:i:s\Z', $endTime);
        }
        return $this->get('', $params);
    }
}