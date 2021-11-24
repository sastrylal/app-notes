<?php

if (!function_exists("setError")) {

    function setError($message, $sub = '') {
        if (!empty($sub) && !empty($message)) {
            $_SESSION['error'][$sub] = $message;
        } else if (!empty($message)) {
            $_SESSION['error'] = $message;
        }
    }

}

if (!function_exists("setMessage")) {

    function setMessage($message, $sub = '') {
        if (!empty($sub) && !empty($message)) {
            $_SESSION['message'][$sub] = $message;
        } else if (!empty($message)) {
            $_SESSION['message'] = $message;
        }
    }

}

if (!function_exists("getMessage")) {

    function getMessage($sub = '') {
        if (!empty($sub) && !empty($_SESSION[$sub]['message'])) {
            echo '<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">×</button> ' . $_SESSION[$sub]['message'] . '</div>';
            $_SESSION[$sub]['message'] = "";
        }// if end.
        if (empty($sub) && !empty($_SESSION['message'])) {
            echo '<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">×</button> ' . $_SESSION['message'] . '</div>';
            $_SESSION['message'] = "";
        }// if end.
        if (!empty($sub) && !empty($_SESSION[$sub]['error'])) {
            echo '<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert">×</button> ' . $_SESSION[$sub]['error'] . '</div>';
            $_SESSION[$sub]['error'] = "";
        }// if end.
        if (empty($sub) && !empty($_SESSION['error'])) {
            echo '<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert">×</button> ' . $_SESSION['error'] . '</div>';
            $_SESSION['error'] = "";
        }// if end.
    }

}

if (!function_exists("generatePassword")) {

    function generatePassword($length = 8) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $str = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++)
            $str .= $chars[rand(0, $max)];
        return $str;
    }

}

if (!function_exists("dateDB2SHOW")) {

    function dateDB2SHOW($db_date = "", $display = "") {
        if (!empty($db_date) && $db_date != "0000-00-00" && $db_date != "0000-00-00 00:00:00") {
            $db_date = strtotime($db_date);
            return date("d/m/Y", $db_date);
        }
        return $display;
    }

}

if (!function_exists("dateTimeDB2SHOW")) {

    function dateTimeDB2SHOW($db_date = "", $format = "", $display = "") {
        if (!empty($db_date) && $db_date != "0000-00-00" && $db_date != "0000-00-00 00:00:00") {
            $db_date = strtotime($db_date);
            if (!empty($format)) {
                return date($format, $db_date);
            } else {
                return date("m/d/Y h:i A", $db_date);
            }
        }
        return $display;
    }

}

if (!function_exists("dateForm2DB")) {

    function dateForm2DB($frm_date) {
        $frm_date = explode("/", $frm_date);
        if (!empty($frm_date[0]) && !empty($frm_date[1]) && !empty($frm_date[2])) {
            return $frm_date[2] . "-" . $frm_date[1] . "-" . $frm_date[0];
        } else {
            return "";
        }
    }

}

if (!function_exists("dateTimeForm2DB")) {

    function dateTimeForm2DB($frm_date) {
        $frm_date_time = explode(" ", $frm_date);
        $frm_date = explode("/", $frm_date_time[0]);
        $frm_time = explode(":", $frm_date_time[1]);
        if (!empty($frm_date[0]) && !empty($frm_date[1]) && !empty($frm_date[2])) {
            if (!isset($frm_time[0]))
                $frm_time[0] = "00";
            if (!isset($frm_time[1]))
                $frm_time[1] = "00";
            if (!isset($frm_time[2]))
                $frm_time[2] = "00";
            return $frm_date[2] . "-" . $frm_date[0] . "-" . $frm_date[1] . " " . $frm_time[0] . ":" . $frm_time[1] . ":" . $frm_time[2];
        } else {
            return "";
        }
    }

}

if (!function_exists("priceFormat")) {

    function priceFormat($price) {
        return number_format($price, 2);
    }

}

if (!function_exists("stdNameFormat")) {

    function stdNameFormat($str, $space = '-') {
        $str = strtolower($str);
        $str = str_replace("  ", " ", $str);
        $str = str_replace(" ", $space, $str);
        return $str;
    }

}

if (!function_exists("stdURLFormat")) {

    function stdURLFormat($str, $space = '-') {
        $str = strtolower($str);
        $str = str_replace("  ", " ", $str);
        $str = str_replace(" ", $space, $str);
        $str = preg_replace('/[^a-z0-9\-\_]/i', '', $str);
        return $str;
    }

}


if (!function_exists("shortDesc")) {

    function shortDesc($str, $len = 300) {
        $str = substr($str, 0, $len);
        return $str;
    }

}

function curl_get_contents($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

if (!function_exists("getIPInfo")) {

    function getIPInfo($ip = '') {
        if (empty($ip)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return json_decode(curl_get_contents("http://ipinfo.io/{$ip}/json"));
    }

}

if (!function_exists("passwordEncode")) {

    function passwordEncode($pwd) {
        return password_hash($pwd, PASSWORD_BCRYPT);
    }

}

if (!function_exists("passwordVerify")) {

    function passwordVerify($pwd, $hash) {
        return password_verify($pwd, $hash);
    }

}

if (!function_exists("imgUpload")) {

    function imgUpload($field, $folder = '/data/', $file_name = '', $overwrite = true) {
        $allowed_types = 'gif|jpg|jpeg|png';
        $doc_root_path = dirname(APPPATH);
        if (!empty($_FILES[$field]['name'])) {
            if (!file_exists($doc_root_path . $folder)) {
                mkdir($doc_root_path . $folder, 0755, true);
            }
            $upload_path = $doc_root_path . $folder;
            $file_info = pathinfo($_FILES[$field]['name']);
            $file_name = $file_name . "." . $file_info['extension'];
            $file_path = $upload_path . $file_name;
            if (@move_uploaded_file($_FILES[$field]["tmp_name"], $file_path)) {
                return $file_name;
            }
        }
        return "";
    }

}

if (!function_exists("sendSms")) {

    function sendSms($phone, $message = "") {
        $url = 'http://203.212.70.200/smpp/sendsms?username=balaji18&password=balaji123&to=' . $phone . '&from=text&text=' . urlencode($message);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
    }

}

if (!function_exists("latLonDistance")) {

    function latLonDistance($lat1, $lon1, $lat2, $lon2) {
        try {
            $degrees = rad2deg(acos((sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lon1 - $lon2)))));
            $distance = $degrees * 111.13384;
            return round($distance, 2);
        } catch (Exception $ex) {
            return "";
        }
    }

}
?>
