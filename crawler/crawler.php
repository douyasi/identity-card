<?php

/**
 * 行政区划代码历史沿革数据采集脚本
 *
 * @author raoyc <raoyc2009@gmail.com>
 */


function analyze_data($url, $key, &$places)
{
    $res = get_request($url);
    // 进行源码预处理工作
    $res = strip_tags($res);
    $res = str_replace(' ', '', $res);
    $res = str_replace('&nbsp;', '', $res);

    $res = str_replace('   ', '', $res);
    $res = preg_replace('/\r\n/', ' ', $res);
    $res = preg_replace('/\n/', ' ', $res);
    $res = preg_replace('/(.*)110000(.*)/', '110000${2}', $res);
    $res = preg_replace('/(.*)注(.*)/', '${1}', $res);
    $count = preg_match_all('/([\d]{6})\s+([\x{4e00}-\x{9fa5}]{3,})/imu', $res, $match, PREG_PATTERN_ORDER);
    $file = __DIR__.'/data/'.$key.'.txt';
    if (file_exists($file)) {
        unlink($file);
    }
    $content = '';
    if ($count > 0) {
        for ($i = 0; $i < $count; $i ++) {
            if (!array_key_exists($match[1][$i], $places)) {
                $places[$match[1][$i]] = $match[2][$i];
            }
            $content .= $match[1][$i].':'.$match[2][$i].PHP_EOL;
        }
    }
    file_put_contents($file, $content);
    return $places;
}

function get_request($url)
{
    $con = curl_init($url);
    curl_setopt($con, CURLOPT_HEADER, false);
    curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($con);
    $errno = curl_errno($con);
    if ($errno) {
        throw \Exception('Curl error : '.curl_error($con)) ;
    }
    curl_close($con);
    return $res;
}

function main() {

    // 暂存所有行政区划代码
    $places = array();

    // 行政区划新老历史沿革 数据 网址
    $urls = require __DIR__.'/urls.php';
    foreach ($urls as $key => $url) {
        analyze_data($url, $key, $places);
    }
    ksort($places);
    $code = '<?php'.PHP_EOL.'// 硬编码数据 1980-2017 年行政区划历史变更数据'.PHP_EOL.'$aDivisions = array('.PHP_EOL;
    foreach ($places as $id => $name) {
        $code .= "    '".$id."' => '".$name."',".PHP_EOL;
    }
    $code .= ');'.PHP_EOL;
    $php_data_file = __DIR__.'/data/all.php';
    if (file_exists($php_data_file)) {
        unlink($php_data_file);
    }
    file_put_contents($php_data_file, $code);
    unset($places);
    echo 'Good, all have done!'.PHP_EOL;
}

main();