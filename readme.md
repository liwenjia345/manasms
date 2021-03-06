# ManaSms

基于[PhpSms](https://github.com/toplan/phpsms)，使用“创蓝”服务商。

# 安装

```php
composer require liwenjia345/manasms//正式版
composer require liwenjia345/manasms:dev-master//开发版
```

# 快速上手

###1. 配置

- 配置代理器所需参数

为你需要用到的短信服务商(即代理器)配置必要的参数。可以在`config\phpsms.php`中键为`agents`的数组中配置，也可以手动在程序中设置，示例如下：

```php
//example:
Sms::agents([
    'Chuanglan' => [
        //短信API key
        'apiAccount' => 'your api key',
        //短信API Password
        'apiPassword' => 'your api password',
        //代理器
        'agentClass' => 'liwenjia345\ManaSms\ChuanglanAgent',
    ]
]);
```

- 配置可用代理器

配置你的调度方案。可在`config\phpsms.php`中键为`enable`的数组中配置。也可以手动在程序中设置，示例如下：

```php
//example:
Sms::enable([
    //被使用概率为2/3
    'Chuanglan' => '20',

    //被使用概率为1/3，且为备用代理器
    'YunPian' => '10 backup',

    //仅为备用代理器
    'YunTongXun' => '0 backup',
]);
```

###2. 在laravel中使用

如果你只想单纯的在laravel中使用phpsms的功能可以按如下步骤操作，
当然也为你准备了基于phpsms开发的[laravel-sms](https://github.com/toplan/laravel-sms)

* 在config/app.php中引入服务提供器

```php
//服务提供器
'providers' => [
    ...
    Toplan\PhpSms\PhpSmsServiceProvider::class,
]

//别名
'aliases' => [
    ...
    'PhpSms' => Toplan\PhpSms\Facades\Sms::class,
]
```

* 生成配置文件

```php
php artisan vendor:publish
```
生成的配置文件为config/phpsms.php，然后在该文件中按提示配置。

* 使用

详见API，示例：
```php
PhpSms::make()->to($to)->content($content)->send();
```

###3. 详细使用见
* [laravel-sms](https://github.com/toplan/laravel-sms)
* [phpsms](https://github.com/toplan/phpsms)

# License

MIT

