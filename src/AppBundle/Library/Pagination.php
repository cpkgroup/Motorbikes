<?php
/**
 * Pagination class
 *
 * Pagination
 *
 * @author Mohammad Habibi <habibi.mh at gmail.com>
 */
namespace AppBundle\Library;

class Pagination {
	private $total;
	private $pageCount;
	private $page;
	private $limit;
	private $pageRange;
	private $pageParameterName;
	private $template;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Controller\Controller;
     */
	private $controller;
	
    public function __construct(
		$controller , $page, $total, $limit = 5 , $pageRange = 9 ,
		$pageParameterName = 'page' , $template = '::pagination/twitter_bootstrap_pagination.html.twig'
	){
		$this->controller = $controller;
		$this->page = $page;
		$this->total = $total;
		$this->limit = $limit;
		$this->pageRange = $pageRange;
		$this->pageParameterName = $pageParameterName;
		$this->template = $template;
        $this->pageCount = ceil($this->total/$this->limit);

	}

    public function render($route , $query )
    {
        $viewData = $this->getPaginationData();
        $viewData['route'] = $route;
        $viewData['query'] = $query;
        return $this->controller->renderView($this->template , $viewData );
    }

    private function getPaginationData()
    {
        $pageCount = $this->pageCount;
        $current = $this->page;

        if ($pageCount < $current) {
            $this->page = $current = $pageCount;
        }

        if ($this->pageRange > $pageCount) {
            $this->pageRange = $pageCount;
        }

        $delta = ceil($this->pageRange / 2);

        if ($current - $delta > $pageCount - $this->pageRange) {
            $pages = range($pageCount - $this->pageRange + 1, $pageCount);
        } else {
            if ($current - $delta < 0) {
                $delta = $current;
            }

            $offset = $current - $delta;
            $pages = range($offset + 1, $offset + $this->pageRange);
        }

        $proximity = floor($this->pageRange / 2);

        $startPage  = $current - $proximity;
        $endPage    = $current + $proximity;

        if ($startPage < 1) {
            $endPage = min($endPage + (1 - $startPage), $pageCount);
            $startPage = 1;
        }

        if ($endPage > $pageCount) {
            $startPage = max($startPage - ($endPage - $pageCount), 1);
            $endPage = $pageCount;
        }

        $viewData = array(
            'last'              => $pageCount,
            'current'           => $current,
            'numItemsPerPage'   => $this->limit,
            'first'             => 1,
            'pageCount'         => $pageCount,
            'pageRange'         => $this->pageRange,
            'startPage'         => $startPage,
            'endPage'           => $endPage
        );

        if ($current - 1 > 0) {
            $viewData['previous'] = $current - 1;
        }

        if ($current + 1 <= $pageCount) {
            $viewData['next'] = $current + 1;
        }

        $viewData['pagesInRange'] = $pages;
        $viewData['firstPageInRange'] = min($pages);
        $viewData['lastPageInRange']  = max($pages);
        $viewData['pageParameterName']  = $this->pageParameterName;


        return $viewData;
    }
}
