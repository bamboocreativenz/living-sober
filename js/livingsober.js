jQuery(document).ready(function() {
  $ = jQuery;
	
	/**
	* Landing Page video function
	var tag = document.createElement('script');
	tag.src = "https://www.youtube.com/iframe_api";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	var player;
	function onYouTubeIframeAPIReady() {
		player = new window.YT.Player('player', {
		'origin': 'https://livingsober.org.nz',
		'width': '560',
		'height': '315',
		'enablejsapi': 1,
		'autoplay': 0,
		'loop': 1,
		'controls': 0,
		'showinfo': 0,
		'modestbranding': 1,
		'videoId': 'ij1ckKdJKoc',
		'events': {
			'onReady': onPlayerReady,
			'onStateChange': onPlayerStateChange
		 }
	});
	}

	var p = document.getElementById ("player");
	$(p).hide();

	function onPlayerStateChange(event) {
	if(event.data === 0) {          
	  $("#overlay-container").fadeIn(800);
	  $("#player").fadeOut(400);
	  player.pauseVideo();
	}
	if (event.data == YT.PlayerState.ENDED) {
	  $('.start-video').fadeIn('normal');
	}
	}

	function onPlayerReady(event) {
	  $(document).on('click', '.start-video', function () {
		player.playVideo();
		$("#player").fadeIn();
		$("#overlay-container").fadeOut(1200);
	  });
	}
*/
  
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
 	* DL Overide the url of the notification icon
    */
	// Define the string to look for and remove
	var slug = 'BP_NOTIFICATIONS_SLUG';
	
	// Define the string to append
	var appendStr = '/notifications';
	
	// Select all a tags
	var aTags = document.querySelectorAll('a');
	
	// Iterate over all a tags
	for (var i = 0; i < aTags.length; i++) {
	    // If the href attribute contains the slug
	    if (aTags[i].href.includes(slug)) {
	        // Replace the slug with an empty string and append the new string
	        aTags[i].href = aTags[i].href.replace(slug, '') + appendStr;
	    }
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

	let dateToday = new Date();
    let datePicker = $("input#daysSoberDatepicker");
	let daysSoberMoney = document.getElementById('daysSoberMoney');
	let daysSoberForm = document.getElementById('daysSoberPre');
	let daysSoberResult = document.getElementById('daysSoberResult');
	let moneyResult = document.getElementById('moneyResult');
	let recalcButton = document.getElementById('recalcButton');
	
	let cookieSettings = { path: '/', expires: 365 * 10 };

	// Set up the datepicker field.
    datePicker.datepicker({
        maxDate: dateToday,
        dateFormat: "dd/mm/yy"
    });
	
	if (document.cookie.includes('daysSinceCookie')) {
	  daysSoberForm.style.display = 'none';
	  let daysSince = daydiff(parseDate(getCookie('daysSinceCookie')), dateToday);
	  daysSoberResult.querySelector('.result').textContent = daysSince + ' days';
	  datePicker.value = getCookie('daysSinceCookie');
	  daysSoberResult.style.display = 'block';
	
	  if (document.cookie.includes('moneySavedCookie') && getCookie('moneySavedCookie') > 0) {
	    let moneySaved = getCookie('moneySavedCookie') / 7 * daysSince;
	    moneyResult.querySelector('.savings').textContent = '$' + moneySaved.toFixed(2);
	    document.querySelector('.daysSoberAction').style.display = 'none';
	    daysSoberMoney.value = getCookie('moneySavedCookie');
	  } else {
	    document.querySelector('.saved').style.display = 'none';
	  }
	}
	
	document.getElementById('daysSoberForm').addEventListener('submit', function(e) {
	  e.preventDefault();
	  daysSoberResult.querySelector('.result').textContent = '';
	  moneyResult.querySelector('.savings').textContent = '';
	
	  if (datePicker.value === '') {
	    return false;
	  }
	
	  let daysSince = daydiff(parseDate(datePicker.value), dateToday);
	  let moneySaved = daysSoberMoney.value / 7 * daysSince;
	  if (daysSince === 0) {
	    moneySaved = daysSoberMoney.value / 7;
	  }
	
	  daysSoberForm.style.display = 'none';
	  daysSoberResult.querySelector('.result').textContent = daysSince + ' days';
	
	  if (moneySaved) {
	    moneyResult.querySelector('.savings').textContent = '$' + moneySaved.toFixed(2);
	    document.querySelector('.saved').style.display = 'block';
	    document.querySelector('.daysSoberAction').style.display = 'none';
	  } else {
	    document.querySelector('.saved').style.display = 'none';
	    document.querySelector('.daysSoberAction').style.display = 'flex';
	  }
	
	  daysSoberResult.style.display = 'block';
	  recalcButton.style.display = 'block';
	
	  document.cookie = `daysSinceCookie=${datePicker.value}; ${serializeCookieSettings(cookieSettings)}`;
	  document.cookie = `moneySavedCookie=${daysSoberMoney.value}; ${serializeCookieSettings(cookieSettings)}`;
	});
	
	document.querySelector('a.recalc').addEventListener('click', function(e) {
	  e.preventDefault();
	  recalcButton.style.display = 'none';
	  document.querySelector('.daysSoberAction').style.display = 'flex';
	  daysSoberForm.style.display = 'block';
	  datePicker.value = '';
	  daysSoberMoney.value = '';
	});
	
	document.getElementById('cancel').addEventListener('click', function(e) {
	  e.preventDefault();
	  daysSoberForm.style.display = 'none';
	  document.querySelector('.daysSoberAction').style.display = 'none';
	  recalcButton.style.display = 'block';
	});
	
	function getCookie(name) {
	  let matches = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()[]\\\/+^])/g, '\\$1') + '=([^;]*)'));
	  return matches ? decodeURIComponent(matches[1]) : undefined;
	}
	
	function serializeCookieSettings(settings) {
	  return Object.entries(settings).map(([key, value]) => `${key}=${value}`).join('; ');
	}
	
	function parseDate(dateString) {
	  let [day, month, year] = dateString.split('/');
	  return new Date(year, month - 1, day);
	}
	
	function daydiff(first, second) {
	  return Math.round((second - first) / (1000 * 60 * 60 * 24));
	}


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
