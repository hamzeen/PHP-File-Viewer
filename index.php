<html>
<head>
  <meta charset="utf-8" />
  <!-- load stylesheets, bootstraps' grid layout is used for a responsive table/page //-->
  <link rel="stylesheet" media="screen" href="assets/bootstrap3.min.css">
  <link rel="stylesheet" media="screen" href="assets/styles.css">

  <!-- JS libs & helper methods //-->
  <script src="assets/jquery-2.1.1.js"></script>
  <script src="assets/helpers.js"></script>
  <script>
  $(document).ready(function() { // DOM is ready
    notify("please enter logfile path to begin", "success", 1000); // notify user to enter filepath
    var filePath;

    $('#btn-view').on( "click", function() {
      filePath = $("#id-filepath").val().trim();
      if(filePath.length > 4) { // only a simple validation is done here for filepath
        getPages('getResults.php?filePath=' + filePath + '&page=0');
      } else {
        notify("please enter a valid file name", "success", 1000);
      }
    });

    $('#list-paginate li').click(function() {
      if(!$(this).hasClass("disabled")) {
        // In case, filepath was changed after pressing 'View' button, just before clicking on a pagination link.
        filePath = $("#id-filepath").val().trim();
        var page = $(this).attr('page');
        getPages('getResults.php?filePath='+filePath + '&page='+page);
      }
    });
  });

  /**
   * calls the webservice and builds the table and pagination links
   * without refreshing the page.
   */
  function getPages(url) {
  	$.ajax({
  		url: url, type: "GET",//data: { page:2 },
      beforeSend: function() {
        $(".preload").show();
      },

      success: function(data) {
        var response = JSON.parse(data);

        if(response.error) { // shows error notification with message received from backend
          $(".preload").hide();
          notify(response.error, "error", 3000);
        }

        if(response.data) {
          // debug: console.log(data);
          var size = Object.keys(response.data).length;
          if(size > 0) {
            $("#tbody-logs").empty();
            for(var i=0; i < size; i++) {
              var objLog = response.data[Object.keys(response.data)[i]];
              var row = $("<tr><td>" + objLog.lineNo + "</td>" +
              "<td>" + objLog.msg + "</td></tr>");
              $("#tbody-logs").append(row);
            }

            // now setup the pagination links
            setButtonProps(response.enablePrev, response.enableNext, response.currentPage, response.totalPages);
          }
    		  setInterval(function() {$(".preload").hide(); },6000);
        } else {
          setInterval(function() {$(".preload").hide(); },100);
          notify("invalid params were sent to the service", "error", 3000);
        }
  		},

  		error: function() {
        $(".preload").hide();
        notify("fatal error, check if you've provided correct file path", "error", 3000);
      }
     });
  }
  </script>
</head>

<body>
  <div id="toast_cont">
      <div id="toast_content"></div>
  </div>
  <div class="preload">LOADING</div>

  <h2 id="heading">PHP Async File Viewer</h2>

  <div class="container">
  <div class="search-toolbar">
      <button id="btn-view" class="btn btn-danger">View</button>

      <div id="input-filepath" class="pull-left search">
        <input id="id-filepath" class="form-control" type="text" placeholder="Search">
      </div>
  </div>

    <div class="row">
      <div class="col-xs-12">
        <div class="table-responsive">
          <table summary="a minimalist table to view log messages on the server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Line</th>
                <th>Log</th>
              </tr>
            </thead>
            <tbody id="tbody-logs">
            </tbody>
          </table>
        </div><!--end of .table-responsive-->
      </div>
    </div>

    <div class="pull-right pagination-toolbar">
      <ul id="list-paginate" class="pagination">
        <li class="disabled"><a href = "#">|&#60;</a></li>
        <li class="disabled"><a href = "#">&#60;</a></li>
        <li class="disabled"><a href = "#">&#62;</a></li>
        <li class="disabled"><a href = "#">&#62;|</a></li>
      </ul>
    </div>
  </div>

  <p class="p-center">
    a demo by <a href="http://hamzeen.github.io" target="_blank">hamzeen. h.</a></p>
</body>
</html>
