<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

/**
 * Class CDN
 *
 * 服务操作
 * @method openCdnService(array $params) 开通CDN服务
 * @method describeCdnService() 查询服务状态
 * @method modifyCdnService(array $params) modifyCdnService
 *
 * 域名操作接口
 * @method addCdnDomain(array $params) 添加加速域名
 * @method describeUserDomains(array $params) 查询域名列表
 * @method describeCdnDomainDetail(array $params) 查询域名信息
 * @method modifyCdnDomain(array $params) 修改源站信息
 * @method startCdnDomain(array $params) 启用加速域名
 * @method stopCdnDomain(array $params) 停用加速域名
 * @method deleteCdnDomain(array $params) 删除加速域名
 * @method describeDomainsBySource(array $params) 根据源站查域名
 *
 * 刷新预热接口
 * @method RefreshObjectCaches(array $params) 刷新缓存
 * @method PushObjectCache(array $params) 预热缓存
 * @method describeRefreshTasks(array $params) 查询刷新操作记录
 * @method describeRefreshQuota(array $params) 查询操作余量
 *
 * 配置操作接口
 * @method describeDomainConfigs(array $params) 查询域名配置
 * @method setOptimizeConfig(array $params) 设置页面优化
 * @method setPageCompressConfig(array $params) 设置智能压缩
 * @method setIgnoreQueryStringConfig(array $params) 设置过滤参数
 * @method setRangeConfig(array $params) 设置Range回源
 * @method setVideoSeekConfig(array $params) 设置拖拽播放
 * @method setSourceHostConfig(array $params) 设置回源HOST
 * @method setErrorPageConfig(array $params) 设置404页面
 * @method setForceRedirectConfig(array $params) 设置强制跳转
 * @method setRefererConfig(array $params) 设置防盗链
 * @method setFileCacheExpiredConfig(array $params) 设置文件类型缓存策略
 * @method setPathCacheExpiredConfig(array $params) 设置路径缓存策略
 * @method modifyFileCacheExpiredConfig(array $params) 修改文件类型缓存策略
 * @method modifyPathCacheExpiredConfig(array $params) 修改路径缓存策略
 * @method deleteCacheExpiredConfig(array $params) 删除缓存策略
 * @method setReqAuthConfig(array $params) 设置鉴权
 * @method setHttpHeaderConfig(array $params) 设置HTTP头信息
 * @method modifyHttpHeaderConfig(array $params) 修改HTTP头信息
 * @method deleteHttpHeaderConfig(array $params) 删除HTTP头信息
 * @method setDomainServerCertificate(array $params) 设置证书
 * @method setIpBlackListConfig(array $params) 设置IP黑名单
 *
 * 资源监控
 * @method describeDomainBpsData(array $params) 查询网络带宽
 * @method describeDomainFlowData(array $params) 查询流量数据
 * @method describeDomainSrcBpsData(array $params) 查询回源带宽
 * @method describeDomainSrcFlowData(array $params) 查询回源流量
 * @method describeDomainHitRateData(array $params) 查询字节缓存命中率
 * @method describeDomainReqHitRateData(array $params) 查询请求缓存命中率
 * @method describeDomainQpsData(array $params) 查询访问QPS
 * @method describeDomainHttpCodeData(array $params) 查询返回码
 * @method describeDomainsUsageByDay(array $params) 天粒度资源使用概览
 * @method describeTopDomainsByFlow(array $params) 天粒度按流量域名排名
 * @method describeDomainPvData(array $params) 查询PV数据
 * @method describeDomainUvData(array $params) 查询UV数据
 * @method describeDomainRegionData(array $params) 用户区域占比
 * @method describeDomainISPData(array $params) 运营商占比
 * @method describeDomainTopUrlVisit(array $params) 查询热门URL
 * @method describeDomainTopReferVisit(array $params) 查询热门Refer
 * @method describeDomainFileSizeProportionData(array $params) 文件大小占比
 * @method describeDomainCCData(array $params) 查询CC数据
 * @method describeCdnRegionAndIsp(array $params) 获取区域和运营商列表
 * @method describeDomainBpsDataByTimeStamp(array $params) 查询时刻网络带宽
 * @method describeDomainMax95BpsData(array $params) 获取加速域名95带宽峰值监控数据
 * @method describeDomainPathData(array $params) 查询路径级别的监控数据
 * @method describeL2VipsByDomain(array $params) 查询L2节点vip列表
 * @method describeRangeDataByLocateAndIspService(array $params) 查询区域运营商资源信息
 * @method describeDomainSlowRatio(array $params) 获取视频加速域名的慢速比数据
 *
 * 全站加速
 * @method setDynamicConfig(array $params) 全站加速缓存规则配置
 *
 * 日志接口
 * @method describeCdnDomainLogs(array $params) 下载域名日志
 * @method describeCustomLogConfig(array $params) 查询自定义日志配置信息
 * @method describeDomainCustomLogConfig(array $params) 获取自定义日志格式配置信息
 * @method describeUserCustomLogConfig(array $params) 获取所有自定义日志配置信息
 * @method listDomainsByLogConfigId(array $params) 查询使用某自定义日志格式的域名
 * @method modifyDomainCustomLogConfig(array $params) 修改域名所属日志配置信息
 * @method modifyUserCustomLogConfig(array $params) 修改用户下自定义日志配置信息
 *
 * 辅助接口
 * @method describeIpInfo(array $params) 检测IP信息
 *
 * @see https://help.aliyun.com/document_detail/27155.html
 * @package xutl\aliyun
 */
class Cdn extends BaseClient
{
    const OBJECT_TYPE_FILE = 'File';
    const OBJECT_TYPE_DIRECTORY = 'Directory';

    /**
     * @var string
     */
    public $baseUrl = 'https://cdn.aliyuncs.com';

    /**
     * @var string
     */
    public $version = '2014-11-11';
}