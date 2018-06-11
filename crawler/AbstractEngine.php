<?php
/**
 * Created by PhpStorm.
 * User: anansaj
 * Date: 4/30/2018
 * Time: 7:04 PM
 */
namespace crawler;

use DOMDocument;
use DOMXPath;

abstract class AbstractEngine
{

    public $sourceId;
    public $sourceName;
    //link to list type of jobs
    public $seedUrl;
    //type of job html tag
    public $typeJobTag;

    //helper using connect database
    public $dbHelper;

    public $mContext;

    //job page tag
    public $jobPageTag;
    //detail of job link html tag
    public $linkTag;
    //title of job html tag
    public $titleTag;
    //company of job html tag
    public $companyTag;
    //location of job html tag
    public $locationTag;
    //salary of job html tag
    public $salaryTag;

    //first page
    public $firstPage;

    //list type of jobs
    public $typeJobLinks;

    public $limit;

    private static $_instances = array();

    public static function getInstance() {
        $class = get_called_class();
        if (!isset(self::$_instances[$class])) {
            self::$_instances[$class] = new $class();
        }
        return self::$_instances[$class];
    }

    public function __construct()
    {
        $this->mContext = stream_context_create(
            array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
                'http' => array(
                    'header'=> "Content-Type: text/html; charset=utf-8",
                )
            )
        );
        $this->dbHelper = \helpers\DBHelper::instance();
    }

    public function setInfoEngine($info) {
        $this->sourceId = $info['source_id'];
        $this->sourceName = $info['source_name'];
        //get job type
        $this->seedUrl = $info['source_seed_url'];
        $this->typeJobTag = $this->handleTag($info['source_type_job_tag']);

        //get job item
        $this->jobPageTag = $this->handleTag($info['source_job_page_tag']);
        $this->linkTag = $this->handleTag($info['source_link_tag']);
        $this->titleTag = $this->handleTag($info['source_title_tag']);
        $this->companyTag = $this->handleTag($info['source_company_tag']);
        $this->locationTag = $this->handleTag($info['source_location_tag']);
        $this->salaryTag = $this->handleTag($info['source_salary_tag']);

        $this->firstPage = $info['source_first_page'];
    }

    public function setLimit($limit) {
        $this->limit = $limit;
    }

    public function getAllTypeJobLinks() {
        $typeJobInfo = array();
        $links = array();
        $titles = array();
        $HTML = @file_get_contents($this->seedUrl,0, $this->mContext);
        $doc = new DOMDocument();
        $html_data  = mb_convert_encoding($HTML , 'HTML-ENTITIES', 'UTF-8');
        @$doc->loadHTML($html_data);
        $selector = new DOMXPath($doc);

        $result = $selector->query($this->handleTag($this->typeJobTag));
        foreach ($result as $node) {
            $href = $node->getAttribute('href');
            $title = trim(preg_replace('/\s+/', ' ', $node->nodeValue));
            if (!filter_var($href, FILTER_VALIDATE_URL)) {
                $splitSeedUrl = explode('/', $this->seedUrl);
                $href = $splitSeedUrl[0] . '//' . $splitSeedUrl[2] . $href;
            }
            if (!in_array($href, $links)) {
                $career = array();
                $career['link'] = $href;
                $career['title'] = $this->findAndDeleteParenthesis($title);
                $typeJobInfo[] = $career;
            }
        }

        return $typeJobInfo;
    }

    public function findAndDeleteParenthesis($string) {
        $splitTypeJob = explode('(', $string);
        return $splitTypeJob[0];
    }

    public abstract function process();
    public abstract function getJobsAndInsertDb();

    protected function handleTag($typeJobTag) {
        $listTag = explode(" ", $typeJobTag);
        $strRes = "/";
        foreach ($listTag as $tag) {
            if (strpos($tag, '.') !== false) {
                //if tag has class attribute
                $tmp = explode('.', $tag);
                if (strlen($tmp[0]) == 0) {
                    $strRes = $strRes . '/*' . '[contains(@class,"' .$tmp[1]. '")]';
                }
                else {
                    $strRes = $strRes . '/' . $tmp[0] . '[contains(@class,"' .$tmp[1]. '")]';
                }

            }
            else if (strpos($tag, '#') !== false) {
                //if tag has id attribute
                $tmp = explode('#', $tag);
                if (strlen($tmp[0]) == 0) {
                    $strRes = $strRes . '/*' . '[contains(@id,"' .$tmp[1]. '")]';
                }
                else {
                    $strRes = $strRes . '/' . $tmp[0] . '[contains(@id,"' .$tmp[1]. '")]';
                }
            }
            else {
                if (strlen($tag) > 0) {
                    $strRes = $strRes . '/' . $tag;
                }
                else {
                    $strRes .= '';
                }
            }
        }
        return $strRes;
    }

    public function getSeedUrl() {
        return $this->seedUrl;
    }

}
