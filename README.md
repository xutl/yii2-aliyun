# Aliyun Extension for Yii 2

适用于 Yii2 的 Aliyun SDK。使用了DI实现的，可自行继承扩展。

[![Latest Stable Version](https://poser.pugx.org/xutl/yii2-aliyun/v/stable.png)](https://packagist.org/packages/xutl/yii2-aliyun)
[![Total Downloads](https://poser.pugx.org/xutl/yii2-aliyun/downloads.png)](https://packagist.org/packages/xutl/yii2-aliyun)
[![Reference Status](https://www.versioneye.com/php/xutl:yii2-aliyun/reference_badge.svg)](https://www.versioneye.com/php/xutl:yii2-aliyun/references)
[![Build Status](https://img.shields.io/travis/xutl/yii2-aliyun.svg)](http://travis-ci.org/xutl/yii2-aliyun)
[![Dependency Status](https://www.versioneye.com/php/xutl:yii2-aliyun/dev-master/badge.png)](https://www.versioneye.com/php/xutl:yii2-aliyun/dev-master)
[![License](https://poser.pugx.org/xutl/yii2-aliyun/license.svg)](https://packagist.org/packages/xutl/yii2-aliyun)


Installation
------------

Next steps will guide you through the process of installing yii2-admin using [composer](http://getcomposer.org/download/). Installation is a quick and easy three-step process.

### Step 1: Install component via composer

Either run

```
composer require --prefer-dist xutl/yii2-aliyun
```

or add

```json
"xutl/yii2-aliyun": "~2.0.0"
```

to the `require` section of your composer.json.

### Step 2: Configuring your application

Add following lines to your main configuration file:

```php
'components' => [
    'aliyun' => [
        'class' => 'xutl\aliyun\Sms',  
        'accessId' => '123456',
        'accessKey' => '654321', 
        'components' => [
             //各子组件配置，如果无需配置不写即可。也可动态注入配置。
            //etc
        ]
    ],
],
```

## License

This is released under the MIT License. See the bundled [LICENSE.md](LICENSE.md)
for details.