<?php

require 'libs/Slim/Slim.php';
require_once 'helpers/DBHelper.php';
require 'crawler/JobCrawlerController.php';
require 'crawler/Vieclam24hEngine.php';
require 'crawler/CareerbuilderEngine.php';
require 'crawler/CareerlinkEngine.php';

header("content-type: text/html; charset=UTF-8");
set_time_limit(2147483647);

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();


// $app->post('/jobs', function () use ($app) {
//     $response = array();
//     $source = $_POST["source"];
//     $limit = $_POST["limit-jobs"];
//     $response["ok"] = "ok";
//     $response["source"] = $source;
//     $response["limit"] = $limit;
//     $crawler = new \crawler\JobCrawlerController();
//     $crawler->process($source, $limit);
//     echoResponse(200, $response);
// });

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
    $crawler->processTest($source, $careerTitles, $careerLinks, $limit);
    echoResponse(200, $response);
});

$app->get('/jobs', function () use ($app) {
    $dbHelper = \helpers\DBHelper::instance();
    $response = $dbHelper->select('jobs');
    echoResponse(200, $response);
});

$app->get('/sources', function () use ($app) {
    $dbHelper = \helpers\DBHelper::instance();
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
    $careers = array();
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
