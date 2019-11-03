jQuery(document).ready(function() {
  $ = jQuery;
	
  /**
   * Landing Page video function
   */
	var tag = document.createElement('script');
  tag.src = "https://www.youtube.com/iframe_api";
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
  var player;
  onYouTubeIframeAPIReady = function () {
    player = new window.YT.Player('player', {
      playerVars: {
        'origin': 'https://livingsober.org.nz',
        'autoplay': 0,
        'loop': 1,
        'controls': 0,
        'showinfo': 0,
        'modestbranding': 1
     },
      events: {
        'onReady': onPlayerReady,
        'onStateChange': onPlayerStateChange
      }
    });
  }
  
  var p = document.getElementById ("player");
  $(p).hide();
  
  onPlayerStateChange = function (event) {
    if(event.data === 0) {          
      $("#overlay-container").fadeIn(800);
      $("#player").fadeOut(400);
      player.pauseVideo();
    }
    if (event.data == YT.PlayerState.ENDED) {
      $('.start-video').fadeIn('normal');
    }
  }

  onPlayerReady = function (event) {
	  $(document).on('click', '.start-video', function () {
		player.playVideo();
		$("#player").fadeIn();
		$("#overlay-container").fadeOut(1200);
	  });
  }
  
  // Do not allow spaces on the username field on the register page.
  jQuery.validator.addMethod("noSpace", function(value, element) { 
      return value.indexOf(" ") < 0 ; 
  }, '<div class="error">No space please</div>');
  
  $("form#signup_form").validate({
      rules: {
          signup_username: {
              noSpace: true
          }
      }
  });

  /**
   * On the profile page there is a hidden field used to set the user name
   */
  if ($("#profile-edit-form").length > 0) {
    // This timeout seems required as the field gets cleared otherwise.
    setTimeout(function() {
        $("input#field_1").val($("#item-header-content h1").text());
    }, 250);
  }

  /**
   * On page load, and on key press inside the username field on the registration
   * page we want to ensure the profile name field (which is hidden) is copied
   * from the username field.
   * 
   * @returns <void>
   */
  function copyUserNameToProfileName() {
      $("#field_1").val($("#signup_username").val());
      return;
  }
  copyUserNameToProfileName();
  $("#signup_username").keyup(copyUserNameToProfileName);

  ////////////////////////////////////////////////////////////////////////////
    /**
     * Living Sober Calculator
     */

    dateToday = new Date();
    datePicker = $("input#daysSoberDatepicker");
    daysSoberMoney = $("#daysSoberMoney");
    daysSoberForm = $("#daysSoberPre");
    daysSoberResult = $("#daysSoberResult");
    moneyResult = $("#moneyResult");
    recalcButton = $("#recalcButton")
    
    // Cookie settings are here as they need to be the same for create/deleting of the cookie
    cookieSettings = { path: '/', expires: 365 * 10 };
    
    // Set up the datepicker field.
    datePicker.datepicker({
        maxDate: dateToday,
        dateFormat: "dd/mm/yy"
    });
    
    
    // On page load, if there is a session length cookie for the last drink, show it
    if (typeof $.cookie("daysSinceCookie") !== "undefined") {
        daysSoberForm.hide();
        var daysSince = daydiff(parseDate($.cookie("daysSinceCookie")), dateToday);
        $(".result", daysSoberResult).text(daysSince + ' days');

        datePicker.val($.cookie("draysSinceCookie"));
        
        daysSoberResult.show();

        if (typeof $.cookie("moneySavedCookie") !== "undefined" && $.cookie("moneySavedCookie") > 0) {
            var moneySaved = $.cookie("moneySavedCookie") / 7 * daysSince;
            $(".savings", moneyResult).text('$' + moneySaved.toFixed(2));
            $(".daysSoberAction").css('display', 'none');
            
            daysSoberMoney.val($.cookie("moneySavedCookie"));
        } else {
            $(".saved").css('display', 'none');
        }

    }
    

    // Say sober calculator form
    $('#daysSoberForm').submit(function(e) {
        e.preventDefault();
        
        // Clear the previous result (if there is one)
        $(".result", daysSoberResult).text("");
        $(".savings", moneyResult).text("");

        // No date entered. Show an error
        if (datePicker.val() === "") {
            return false;
        }

        // Calculate the number of days since the users last drink
        var daysSince = daydiff(parseDate(datePicker.val()), dateToday);
        var moneySaved = daysSoberMoney.val() / 7 * daysSince;
        if(daysSince == 0) {
            moneySaved = daysSoberMoney.val() / 7;
        }
        
        // Update and display the days sober result
        daysSoberForm.fadeOut(300, function() {
            $(".result", daysSoberResult).text(daysSince + ' days');

            if(moneySaved) {
                $(".savings", moneyResult).text('$' + moneySaved.toFixed(2));
                
                // $(".saved").css('display', 'block');
                $(".saved").fadeIn(300);
                $(".daysSoberAction").css('display', 'none');
            } else {
                $(".saved").css('display', 'none');
                $(".daysSoberAction").css('display', 'flex');
            }

            daysSoberResult.fadeIn(300);
            recalcButton.fadeIn(300);
        });
        
        // Store the result in a session length cookie
        $.cookie("daysSinceCookie", datePicker.val(), cookieSettings);
        $.cookie("moneySavedCookie", daysSoberMoney.val(), cookieSettings);
        
    });


    /**
     * Clear the calculator result and start again.
     */
    $("a.recalc").click(function(e) {
        e.preventDefault();
        
        // Clear the result cookie
        // $.removeCookie("daysSinceCookie", cookieSettings);
        // $.removeCookie("moneySavedCookie", cookieSettings);
        
        // Clear any user entered or calculated text
        recalcButton.fadeOut(300, function() {
            $(".daysSoberAction").css('display', 'flex');
            daysSoberForm.fadeIn(300);
            //$(".result", daysSoberResult).text("");
            //$(".savings", moneyResult).text("");
            datePicker.val("");
            daysSoberMoney.val("");
        });
    }); 
    $("#cancel").click(function(e) {
        e.preventDefault();
        daysSoberForm.fadeOut(300, function() {
            $(".daysSoberAction").css('display', 'none');
            recalcButton.fadeIn(300);
        });
    });
});

/**
 * Create a new date object from the datepicker value.
 * @param <string> dateString
 * @returns <Date>
 */
function parseDate(dateString) {
  var mdy = dateString.split('/');
  // -1 on the month as months start at 0 in JS
  return new Date(mdy[2], mdy[1] - 1, mdy[0]);
}

function daydiff(first, second) {
  // Round to the nearest whole day.
  var days = Math.floor((second - first) / (1000 * 60 * 60 * 24));
  return days;
} 
