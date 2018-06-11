<?php
/**
 * Created by PhpStorm.
 * User: anansaj
 * Date: 4/30/2018
 * Time: 6:52 PM
 */

namespace crawler;

use DOMDocument;
use DOMXPath;
use helpers\DBHelper;

define('H_LIMIT_DEFAULT', 10);

class Vieclam24hEngine extends AbstractEngine
{
    public function __construct() {
        parent::__construct();
    }

    public function process()
    {
        //delete all before records
        $condition = array();
        $condition[JOB_SOURCE_TYPE] = $this->sourceId;
        $this->dbHelper->delete(TABLE_DB, $condition);
        //get all type job links from $seedUrl
        $this->typeJobLinks = $this->getAllTypeJobLinks();
        $this->getJobsAndInsertDb();
    }

    public function getJobsAndInsertDb()
    {
        $i = 0;
        //print_r($this->typeJobLinks);
        foreach ($this->typeJobLinks as $career) {
            $title = $career['title'];
            $link = $career['link'];
            $this->getJobsFromOneLink($title, $link);
            $i++;
        }
    }

    public function getJobsFromOneLink($title, $link) {
        //with each type job url -> get all jobs from that type
        //foreach ($this->typeJobLinks as $link) {
        $HTML = @file_get_contents($link,0, $this->mContext);
        $doc = new DOMDocument();
        @$doc->loadHTML($HTML);
        $selector = new DOMXPath($doc);

        $result = $selector->query('//ul[contains(@class, "pagination")]/li/a');

        foreach ($result as $res) {
            if ($res->hasAttribute('onclick')) {
                $temp = $res->getAttribute('onclick');
                //get url from string
                preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $temp, $match);
                $url = $match[0][0];
                $arrayParams = explode('&', $url);
                $urlRebuild = "";
                foreach ($arrayParams as $param) {
                    if (strpos($param, 'lay_tin_mien_phi') !== false || strpos($param, 'page') !== false) {
                        $urlRebuild .= "";
                    }
                    else if (strpos($param, 'limit') !== false) {
                        //increase limit to get more articles
                        $urlRebuild .= "limit=" . $this->limit;
                    }
                    else {
                        $urlRebuild .= $param . "&";
                    }
                }
                //print_r($urlRebuild);
                //getListJob
                $jobHtml = @file_get_contents($urlRebuild,0, $this->mContext);
                $html_data  = mb_convert_encoding($jobHtml , 'HTML-ENTITIES', 'UTF-8');
                @$doc->loadHTML($html_data);
                $selector = new DOMXPath($doc);
                $linkItems = $selector->query($this->linkTag);
                $titleItems = $selector->query($this->titleTag);
                $companyItems = $selector->query($this->companyTag);
                $locationItems = $selector->query($this->locationTag);
                $salaryItems = $selector->query($this->salaryTag);
                $i = 0;
                foreach ($linkItems as $link) {
                    $linkItem = 'https://vieclam24h.vn' . $link->getAttribute('href');
                    $titleItem = $titleItems[$i]->getAttribute('title');
                    $companyItem = $companyItems[$i]->nodeValue;
                    $locationItem = $locationItems[$i]->nodeValue;
                    $salaryItem = $salaryItems[$i * 3]->nodeValue;
                    $i++;
                    //build array to insert to db
                    $arr = array();
                    $arr[JOB_NAME_COLUMN] = $titleItem;
                    $arr[JOB_LINK_COLUMN] = $linkItem;
                    $arr[JOB_LOCATION_COLUMN] = $locationItem;
                    $arr[JOB_COMPANY_COLUMN] = $companyItem;
                    $arr[JOB_TYPE_COLUMN] = $title;
                    $arr[JOB_SALARY_COLUMN] = trim(preg_replace('/\s+/', ' ', $salaryItem));
                    $arr[JOB_SOURCE_TYPE] = $this->sourceId;
                    $this->dbHelper->insert(TABLE_DB, $arr);
                }
                break;
            }
        }
    }
}
