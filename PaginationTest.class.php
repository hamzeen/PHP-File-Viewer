<?php
require 'Pagination.class.php';

/**
 * A unit class holding test cases for Pagination Class
 *
 * @author    Hamzeen. H. <hamzeen@gmail.com>
 */
class PaginationTests extends PHPUnit_Framework_TestCase {
    private $paginate;

    protected function setUp() {
      $this->paginate = new Pagination(10);
    }

    protected function tearDown() {
      $this->paginate = NULL;
    }

    public function testPageSize() {
      $result = $this->paginate->getPageSize();
      $this->assertEquals(10, $result);

      fwrite(STDOUT, "\n[Passed] testPageSize()");
    }

    /**
     * tests Pagination->getPage($page, $count, $filePath)
     * with 'No Results'
     */
    public function testGetPageWithNoResults() {
      // page:1 results:0 pageSize:10
      $result = $this->paginate->getPage(1, 0, "sample-log.txt");
      $this->assertInternalType('string',$result);
      $this->assertEquals("the file you're looking is empty", $result);

      fwrite(STDOUT, "\n[Passed] testGetPageWithNoResults()");
    }

    /**
     * tests Pagination->getPage($page, $count, $filePath) with
     * an invalid (negative) page number when remaining params are valid.
     */
    public function testGetPageNegativeValue() {
      // page:negative results:17 pageSize:10
      $result = $this->paginate->getPage(-1, 17, "sample-log.txt");
      $this->assertInternalType('string', $result);
      $this->assertEquals("page you requested doesn\'t exist", $result);

      fwrite(STDOUT, "\n[Passed] testGetPageNegativeValue()");
    }

    /**
     * tests Pagination->getPage($page, $count, $filePath)
     * when there is only 1 page. all the pagination links should be disabled.
     */
    public function testPaginationButtonStatesDisabled() {
      // page:1 results:3 pageSize:10
      $result = $this->paginate->getPage(0, 3, "sample-log.txt");
      $this->assertInternalType('array', $result);

      $this->assertEquals(0, $this->paginate->enableFirstPage());
      $this->assertEquals(0, $this->paginate->enablePrev());

      $this->assertEquals(0, $this->paginate->enableLastPage());
      $this->assertEquals(0, $this->paginate->enableNext());

      fwrite(STDOUT, "\n[Passed] testPaginationButtonStatesDisabled()");
    }

    /**
     * tests Pagination->getPage($page, $count, $filePath)
     * from 1st page (yet there are 2 pages to show).
     * Expecting prev & first page links should be disabled,
     * next & last page links to be enabled.
     */
    public function testPaginationNextButtonStateEnabled() {
      // page:1 results:13 pageSize:10
      $result = $this->paginate->getPage(0, 13, "sample-log.txt");
      $this->assertInternalType('array', $result);

      $this->assertEquals(0, $this->paginate->enableFirstPage());
      $this->assertEquals(0, $this->paginate->enablePrev());

      $this->assertEquals(1, $this->paginate->enableLastPage());
      $this->assertEquals(1, $this->paginate->enableNext());

      fwrite(STDOUT, "\n[Passed] testPaginationNextButtonStateEnabled()");
    }

    /**
     * tests Pagination->getPage($page, $count, $filePath)
     * from 2nd page (there exists a previous page).
     * Expecting prev & first page links should be enabled,
     * next & last page links to be disabled.
     */
    public function testPaginationPrevButtonStateEnabled() {
      // page:2 results:13 pageSize:10
      $result = $this->paginate->getPage(1, 13, "sample-log.txt");
      $this->assertInternalType('array', $result);

      $this->assertEquals(1, $this->paginate->enableFirstPage());
      $this->assertEquals(1, $this->paginate->enablePrev());

      $this->assertEquals(0, $this->paginate->enableLastPage());
      $this->assertEquals(0, $this->paginate->enableNext());

      fwrite(STDOUT, "\n[Passed] testPaginationPrevButtonStateEnabled()");
    }
}
