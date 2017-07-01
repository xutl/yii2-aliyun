<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use yii\base\InvalidConfigException;

/**
 * Class Live
 * @package xutl\aliyun
 */
class Live extends Rpc
{
    /**
     * @var string 网关地址
     */
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
     * @var string 推流鉴权
     */
    public $pushAuth;

    /**
     * @var string 媒体中心地址
     */
    public $pushDomain = 'video-center.alivecdn.com';

    /**
     * @var string 录像播放域名，通常是OSS地址
     */
    public $recordDomain;

    /**
     * @var bool 是否使用安全连接
     */
    public $secureConnection = false;

    /**
     * @var int 推流签名有效期,默认有效期是一周
     */
    public $authTime = 604800;

    /**
     * @var int 签名过期时间
     */
    private $expirationTime;

    /**
     * @var string 播放地址
     */
    private $httpPlayUrl;

    /**
     * 初始化直播
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        //初始化过期时间
        $this->expirationTime = time() + $this->authTime;
        //播放地址前半段
        $this->httpPlayUrl = ($this->secureConnection ? 'https://' : 'http://') . $this->domain;
        if (empty ($this->domain)) {
            throw new InvalidConfigException ('The "domain" property must be set.');
        }
        if (empty ($this->recordDomain)) {
            throw new InvalidConfigException ('The "recordDomain" property must be set.');
        }
    }

    /**
     * 禁止推流
     * @param string $appName 应用名称
     * @param string $streamName
     * @return array
     */
    public function forbidLiveStream($appName, $streamName)
    {
        return $this->get('', [
            'Action' => 'ForbidLiveStream',
            'DomainName' => $this->domain,
            'AppName' => $appName,
            'StreamName' => $streamName,
            'LiveStreamType' => 'publisher',
            'ResumeTime' => gmdate('Y-m-d\TH:i:s\Z', mktime(0, 0, 0, 1, 1, 2050))
        ]);
    }

    /**
     * 允许推流
     * @param string $appName 应用名称
     * @param string $streamName
     * @return array
     */
    public function resumeLiveStream($appName, $streamName)
    {
        return $this->get('', [
            'Action' => 'ResumeLiveStream',
            'DomainName' => $this->domain,
            'AppName' => $appName,
            'StreamName' => $streamName,
            'LiveStreamType' => 'publisher'
        ]);
    }

    /**
     * 实时查询在线人数的请求参数
     * @param string $appName 应用名称
     * @param null|string $streamName
     * @param null|int $startTime
     * @param null|int $endTime
     * @return array
     */
    public function liveStreamOnlineUserNum($appName, $streamName = null, $startTime = null, $endTime = null)
    {
        $params = [
            'Action' => 'DescribeLiveStreamOnlineUserNum',
            'DomainName' => $this->domain,
            'AppName' => $appName
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

    /**
     * 查询在线的直播推流列表
     * @param string $appName 应用名称
     * @return array
     */
    public function liveStreamsOnlineList($appName)
    {
        return $this->get('', [
            'Action' => 'DescribeLiveStreamsOnlineList',
            'DomainName' => $this->domain,
            'AppName' => $appName
        ]);
    }

    /**
     * 查询推流黑名单列表
     * @return array
     */
    public function liveStreamsBlockList()
    {
        return $this->get('', [
            'Action' => 'DescribeLiveStreamsBlockList',
            'DomainName' => $this->domain
        ]);
    }

    /**
     * 查询流控历史
     * @param string $appName 应用名称
     * @param string $startTime 查询开始时间 UTC时间 格式：2015-12-01T17:36:00Z
     * @param string $endTime 查询结束时间 UTC时间 格式：2015-12-01T17:37:00Z，EndTime和StartTime之间的间隔不能超过30天
     * @return array
     */
    public function liveStreamsControlHistory($appName, $startTime, $endTime)
    {
        return $this->get('', [
            'Action' => 'DescribeLiveStreamsControlHistory',
            'DomainName' => $this->domain,
            'AppName' => $appName,
            'StartTime' => gmdate('Y-m-d\TH:i:s\Z', $startTime),
            'EndTime' => gmdate('Y-m-d\TH:i:s\Z', $endTime),
        ]);
    }

    /**
     * 查询直播流的帧率和码率
     * @param string $appName 应用名称
     * @param string $streamName 流名称
     * @return array
     */
    public function liveStreamsFrameRateAndBitRateData($appName, $streamName = null)
    {
        $params = [
            'Action' => 'DescribeLiveStreamsFrameRateAndBitRateData',
            'DomainName' => $this->domain,
            'AppName' => $appName,
        ];
        if (!empty($streamName)) {
            $params['StreamName'] = $streamName;
        }
        return $this->get('', $params);
    }

    /**
     * 查询推流历史
     * @param string $appName 应用名称
     * @param string $streamName 直播流名称
     * @param string $startTime 起始时间，UTC格式，例如：2016-06-29T19:00:00Z
     * @param string $endTime 结束时间，UTC格式，例如：2016-06-30T19:00:00Z，EndTime和StartTime之间的间隔不能超过30天
     * @param int $pageSize 分页大小，默认3000，最大3000，取值：1~3000之前的任意整数
     * @param int $pageNumber 取得第几页，默认1
     * @return array
     */
    public function liveStreamsPublishList($appName, $streamName, $startTime, $endTime, $pageSize = 3000, $pageNumber = 1)
    {
        $params = [
            'Action' => 'DescribeLiveStreamsPublishList',
            'DomainName' => $this->domain,
            'AppName' => $appName,
            'StartTime' => gmdate('Y-m-d\TH:i:s\Z', $startTime),
            'EndTime' => gmdate('Y-m-d\TH:i:s\Z', $endTime),
            'PageSize' => $pageSize,
            'PageNumber' => $pageNumber,
        ];
        if (!empty($streamName)) {
            $params['StreamName'] = $streamName;
        }

        return $this->get('', $params);
    }

    /**
     * 直播签名
     * @param string $appName 应用名称
     * @param string $streamName 直播流名称
     * @return string
     */
    protected function getSign($appName, $streamName)
    {
        $uri = "/{$appName}/{$streamName}";
        if ($this->pushAuth) {
            $authKey = "?vhost={$this->domain}&auth_key={$this->expirationTime}-0-0-" . md5("{$uri}-{$this->expirationTime}-0-0-{$this->pushAuth}");
        } else {
            $authKey = "?vhost={$this->domain}";
        }
        return $authKey;
    }

    /**
     * 获取推流地址
     * @param string $appName 应用名称
     * @return string
     */
    public function getPushPath($appName)
    {
        return "rtmp://{$this->pushDomain}/{$appName}/";
    }

    /**
     * 获取串码流
     * @param string $appName 应用名称
     * @param string $streamName 直播流名称
     * @return string
     */
    public function getPushArg($appName, $streamName)
    {
        return $streamName . $this->getSign($appName, $streamName);
    }

    /**
     * 获取直播推流地址
     * @param string $appName 应用名称
     * @param string $streamName 直播流名称
     * @return string
     */
    public function getPushUrl($appName, $streamName)
    {
        $uri = "/{$appName}/{$streamName}";
        return "rtmp://{$this->pushDomain}" . $uri . $this->getSign($appName, $streamName);
    }

    /**
     * 获取签名
     * @param string $uri
     * @return string
     */
    protected function getAuthKey($uri)
    {
        $authKey = '';
        if ($this->pushAuth) {
            $authKey = "?auth_key={$this->expirationTime}-0-0-" . md5("{$uri}-{$this->expirationTime}-0-0-{$this->pushAuth}");
        }
        return $authKey;
    }

    /**
     * 获取阿里云播放地址
     * @param string $appName 应用名称
     * @param string $streamName 直播流名称
     * @return array
     */
    public function getPlayUrls($appName, $streamName)
    {
        return [
            'rtmp' => $this->getPlayUrlForRTMP($appName, $streamName),
            'flv' => $this->getPlayUrlForFLV($appName, $streamName),
            'm3u8' => $this->getPlayUrlForM3U8($appName, $streamName)
        ];
    }

    /**
     * 获取RTMP拉流地址
     * @param string $appName 应用名称
     * @param string $streamName 直播流名称
     * @return string
     */
    public function getPlayUrlForRTMP($appName, $streamName)
    {
        $uri = "/{$appName}/{$streamName}";
        return 'rtmp://' . $this->domain . $uri . $this->getAuthKey($uri);
    }

    /**
     * 获取FLV播放地址
     * @param string $appName 应用名称
     * @param string $streamName 直播流名称
     * @return string
     */
    public function getPlayUrlForFLV($appName, $streamName)
    {
        $uri = "/{$appName}/{$streamName}.flv";
        return $this->httpPlayUrl . $uri . $this->getAuthKey($uri);
    }

    /**
     * 获取M3U8播放地址
     * @param string $appName 应用名称
     * @param string $streamName 直播流名称
     * @return string
     */
    public function getPlayUrlForM3U8($appName, $streamName)
    {
        $uri = "/{$appName}/{$streamName}.m3u8";
        return $this->httpPlayUrl . $uri . $this->getAuthKey($uri);
    }

    /**
     * 设置签名过期时间
     * @param int $expirationTime
     * @return $this
     */
    public function setExpirationTime($expirationTime)
    {
        $this->expirationTime = $expirationTime;
        return $this;
    }

    /**
     * 获取录像播放地址
     * @param string $uri
     * @return string
     */
    public function getRecordUrl($uri)
    {
        return ($this->secureConnection ? 'https://' : 'http://') . $this->recordDomain . '/' . $uri;
    }
}