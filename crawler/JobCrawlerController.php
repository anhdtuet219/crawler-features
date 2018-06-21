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

define('VIEC_LAM_24H_DOMAIN', 'vieclam24h.vn');
define('CAREERLINK_DOMAIN', 'careerlink.vn');
define('CAREERBUILDER_DOMAIN', 'careerbuilder.vn');

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

    public function process($sources, $careerTitles, $careerLinks, $limit) {
        //get all type job links from $seedUrl
        $res = $this->dbHelper->select('sources');
        $sourceArray = explode(',', $sources);
        $listCareers = $this->handleCareerData($careerTitles, $careerLinks);
        foreach ($sourceArray as $source) {
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
                    $this->engine->setInfoEngine($info);
                    $this->engine->setTypeJobLinks($listCareers[VIEC_LAM_24H_DOMAIN]);
                    break;
                case CAREERLINK:
                    $this->engine = CareerlinkEngine::getInstance();
                    $this->engine->setInfoEngine($info);
                    $this->engine->setTypeJobLinks($listCareers[CAREERLINK_DOMAIN]);
                    break;
                case CAREERBUILDER:
                    $this->engine = CareerbuilderEngine::getInstance();
                    $this->engine->setInfoEngine($info);
                    $this->engine->setTypeJobLinks($listCareers[CAREERBUILDER_DOMAIN]);
                    break;
            }

            $this->engine->setLimit($limit);
            $this->engine->process();
        }

    }

    public function handleCareerData($careerTitles, $careerLinks) {
        $titles = explode(',', $careerTitles);
        $links = explode(',', $careerLinks);
        $listCareers = array();
        $i = 0;
        $vl24h = array();
        $clink = array();
        $cbuilder = array();
        foreach ($titles as $title) {
            $career = array();
            $career['link'] = $links[$i];
            $career['title'] = $title;
            if (strpos($links[$i], VIEC_LAM_24H_DOMAIN) !== false) {
                $vl24h[] = $career;
            }
            else if (strpos($links[$i], CAREERLINK_DOMAIN) !== false) {
                $clink[] = $career;
            }
            else if (strpos($links[$i], CAREERBUILDER_DOMAIN) !== false) {
                $cbuilder[] = $career;
            }

            $i++;
        }
        $listCareers[VIEC_LAM_24H_DOMAIN] = $vl24h;
        $listCareers[CAREERLINK_DOMAIN] = $clink;
        $listCareers[CAREERBUILDER_DOMAIN] = $cbuilder;
        return $listCareers;
    }
}
