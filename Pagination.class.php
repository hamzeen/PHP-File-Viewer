<?php
/**
 * Pagination Class
 *
 * It fetches the content required to render a page from local file system.
 * Size of a page (i.e number of records for a page) could be set via its constructor.
 *
 * @author    Hamzeen. H. <hamzeen@gmail.com>
 */
class Pagination {
  var $page;
  var $pageSize;
  var $totalPages;
  var $results;

  /**
   * constructor of this class
   */
  public function __construct($pageSize) {
    $this->page = 0;
    $this->pageSize = $pageSize;
  }

  /**
   * returns the content to render a given page.
   * It only reads the lines of interest to render the requested page.
   *
   * @param int $page from Zero to (n-1) for n pages.
   * @param int $totalResults
   * @param string filePath
   * @return string when there is an error or invlaid request
   * @return array when the requested page has result(s)
   */
  function getPage($page, $totalResults, $filePath) {
    $this->page = intval($page);
    $this->results = $totalResults;
    $this->totalPages = (int) ($this->results/$this->pageSize);

    if($this->results <= 0) { // client has request to view an empty file
      return "the file you're looking is empty";
    }

    if($this->page < 0 || $this->page > $this->totalPages) {
      return "page you requested doesn\'t exist";
    } else {
          $start = $this->page * $this->pageSize;
          $end = (($start + $this->pageSize) > $this->results) ? $this->results : ($start + $this->pageSize);

          $ary_result = array();
          $file = new SplFileObject($filePath);
        	for($i = $start; $i < $end; $i++) {
            $file->seek($i);     // Seek to line no. i+1
            $ary_result [$i]["lineNo"] = $i+1;
            $ary_result [$i]["msg"] = $file->current();
        	}
        	return $ary_result;  // returns the logs
      }
  }

  /**
   * returns number of results for a page
   * @return int results per pages
   */
  function getPageSize() {
    return $this->pageSize;
  }

  /**
   * returns total number of pages
   * @return int total pages
   */
  function getTotalPages() {
    return $this->totalPages;
  }

  /**
   * returns the current page index
   * @return int page index
   */
  function getCurrentPage() {
    return $this->page;
  }

  /**
   * returns whether next page link can be enabled
   */
  function enableNext() {
    return ($this->page < $this->totalPages) ? 1 : 0;
  }

  /**
   * returns whether previous page link can be enabled
   */
  function enablePrev() {
    return ($this->page > 0) ? 1 : 0;
  }

  /**
   * returns whether last page links can be enabled
   */
  function enableLastPage() {
    return ($this->page < $this->totalPages) ? 1 : 0;
  }

  /**
   * returns whether first page links can be enabled
   */
  function enableFirstPage() {
    return ($this->page > 0) ? 1 : 0;
  }
}
?>
