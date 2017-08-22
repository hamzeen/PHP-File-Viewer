 /**
  * sets up pagination link states.
  *
  * @param {boolean} isPrevAllowed enable previous & first page buttons
  * @param {boolean} isNextAllowed enable next & last page buttons
  * @param {number} currentPage the currentPage index (valid: 0 to n-1 for n pages)
  * @param {number} ttlPages totalPages (valid: n-1 for n pages)
  */
  function setButtonProps(isPrevAllowed, isNextAllowed, currentPage, ttlPages) {
    if(isPrevAllowed) {
      $("#list-paginate li:nth-child(1)").removeClass("disabled");
      $("#list-paginate li:nth-child(2)").removeClass("disabled");

      $("#list-paginate li:nth-child(1)").attr("page", 0);
      $("#list-paginate li:nth-child(2)").attr("page", currentPage - 1);
    } else {
      $("#list-paginate li:nth-child(1)").addClass("disabled");
      $("#list-paginate li:nth-child(2)").addClass("disabled");
    }

    if(isNextAllowed) {
      $("#list-paginate li:nth-child(3)").removeClass("disabled");
      $("#list-paginate li:nth-child(4)").removeClass("disabled");

      $("#list-paginate li:nth-child(3)").attr("page", currentPage + 1);
      $("#list-paginate li:nth-child(4)").attr("page", ttlPages);
    } else {
      $("#list-paginate li:nth-child(3)").addClass("disabled");
      $("#list-paginate li:nth-child(4)").addClass("disabled");
    }
  }

  /**
   * shows a notification bubble for a given period of time.
   *
   * @param {string} msg message to be shown
   * @param {strnig} type type of message (ex: error, warning or success)
   * @param {number} duration the duration to show the notification
   */
  function notify(msg, type, duration) {
    // get hold of the elements
    var toast = $("#toast_cont");
    var content = $("#toast_content");

    if((toast.attr('class') == null) || toast.attr('class').indexOf('visible') < 0) {
      content.html(msg); // set the notification message

      if(type!=null) {
        content.removeAttr('class');
        content.addClass(type);
      }

      toast.addClass("visible"); // show
      setTimeout(function() { // hide after the timeout
        toast.removeClass("visible");
        if(type!=null)
          toast.removeClass(type);
      }, duration);
    }
  }

  /**
   * helper function to hide the tabl & pagination links.
   * @deprecated Never used in the app.
   */
  function hideTable() {
    $(".pagination-toolbar").hide();
    $(".row").hide();
  }
