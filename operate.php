<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once './qcloudapi-sdk-php-master/src/QcloudApi/QcloudApi.php';

class OperateEcs
{
    private $config = array(
        'SecretId' => '在index.php里面修改',
        'SecretKey' => '在index.php里面修改',
        'RequestMethod' => 'GET',
        'DefaultRegion' => 'bj');

    private $cvm;

    function __construct($SecretId, $SecretKey)
    {
        $this->config['SecretId'] = $SecretId;
        $this->config['SecretKey'] = $SecretKey;
        $this->cvm = QcloudApi::load(QcloudApi::MODULE_CVM, $this->config);
    }

    public function get($region = null)
    {
        $package = array('offset' => 0,
            'limit' => 5,
            'Region' => $region, // 当Region不是上面配置的DefaultRegion值时，可以重新指定请求的Region
            'SignatureMethod' => 'HmacSHA256',//指定所要用的签名算法，可选HmacSHA256或HmacSHA1，默认为HmacSHA1
        );
        // 请求方法为对应接口的接口名，请参考wiki文档上对应接口的接口名
        $a = $this->cvm->DescribeInstances($package);
        if ($a === false) {
            $error = $this->cvm->getError();
            return "Error code:" . $error->getCode() . ".\n" . "message:" . $error->getMessage() . ".\n" . "ext:" . var_export($error->getExt(), true) . ".\n";
        }
        return $a;
    }
    public function getImg($region = null)
    {
        $this->cvm = QcloudApi::load(QcloudApi::MODULE_IMAGE, $this->config);
        $package = array('offset' => 0,
            'limit' => 5,
            'imageType' => 2, // 当Region不是上面配置的DefaultRegion值时，可以重新指定请求的Region
            'SignatureMethod' => 'HmacSHA256',//指定所要用的签名算法，可选HmacSHA256或HmacSHA1，默认为HmacSHA1
        );
        // 请求方法为对应接口的接口名，请参考wiki文档上对应接口的接口名
        $a = $this->cvm->DescribeImages($package);
        if ($a === false) {
            $error = $this->cvm->getError();
            return "Error code:" . $error->getCode() . ".\n" . "message:" . $error->getMessage() . ".\n" . "ext:" . var_export($error->getExt(), true) . ".\n";
        }
        return $a;
    }

    public function status($instanceId, $region = null)
    {
        $package = array('offset' => 0,
            'SignatureMethod' => 'HmacSHA256',
            'Region' => $region,
            'instanceIds.0' => $instanceId
        );
        // 请求方法为对应接口的接口名，请参考wiki文档上对应接口的接口名
        $a = $this->cvm->DescribeInstances($package);
        if ($a === false) {
            $error = $this->cvm->getError();
            return "Error code:" . $error->getCode() . ".\n" . "message:" . $error->getMessage() . ".\n" . "ext:" . var_export($error->getExt(), true) . ".\n";
        }

        //var_dump($a);
        $status = $a['instanceSet'][0]['status'];
        $alls = array(1 => '故障',
            2 => '运行中',
            3 => '创建中',
            4 => '已关机',
            5 => '已退还',
            6 => '退还中',
            7 => '重启中',
            8 => '开机中',
            9 => '关机中',
            10 => '密码重置中',
            11 => '格式化中',
            12 => '镜像制作中',
            13 => '带宽设置中',
            14 => '重装系统中',
            15 => '域名绑定中',
            16 => '域名解绑中',
            17 => '负载均衡绑定中',
            18 => '负载均衡解绑中',
            19 => '升级中',
            20 => '密钥下发中');
        if (isset($alls[$status])) {
            $status = 'IP：' . $a['instanceSet'][0]['wanIpSet'][0] . '，系统：' . $a['instanceSet'][0]['os'] . '，状态：' . $alls[$status] . '，到期时间：' . $a['instanceSet'][0]['deadlineTime'];
        } else {
            $status = 'IP：' . $a['instanceSet'][0]['wanIpSet'][0] . '，系统：' . $a['instanceSet'][0]['os'] . '，状态：' . '维护中(不能对实例进行操作，但不影响正常运行)' . '，到期时间：' . $a['instanceSet'][0]['deadlineTime'];
        }

        return $status;
    }

    public function start($instanceId, $region = null)
    {
        $package = array('offset' => 0,
            'SignatureMethod' => 'HmacSHA256',
            'Region' => $region,
            'instanceIds.0' => $instanceId
        );
        // 请求方法为对应接口的接口名，请参考wiki文档上对应接口的接口名
        $a = $this->cvm->StartInstances($package);
        if ($a === false) {
            $error = $this->cvm->getError();
            return "Error code:" . $error->getCode() . ".\n" . "message:" . $error->getMessage() . ".\n" . "ext:" . var_export($error->getExt(), true) . ".\n";
        }
        if ($a['codeDesc'] == 'Success') {
            return '操作成功';
        }
        return $a;
    }

    public function stop($instanceId, $region = null)
    {
        $package = array('offset' => 0,
            'SignatureMethod' => 'HmacSHA256',
            'Region' => $region,
            'instanceIds.0' => $instanceId,
            'forceStop' => 1
        );
        // 请求方法为对应接口的接口名，请参考wiki文档上对应接口的接口名
        $a = $this->cvm->StopInstances($package);
        if ($a === false) {
            $error = $this->cvm->getError();
            return "Error code:" . $error->getCode() . ".\n" . "message:" . $error->getMessage() . ".\n" . "ext:" . var_export($error->getExt(), true) . ".\n";
        }
        if ($a['codeDesc'] == 'Success') {
            return '操作成功';
        }
        return $a;
    }

    public function reboot($instanceId, $region = null)
    {
        $package = array('offset' => 0,
            'SignatureMethod' => 'HmacSHA256',
            'Region' => $region,
            'instanceIds.0' => $instanceId,
            'forceStop' => 1
        );
        // 请求方法为对应接口的接口名，请参考wiki文档上对应接口的接口名
        $a = $this->cvm->RestartInstances($package);
        if ($a === false) {
            $error = $this->cvm->getError();
            return "Error code:" . $error->getCode() . ".\n" . "message:" . $error->getMessage() . ".\n" . "ext:" . var_export($error->getExt(), true) . ".\n";
        }
        if ($a['codeDesc'] == 'Success') {
            return '操作成功';
        }
        return $a;
    }

    public function getDisk($instanceId)
    {
        $response = '';
        return $response;
    }

    /**
     * 重装系统
     * @param $instanceId
     * @param $imgId
     * @param null $region
     * @return mixed|SimpleXMLElement
     */
    public function rebuild($instanceId, $imgId, $region = null)
    {
        $package = array(
            'offset' => 0,
            'SignatureMethod' => 'HmacSHA256',
            'Region' => $region,
            'instanceId' => $instanceId,
            'imageType' => 2,
            'imageId' => $imgId,
            'password' => 'Qq19060Qq19060',
            'needSecurityAgent' => 0,
            'needMonitorAgent' => 0
        );

        // 请求方法为对应接口的接口名，请参考wiki文档上对应接口的接口名
        $a = $this->cvm->ResetInstances($package);
        if ($a === false) {
            $error = $this->cvm->getError();
            return "Error code:" . $error->getCode() . ".\n" . "message:" . $error->getMessage() . ".\n" . "ext:" . var_export($error->getExt(), true) . ".\n";
        }
        if ($a['codeDesc'] == 'Success') {
            return '操作成功，初始密码：Qq19060Qq19060';
        }
        return $a;
    }
}