jQuery(document).ready(function() {
  $ = jQuery;
  
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
    daysSoberMoney = $("input#daysSoberMoney");
    daysSoberForm = $("#daysSoberPre");
    daysSoberResult = $("#daysSoberResult");
    
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
        $(".result", daysSoberResult).text(daysSince);

        datePicker.val($.cookie("daysSinceCookie"));
        
        daysSoberResult.show();

        if (typeof $.cookie("moneySavedCookie") !== "undefined" && $.cookie("moneySavedCookie") > 0) {
            var moneySaved = $.cookie("moneySavedCookie") / 7 * daysSince;
            $(".savings", daysSoberResult).text('$' + moneySaved.toFixed(2));
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
            $(".result", daysSoberResult).text(daysSince);

            if(moneySaved) {
                $(".savings", daysSoberResult).text('$' + moneySaved.toFixed(2));
                
                // $(".saved").css('display', 'block');
                $(".saved").fadeIn(300);
                $(".daysSoberAction").css('display', 'none');
            } else {
                $(".saved").css('display', 'none');
                $(".daysSoberAction").css('display', 'block');
            }

            daysSoberResult.fadeIn(300);
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
        daysSoberResult.fadeOut(300, function() {
            $(".daysSoberAction").css('display', 'block');
            daysSoberForm.fadeIn(300);
            $(".result", daysSoberResult).text("");
            // datePicker.val("");
            // daysSoberMoney.val("");
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