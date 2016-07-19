<?php
namespace Manasms\PhpSms;
use Toplan\PhpSms\Agent;
//require_once __DIR__.'/../lib/nusoaplib/nusoap.php';

require_once 'E:/heracles/vendor/autoload.php';
use GuzzleHttp\Client;

class YimeiAgent extends Agent {
    //override
    //发送短信一级入口
    public function sendSms($to, $content, $tempId, array $data)
    {

        /**
         * 初始化 webservice 客户端
         */
//        $this->soap = new Client(
//            $this->url,false,$this->proxyhost,$this->proxyport,$this->proxyusername
//            ,$this->proxypassword,$this->timeout,$this->response_timeout);
//        $this->soap->soap_defencoding = $this->outgoingEncoding;
//        $this->soap->decode_utf8 = false;
        //在这个方法中调用二级入口
        //根据你使用的服务商的接口选择调用哪个方式发送短信
        $this->sendContentSms($to, $content);
    }

    //override
    //发送短信二级入口：发送内容短信
    public function sendContentSms($to, $content)
    {
        //亿美接口参数
        $params = array('arg0'=>$this->serialNumber,'arg1'=>$this->sessionKey,'arg2'=>'','arg3'=>$to,
            'arg4'=>$content,'arg5'=>'', 'arg6'=>'UTF-8','arg7'=>'5','arg8'=>'8888'
        );

        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $this->url,
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);


//        foreach($to as $to)
//        {
//            array_push($params,new soapval("arg3",false,$to));
//        }

        //可用方法:
//        $result = $this->curlPost($url, $params);
//        $result = $this->execResult($result);
//        $result = $this->soap->__call("sendSMS",$params,$this->namespace);
        $response = $client->request('GET', '/sdk/SDKService', $params);
        dd($response);
//        echo $response->getStatusCode();
        exit;
        //切记更新发送结果
        if (0 == $result[1]) {
            $this->result('success', true);//发送成功
        } else {
            $this->result('success', false);//发送失败
        }
        $this->result('code', $result[1]);//发送结果代码
        switch ($result[1]) {
            case '0': //成功
                $this->result('info', '发送成功');//发送结果信息说明
                break;
            case '101': //无此用户
                $this->result('info', '无此用户');//发送结果信息说明
                break;
            case '102': //密码错
                $this->result('info', '密码错误');//发送结果信息说明
                break;
            case '103': //提交过快（提交速度超过流速限制）
                $this->result('info', '提交过快');//发送结果信息说明
                break;
            case '104': //系统忙（因平台侧原因，暂时无法处理提交的短信）
                $this->result('info', '系统忙');//发送结果信息说明
                break;
            case '105': //敏感短信（短信内容包含敏感词）
                $this->result('info', '短信内容包含敏感词');//发送结果信息说明
                break;
            case '106': //消息长度错（>536或<=0）
                $this->result('info', '消息长度错误');//发送结果信息说明
                break;
            case '107': //包含错误的手机号码
                $this->result('info', '包含错误的手机号码');//发送结果信息说明
                break;
            case '108': //手机号码个数错（群发>50000或<=0;单发>200或<=0）
                $this->result('info', '手机号码个数错误');//发送结果信息说明
                break;
            case '109': //无发送额度（该用户可用短信数已使用完）
                $this->result('info', '可用短信数已使用完');//发送结果信息说明
                break;
            case '110': //不在发送时间内
                $this->result('info', '不在发送时间内');//发送结果信息说明
                break;
            case '111': //超出该账户当月发送额度限制
                $this->result('info', '超出该账户当月发送额度限制');//发送结果信息说明
                break;
            case '112': //无此产品，用户没有订购该产品
                $this->result('info', '无此产品');//发送结果信息说明
                break;
            case '113': //extno格式错（非数字或者长度不对）
                $this->result('info', 'extno格式错');//发送结果信息说明
                break;
            case '115': //自动审核驳回
                $this->result('info', '自动审核驳回');//发送结果信息说明
                break;
            case '116': //签名不合法，未带签名（用户必须带签名的前提下）
                $this->result('info', '签名不合法');//发送结果信息说明
                break;
            case '117': //IP地址认证错,请求调用的IP地址不是系统登记的IP地址
                $this->result('info', 'IP地址认证错');//发送结果信息说明
                break;
            case '118': //用户没有相应的发送权限
                $this->result('info', '用户没有相应的发送权限');//发送结果信息说明
                break;
            case '119': //用户已过期
                $this->result('info', '用户已过期');//发送结果信息说明
                break;
            case '120': //短信内容不在白名单中
                $this->result('info', '短信内容不在白名单中');//发送结果信息说明
                break;
            default:
                $this->result('info', '短信发送失败');//发送结果信息说明
                break;
        }
    }

    //override
    //发送短信二级入口：发送模板短信
    public function sendTemplateSms($tempId, $to, array $tempData)
    {
        //同上...
    }

    //override
    //发送语音验证码入口
    public function voiceVerify($to, $code, $tempId, array $tempData)
    {
        //同上...
    }

    /**
     * 查询额度
     */
    public function queryBalance()
    {
        //查询参数
        $postArr = array(
            'account' => $this->apiAccount,
            'pswd' => $this->apiPassword,
        );
        return $this->curlPost($this->apiBalanceQueryUrl, $postArr);
    }

    private function curlPost($url,$postFields)
    {
        $postFields = http_build_query($postFields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 处理返回值
     */
    public function execResult($result)
    {
        return preg_split("/[,\r\n]/", $result);
    }

}
