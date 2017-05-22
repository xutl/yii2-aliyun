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
     * @var string 应用名称
     */
    public $appName;

    /**
     * @var string 推流鉴权
     */
    public $pushAuth;

    /**
     * @var string 媒体中心地址
     */
    public $pushDomain = 'video-center.alivecdn.com';

    /**
     * @var string
     */
    public $recordDomain;

    /**
     * @var bool 是否使用安全连接
     */
    public $secureConnection = false;

    /**
     * @var int 签名有效期,默认有效期是一周
     */
    public $authTime = 604800;

    /**
     * @var int 秘钥过期时间
     */
    private $expirationTime;

    /**
     * @var string 播放协议
     */
    private $playScheme;

    /**
     * @var string 播放地址
     */
    private $httpPlayUrl;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->expirationTime = time() + $this->authTime;
        $this->playScheme = $this->secureConnection ? 'https://' : 'http://';
        $this->httpPlayUrl = $this->playScheme . $this->domain;
        if (empty ($this->domain)) {
            throw new InvalidConfigException ('The "domain" property must be set.');
        }
        if (empty ($this->appName)) {
            throw new InvalidConfigException ('The "appName" property must be set.');
        }
        if (empty ($this->recordDomain)) {
            throw new InvalidConfigException ('The "recordDomain" property must be set.');
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
            'ResumeTime' => gmdate('Y-m-d\TH:i:s\Z', mktime(0, 0, 0, 1, 1, 2050))
        ]);
    }

    /**
     * 允许推流
     * @param string $streamName
     * @return string
     */
    public function resumeLiveStream($streamName)
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
    public function liveStreamOnlineUserNum($streamName = null, $startTime = null, $endTime = null)
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

    /**
     * 查询在线的直播推流列表
     * @return string
     */
    public function liveStreamsOnlineList()
    {
        return $this->get('', [
            'Action' => 'DescribeLiveStreamsOnlineList',
            'DomainName' => $this->domain,
            'AppName' => $this->appName
        ]);
    }

    /**
     * 查询推流黑名单列表
     * @return string
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
     * @param string $startTime 查询开始时间 UTC时间 格式：2015-12-01T17:36:00Z
     * @param string $endTime 查询结束时间 UTC时间 格式：2015-12-01T17:37:00Z，EndTime和StartTime之间的间隔不能超过30天
     * @return Response
     */
    public function liveStreamsControlHistory($startTime, $endTime)
    {
        return $this->get('', [
            'Action' => 'DescribeLiveStreamsControlHistory',
            'DomainName' => $this->domain,
            'AppName' => $this->appName,
            'StartTime' => gmdate('Y-m-d\TH:i:s\Z', $startTime),
            'EndTime' => gmdate('Y-m-d\TH:i:s\Z', $endTime),
        ]);
    }

    /**
     * 查询直播流的帧率和码率
     * @param string $streamName 流名称
     * @return Response
     */
    public function liveStreamsFrameRateAndBitRateData($streamName = null)
    {
        $params = [
            'Action' => 'DescribeLiveStreamsFrameRateAndBitRateData',
            'DomainName' => $this->domain,
            'AppName' => $this->appName,
        ];
        if (!empty($streamName)) {
            $params['StreamName'] = $streamName;
        }
        return $this->get('', $params);
    }

    /**
     * 查询推流历史
     * @param string $streamName 直播流名称
     * @param string $startTime 起始时间，UTC格式，例如：2016-06-29T19:00:00Z
     * @param string $endTime 结束时间，UTC格式，例如：2016-06-30T19:00:00Z，EndTime和StartTime之间的间隔不能超过30天
     * @param int $pageSize 分页大小，默认3000，最大3000，取值：1~3000之前的任意整数
     * @param int $pageNumber 取得第几页，默认1
     * @return Response
     */
    public function liveStreamsPublishList($streamName, $startTime, $endTime, $pageSize = 3000, $pageNumber = 1)
    {
        $params = [
            'Action' => 'DescribeLiveStreamsPublishList',
            'DomainName' => $this->domain,
            'AppName' => $this->appName,
            'StartTime' => gmdate('Y-m-d\TH:i:s\Z', $startTime),
            'EndTime' => gmdate('Y-m-d\TH:i:s\Z', $endTime),
        ];
        if (!empty($streamName)) {
            $params['StreamName'] = $streamName;
        }
        return $this->get('', $params);
    }

    /**
     * 直播签名
     * @param string $streamName 直播流名称
     * @return string
     */
    public function getSign($streamName)
    {
        $uri = "/{$this->appName}/{$streamName}";
        if ($this->pushAuth) {
            $authKey = "?vhost={$this->domain}&auth_key={$this->expirationTime}-0-0-" . md5("{$uri}-{$this->expirationTime}-0-0-{$this->pushAuth}");
        } else {
            $authKey = "?vhost={$this->domain}";
        }
        return $authKey;
    }

    /**
     * 获取推流地址
     * @return string
     */
    public function getPushPath()
    {
        return "rtmp://{$this->pushDomain}/{$this->appName}/";
    }

    /**
     * 获取串码流
     * @param string $streamName 直播流名称
     * @return string
     */
    public function getPushArg($streamName)
    {
        return $streamName . $this->getSign($streamName);
    }

    /**
     * 获取直播推流地址
     * @param string $streamName 直播流名称
     * @return string
     */
    public function getPushUrl($streamName)
    {
        $uri = "/{$this->appName}/{$streamName}";
        return "rtmp://{$this->pushDomain}" . $uri . $this->getSign($streamName);
    }

    /**
     * 验证签名
     * @param string $streamName 直播流名称
     * @param string $usrargs
     * @return bool
     */
    public function checkSign($streamName, $usrargs)
    {
        parse_str($usrargs, $args);
        if (isset($args['vhost']) && isset($args['auth_key'])) {
            if ($args['vhost'] != $this->domain) {
                return false;
            }
            $params = explode('-', $args['auth_key'], 4);
            if (isset($params[0]) && $params[3]) {
                $uri = "/{$this->appName}/{$streamName}";
                if ($params[3] == md5("{$uri}-{$params[0]}-0-0-{$this->pushAuth}")) {
                    return true;
                }
            }
        }
        return false;
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
     * 获取RTMP拉流地址
     * @param string $streamName 直播流名称
     * @return string
     */
    public function getPlayUrlForRTMP($streamName)
    {
        $uri = "/{$this->appName}/{$streamName}";
        return 'rtmp://' . $this->domain . $uri . $this->getAuthKey($uri);
    }

    /**
     * 获取FLV播放地址
     * @param string $streamName 直播流名称
     * @return string
     */
    public function getPlayUrlForFLV($streamName)
    {
        $uri = "/{$this->appName}/{$streamName}.flv";
        return $this->httpPlayUrl . $uri . $this->getAuthKey($uri);
    }

    /**
     * 获取M3U8播放地址
     * @param string $streamName 直播流名称
     * @return string
     */
    public function getPlayUrlForM3U8($streamName)
    {
        $uri = "/{$this->appName}/{$streamName}.m3u8";
        return $this->httpPlayUrl . $uri . $this->getAuthKey($uri);
    }

    /**
     * 获取阿里云播放地址
     * @param string $streamName 直播流名称
     * @return array
     */
    public function getPlayUrls($streamName)
    {
        return [
            'rtmp' => $this->getPlayUrlForRTMP($streamName),
            'flv' => $this->getPlayUrlForFLV($streamName),
            'm3u8' => $this->getPlayUrlForM3U8($streamName)
        ];
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
     * 获取签名过期时间
     * @return int
     */
    public function getExpirationTime()
    {
        return $this->expirationTime;
    }

    /**
     * @return string
     */
    public function getPlayScheme()
    {
        return $this->playScheme;
    }

    /**
     * 获取录像播放地址
     * @param string $uri
     * @return string
     */
    public function getRecordUrl($uri)
    {
        return '//' . $this->recordDomain . '/' . $uri;
    }

    /**
     * @return string
     */
    public function getHttpPlayUrl()
    {
        return $this->httpPlayUrl;
    }
}