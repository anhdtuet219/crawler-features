<?php
/**
 * Created by PhpStorm.
 * User: anansaj
 * Date: 4/16/2018
 * Time: 10:37 PM
 */

namespace crawler;
include ("db_config");

define('VIEC_LAM_24H', '1');
define('CAREERLINK', '2');
define('CAREERBUILDER', '3');

use DOMDocument;
use DOMXPath;
use helpers\DBHelper;

class JobCrawlerController {

    public static $instance;

    //helper using connect database
    public $engine;
    public $dbHelper;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new JobCrawlerController();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->dbHelper = DBHelper::instance();
    }

    public function getCareers($source) {
        //get all type job links from $seedUrl
        $res = $this->dbHelper->select('sources');
        $info = array();
        foreach ($res as $key => $value) {
            if ($value['source_id'] === ''.$source.'') {
                $info = $value;
                break;
            }
        }
        switch ($source) {
            case VIEC_LAM_24H:
                $this->engine = Vieclam24hEngine::getInstance();
                break;
            case CAREERLINK:
                $this->engine = CareerlinkEngine::getInstance();
                break;
            case CAREERBUILDER:
                $this->engine = CareerbuilderEngine::getInstance();
                break;
        }
        $this->engine->setInfoEngine($info);
        return $this->engine->getAllTypeJobLinks();
    }

    public function process($source, $limit) {
        //get all type job links from $seedUrl
        $res = $this->dbHelper->select('sources');
        $info = array();
        foreach ($res as $key => $value) {
            if ($value['source_id'] === ''.$source.'') {
                $info = $value;
                break;
            }
        }
        switch ($source) {
            case VIEC_LAM_24H:
                $this->engine = Vieclam24hEngine::getInstance();
                $this->engine->setLimit($limit);
                $this->engine->setInfoEngine($info);
                $this->engine->process();
                break;
            case CAREERLINK:
                $this->engine = CareerlinkEngine::getInstance();
                $this->engine->setLimit($limit);
                $this->engine->setInfoEngine($info);
                $this->engine->process();
                break;
            case CAREERBUILDER:
                $this->engine = CareerbuilderEngine::getInstance();
                $this->engine->setLimit($limit);
                $this->engine->setInfoEngine($info);
                $this->engine->process();
                break;
        }
    }

    public function processTest($source, $careerTitles, $careerLinks, $limit) {
        //get all type job links from $seedUrl
        $res = $this->dbHelper->select('sources');
        $info = array();
        foreach ($res as $key => $value) {
            if ($value['source_id'] === ''.$source.'') {
                $info = $value;
                break;
            }
        }
        switch ($source) {
            case VIEC_LAM_24H:
                $this->engine = Vieclam24hEngine::getInstance();
                break;
            case CAREERLINK:
                $this->engine = CareerlinkEngine::getInstance();
                break;
            case CAREERBUILDER:
                $this->engine = CareerbuilderEngine::getInstance();
                break;
        }

        $this->engine->setLimit($limit);
        $this->engine->setInfoEngine($info);
        $this->engine->setCrawlCareersList($careerTitles, $careerLinks);
        $this->engine->process();
    }


}
