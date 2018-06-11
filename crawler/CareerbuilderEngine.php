<?php
/**
 * Created by PhpStorm.
 * User: anansaj
 * Date: 5/6/2018
 * Time: 2:25 AM
 */

namespace crawler;

use DOMDocument;
use DOMXPath;
use helpers\DBHelper;

class CareerbuilderEngine extends AbstractEngine
{
    public $arrLink;
    public $countLimitArray;

    public function __construct() {
        parent::__construct();
        $this->countLimitArray = array();
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
        foreach ($this->typeJobLinks as $career) {
            $title = $career['title'];
            $link = $career['link'];
            $title = trim(preg_replace('/\s+/', ' ', $title));
            $this->countLimitArray[$i] = 0;
            $this->getJobsFromOneLink($title, $link, $i);
            $i++;
        }
    }

    public function getAllJobs($title, $link, $indexOfTypeJob) {
        //check if $arr contains $link
        if (in_array($link, $this->arrLink)) {
            return;
        }

        //if number of jobs greater than limit
        if ($this->countLimitArray[$indexOfTypeJob] >= $this->limit) {
            return;
        }

        //if current is first page, ignoring it because it is crawled
        if (strpos($link, $this->firstPage) !== false) {
            return;
        }

        $this->arrLink[] = $link;
        //get all jobs of link not exists on arrLink and access to this link for get sublink in it
        $HTML = @file_get_contents($link,0, $this->mContext);
        $doc = new DOMDocument();
        @$doc->loadHTML($HTML);
        $selector = new DOMXPath($doc);

        //ignore first link of arrLink
        $linkItems = $selector->query($this->linkTag);
        $titleItems = $selector->query($this->titleTag);
        $companyItems = $selector->query($this->companyTag);
        $locationItems = $selector->query($this->locationTag);
        $salaryItems = $selector->query($this->salaryTag);
        $i = 0;

        foreach ($linkItems as $link) {
            if ($this->countLimitArray[$indexOfTypeJob] < $this->limit) {
                //if number of jobs less than limit, continuing to crawl
                $linkItem = $link->getAttribute('href');
                $titleItem = $titleItems[$i]->nodeValue;
                $companyItem = $companyItems[$i]->nodeValue;
                $locationItem = $locationItems[$i]->nodeValue;
                $salaryItem = $salaryItems[$i]->nodeValue;
                $i++;
                //build array to insert to db
                $arr = array();
                $arr[JOB_NAME_COLUMN] = $titleItem;
                $arr[JOB_LINK_COLUMN] = $linkItem;
                $arr[JOB_LOCATION_COLUMN] = $locationItem;
                $arr[JOB_COMPANY_COLUMN] = $companyItem;
                $arr[JOB_TYPE_COLUMN] = trim(preg_replace('/\s+/', ' ', $title));
                $arr[JOB_SALARY_COLUMN] = trim(preg_replace('/\s+/', ' ', $salaryItem));
                $arr[JOB_SOURCE_TYPE] = $this->sourceId;
                $this->dbHelper->insert(TABLE_DB, $arr);
                $this->countLimitArray[$indexOfTypeJob]++;
            }
            else {
                return;
            }
        }

        $result = $selector->query($this->jobPageTag);
        foreach ($result as $res) {
            $subLink = $res->getAttribute("href");
            if (strcmp($subLink, "#") != 0) {
                $this->getAllJobs($title, $subLink, $indexOfTypeJob);
            }
        }
    }

    public function getJobsFromOneLink($title, $link, $indexOfTypeJob) {
        $this->arrLink = array();
        //get all jobs of link not exists on arrLink and access to this link for get sublink in it
        $this->getAllJobs($title, $link, $indexOfTypeJob);
    }
}
