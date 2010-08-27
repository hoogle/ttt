<?php
require "config/config.php";
$uid = getUserID(USERNAME);

function getUserID()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/3.0.195.33 Safari/532.0');
    curl_setopt($ch, CURLOPT_URL, "http://www.plurk.com/" . USERNAME);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $contents = curl_exec($ch);
    curl_close($ch);
    preg_match('/var GLOBAL = \{.*"uid": ([\d]+),.*\}/imU', $contents, $pid);
    $uid = $pid[1];
    return $uid;
}

function do_act($target_url, $data = NULL)
{
    $cookie_file = "/tmp/plurk_cookie";
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_URL, $target_url);
    curl_setopt($ch, CURLOPT_POST, true);
    if ( ! is_null($data))
    {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

$login_url = "http://www.plurk.com/API/Users/login"; 
$login_data = array(
    "api_key" => API_KEY,
    "username" => USERNAME,
    "password" => PASSWORD,
    "no_data" => 1,
);
do_act($login_url, $login_data);

$func = $_SERVER['argv'][1];

switch ($func)
{
    case "nostop":
        $post_url = "http://www.plurk.com/TimeLine/addPlurk"; 
        $priv = "true"; 
        $limited_to = ($priv == "true") ? "[$uid]" : "";
        $post_data = array(
            "qualifier" => "has",
            "content" => "!FB " . date("H:i") . " 了，還沒關機喔！",
            "lang" => "tr_ch",
            "limited_to" => $limited_to,
            "no_comments" => 0,
            "uid" => $uid
        );
        do_act($post_url, $post_data);
        break;

    case "morning":
        do
        {
            $target_url = "http://www.plurk.com/API/Realtime/getUserChannel?api_key=" . API_KEY;
            $arr = json_decode(do_act($target_url, NULL), TRUE);
        }
        while ( ! array_key_exists("comet_server", $arr));

        $comet_server_uri = substr($arr["comet_server"], 0, strlen($arr["comet_server"])-1);
        echo $arr["comet_server"] . "\n";

        $url = $arr["comet_server"];
        $arr = json_decode(do_act($url, NULL), TRUE);
        echo "offset={$arr['new_offset']}\t";
        while (array_key_exists("data", $arr) && $arr["new_offset"] != -3)
        {
            $user_arr = $arr["data"][0]["response"]["user_id"];
            $poster = $arr["data"][0]["user"][$user_arr]["display_name"] . " (" . $arr["data"][0]["user"][$user_arr]["karma"]. ")\n";
            $post = $arr["data"][0]["response"]["content_raw"] . "\n";
            echo iconv("utf-8", "big5", trim($poster)) . " => " . iconv("utf-8", "big5", trim($post)) . "\n";
            $new_offset = $arr["new_offset"] + 1;
            $url = $comet_server_uri . $new_offset;
            echo "offset={$new_offset}\t";
            $arr = json_decode(do_act($url, NULL), TRUE);
        }
        break;
}
echo "\n";
?>
