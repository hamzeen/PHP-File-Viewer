<?php
include('Pagination.class.php'); // includes Pagination class
$pagination = new Pagination(10); // pagination instance

// default error message to respond when filePath & page params (mandatory) aren't set
$pageResult = "invalid request, 'filepath' & 'page' params are mandatory";
if( (isset($_GET['filePath'])) && (isset($_GET['page'])) ) {
  $filePath = $_GET['filePath'];

  if(!file_exists($filePath)) {
    $pageResult = "This file doesn't exist, please check the file path";
  } else {
    // reads the total number of lines in the file
    $totalLines = intval(exec("wc -l '$filePath'"));
    $pageResult = $pagination->getPage($_GET['page'], $totalLines, $filePath);
  }
}

// $pageResult is a string if there's any error, refer contract of Pagination->getPage() method
if(is_string($pageResult)) {
  $response->error = $pageResult;
  echo json_encode($response);
} else {

  // constructs the object with results & pagination link states
  $response->data = $pageResult;
  $response->pageSize = $pagination->getPageSize();
  $response->currentPage = $pagination->getCurrentPage();
  $response->enablePrev = $pagination->enablePrev();
  $response->enableNext = $pagination->enableNext();
  $response->totalPages = $pagination->getTotalPages();

  // json object which the front-end will receive upon a successful request.
  echo json_encode($response);
}
?>
