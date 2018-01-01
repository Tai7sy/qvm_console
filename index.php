<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
<title>QQ云管理</title>
<body>
<?php
include_once 'operate.php';
if (isset($_POST['key']) && isset($_POST['op'])) {

    // 这里修改成自己的
    $ecs = new OperateEcs("AKIDEUiIOWDo3Jg5TtHoGiAeAiXPIEEFpzb3", "PhomaOQJ3eyUoxvhHfv0veCp6GF5jBc9");
    $allId = array(
        'key1' => array('bj', 'ins-36g8umv7'),
        'key2' => array('bj', 'ins-18o2wzwr'),

        'key3' => array('gz', 'ins-9sshtp4a'),
        'key4' => array('gz', 'ins-mmgnk3uq'),
    );
    $key = $_POST['key'];
    if (!isset($allId[$key]))
        die('Key错误');

    $ins = $allId[$key];
    $op = $_POST['op'];

    try {
        switch ($op) {
            case 'status':
                echo("操作结果: <br>");
                print_r($ecs->status($ins[1], $ins[0]));
                break;
            case 'start':
                echo("操作结果: <br>");
                print_r($ecs->start($ins[1], $ins[0]));
                break;
            case 'stop':
                echo("操作结果: <br>");
                print_r($ecs->stop($ins[1], $ins[0]));
                break;
            case 'reboot':
                echo("操作结果: <br>");
                print_r($ecs->reboot($ins[1], $ins[0]));
                break;
            case 'rebuild':
                $os = $_POST['os'];
                echo("操作结果: <br>");
                print_r($ecs->rebuild($ins[1], $os, $ins[0]));
                break;
            default:
                break;
        }
    } catch (Exception $e) {
        print_r("出现错误: " . $e->getMessage());
    }

    //exit;
} else {
    echo '请选择操作<br>';
}
?>

<br><br>
<form method="post">
    <label>Key
        <input type="text" name="key" required value="<?php echo(isset($_POST['key']) ? $_POST['key'] : '') ?>">
    </label> <br><br>
    <input type="text" name="t" required value="<?php echo(time()) ?>" hidden="hidden" style="display: none">
    <label>请选择操作
        <select name="op" onchange="changeAction()" id="action">
            <option value="status">查询状态</option>
            <option value="start">启动</option>
            <option value="stop">停止</option>
            <option value="reboot">重启</option>
            <option value="rebuild">重装</option>
        </select>
    </label>
    <label id="allOs" style="display: none">请选择(不支持Linux/Win互换, 互换请私聊)
        <select name="os">
            <?php

            $allOs = array('CentOS 5.8 32位' => 'img-7br3ouzr',
                'CentOS 5.8 64位' => 'img-4cq5l3u1',
                'CentOS 5.11 32位' => 'img-ko6c8e6f',
                'CentOS 5.11 64位' => 'img-ailu7ftt',
                'CentOS 6.2 64位' => 'img-50mr2ow7',
                'CentOS 6.3 32位' => 'img-1afi29f3',
                'CentOS 6.3 64位' => 'img-4w43a15z',
                'CentOS 6.4 32位' => 'img-k09t26i1',
                'CentOS 6.4 64位' => 'img-jlo93805',
                'CentOS 6.5 32位' => 'img-7uq6rrhr',
                'CentOS 6.5 64位' => 'img-7fwdvfur',
                'CentOS 6.6 32位' => 'img-5jbd8jxn',
                'CentOS 6.6 64位' => 'img-h5le2uy5',
                'CentOS 6.7 32位' => 'img-ljriodz5',
                'CentOS 6.7 64位' => 'img-9iwld2rx',
                'CentOS 7.0 64位' => 'img-b1ve77s9',
                'CentOS 7.1 64位' => 'img-9q2lxkar',
                'CentOS 7.2 64位' => 'img-31tjrtph',
                'Debian 7.4 64位' => 'img-c1l6bgb1',
                'Debian 7.8 32位' => 'img-2p1g2wjv',
                'Debian 7.8 64位' => 'img-feqctcrx',
                'Debian 8.2 32位' => 'img-ez7jwngr',
                'Debian 8.2 64位' => 'img-hi93l4ht',
                'Ubuntu Server 12.04 LTS 64位 (Docker)' => 'img-aa9z7opt',
                'Ubuntu Server 14.04.1 LTS 32位' => 'img-qpxvpujt',
                'Ubuntu Server 14.04.1 LTS 64位' => 'img-3wnd9xpl',
                'openSUSE 12.3 32位' => 'img-8bf2kz5x',
                'openSUSE 12.3 64位' => 'img-1p6m0vz5',
                'openSUSE 13.2 64位' => 'img-pmhtrjdx',
                'SUSE Linux Enterprise Server 11 SP3 64位' => 'img-mg89zx1h',
                'SUSE Linux Enterprise Server 12 64位' => 'img-d5304izr',
                'FreeBSD 10.1 64位' => 'img-871lthrb',
                'CoreOS 717.3.0 64位' => 'img-6mre94jv',

                //'Windows Server 2003 企业版 SP2 32位' => 'img-mf36ei85',
                //'Windows Server 2003 R2 企业版 SP2 64位' => 'img-b5a3yjfr',
                'Windows Server 2008 R2 企业版 SP1 64位' => 'img-0vbqvzfn',
                'Windows Server 2012 R2 标准版 64位英文版' => 'img-lkxqa4kj',
                'Windows Server 2012 R2 标准版 64位中文版' => 'img-egif9bvl',
                'Windows Server 2012 R2 数据中心版 64位英文版' => 'img-2tddq003',
                'Windows Server 2012 R2 数据中心版 64位中文版' => 'img-29hl923v');

            foreach ($allOs as $osName => $osId) {
                echo ' <option value="' . $osId . '">' . $osName . '</option>';
            }
            ?>
        </select>
    </label>
    <br><br>
    <button type="submit">确认操作</button>
    <br>
    <span>(提示 request failed 多试几次就好)</span>
</form>
<script>
    function changeAction() {
        if (document.getElementById('action').value === 'rebuild') {
            document.getElementById('allOs').style.display = 'block';
        } else {
            document.getElementById('allOs').style.display = 'none';
        }
    }
</script>
</body>
</html>
