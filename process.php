<?php

require 'libs/Slim/Slim.php';
require_once 'helpers/DBHelper.php';
require 'crawler/JobCrawlerController.php';
require 'crawler/Vieclam24hEngine.php';
require 'crawler/CareerbuilderEngine.php';
require 'crawler/CareerlinkEngine.php';

use helpers\DBHelper as DBHelper;

header("content-type: text/html; charset=UTF-8");
set_time_limit(2147483647);

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->post('/jobs', function () use ($app) {
    $response = array();
    $source = $_POST["source"];
    $careerLinks = "";
    if (isset($_POST["career_link"])) {
        $careerLinks = $_POST["career_link"];
    }
    $careerTitles = "";
    if (isset($_POST["career_title"])) {
        $careerTitles = $_POST["career_title"];
    }

    $limit = $_POST["limit_jobs"];

    $crawler = new \crawler\JobCrawlerController();
    $crawler->process($source, $careerTitles, $careerLinks, $limit);
    echoResponse(200, $response);
});

$app->get('/jobs', function () use ($app) {
    $dbHelper = DBHelper::instance();
    $response = $dbHelper->select('jobs');
    echoResponse(200, $response);
});

$app->get('/sources', function () use ($app) {
    $dbHelper = DBHelper::instance();
    if (isset($_GET['source_id'])) {
        $where = array();
        $pair = array();
        $pair['source_id'] = $_GET['source_id'];
        $where['where'] = $pair;
        $response = $dbHelper->select('sources', $where);
    }
    else {
        $response = $dbHelper->select('sources');
    }
    echoResponse(200, $response);
});

$app->get("/careers", function() use ($app) {
    if (isset($_GET['source_id'])) {
        $source = $_GET['source_id'];
        $crawler = new \crawler\JobCrawlerController();
        $list = $crawler->getCareers($source);
        echoResponse(200, $list);
    }
    else {
        //failed
    }
});

//update du lieu se lay ve cua bo schedule
$app->post("/update/schedule", function () use ($app) {
    $response = array();

    $dbHelper = DBHelper::instance();
    $infoSchedule = $dbHelper->select(SCHEDULE_TABLE);

    //du lieu nhap tu form
    $source = $_POST["source"];
    $careerLinks = "";
    if (isset($_POST["career_link"])) {
        $careerLinks = $_POST["career_link"];
    }
    $careerTitles = "";
    if (isset($_POST["career_title"])) {
        $careerTitles = $_POST["career_title"];
    }

    $limit = $_POST["limit_jobs"];

    //du lieu se duoc update vao trong database
    $dataUpdate = array();
    $dataUpdate[SCHEDULE_CAREER_TITLE_COLUMN] = $careerTitles;
    $dataUpdate[SCHEDULE_CAREER_LINK_COLUMN] = $careerLinks;
    $dataUpdate[SCHEDULE_LIMIT_JOBS_COLUMN] = $limit;
    $dataUpdate[SCHEDULE_SOURCE_COLUMN] = $source;

    //dieu kien update du lieu
    $condition = array();
    $where = array();
    $where[SCHEDULE_ID_COLUMN] = $infoSchedule[0][SCHEDULE_ID_COLUMN];
    $condition['where'] = $where;

    $dbHelper->update(SCHEDULE_TABLE, $dataUpdate, $condition);

    echoResponse(200, $response);
});

function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

//autoload of classes
function __autoload($className) {
    $filename = $className . ".php";
    if (is_readable($filename)) {
        require $filename;
    }
}

$app->run();
