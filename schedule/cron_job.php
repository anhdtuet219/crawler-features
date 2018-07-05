<?php
/**
 * Created by PhpStorm.
 * User: anansaj
 * Date: 6/26/2018
 * Time: 7:09 PM
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/features/crawler/helpers/DBHelper.php';

use helpers\DBHelper as DBHelper;

set_time_limit(2147483647);

$dbHelper = DBHelper::instance();
$infoSchedule = $dbHelper->select(SCHEDULE_TABLE);

//chung ta chi can lay 1 phan tu trong bang schedule boi vi chung ta chi chay 1 schedule trong 1 thoi diem

$url = $infoSchedule[0][SCHEDULE_API_LINK_COLUMN];
$params = array(
    'source' => $infoSchedule[0][SCHEDULE_SOURCE_COLUMN],
    'career_link' => $infoSchedule[0][SCHEDULE_CAREER_LINK_COLUMN],
    'limit_jobs' => $infoSchedule[0][SCHEDULE_LIMIT_JOBS_COLUMN],
    'career_title' => $infoSchedule[0][SCHEDULE_CAREER_TITLE_COLUMN]
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);

// This should be the default Content-type for POST requests
//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));

$result = curl_exec($ch);
if(curl_errno($ch) !== 0) {
    error_log('cURL error when connecting to ' . $url . ': ' . curl_error($ch));
}

curl_close($ch);
print_r($result);