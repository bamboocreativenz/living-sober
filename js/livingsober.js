jQuery(document).ready(function () {
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
	jQuery.validator.addMethod(
		"noSpace",
		function (value, element) {
			return value.indexOf(" ") < 0;
		},
		'<div class="error">No space please</div>'
	);

	$("form#signup_form").validate({
		rules: {
			signup_username: {
				noSpace: true,
			},
		},
	});

	/**
	 * On the profile page there is a hidden field used to set the user name
	 */
	if ($("#profile-edit-form").length > 0) {
		// This timeout seems required as the field gets cleared otherwise.
		setTimeout(function () {
			$("input#field_1").val($("#item-header-content h1").text());
		}, 250);
	}

	/**
	 * DL Overide the url of the notification icon
	 */
	// Define the string to look for and remove
	var slug = "BP_NOTIFICATIONS_SLUG";

	// Define the string to append
	var appendStr = "/notifications";

	// Select all a tags
	var aTags = document.querySelectorAll("a");

	// Iterate over all a tags
	for (var i = 0; i < aTags.length; i++) {
		// If the href attribute contains the slug
		if (aTags[i].href.includes(slug)) {
			// Replace the slug with an empty string and append the new string
			aTags[i].href = aTags[i].href.replace(slug, "") + appendStr;
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

	dateToday = new Date();
	datePicker = $("input#daysSoberDatepicker");
	daysSoberMoney = $("#daysSoberMoney");
	daysSoberForm = $("#daysSoberPre");
	daysSoberResult = $("#daysSoberResult");
	moneyResult = $("#moneyResult");
	recalcButton = $("#recalcButton");

	// Cookie settings are here as they need to be the same for create/deleting of the cookie
	cookieSettings = { path: "/", expires: 365 * 10 };

	// Set up the datepicker field.
	datePicker.datepicker({
		maxDate: dateToday,
		dateFormat: "dd/mm/yy",
	});

	// On page load, if there is a session length cookie for the last drink, show it
	if (
		document.cookie.includes("daysSinceCookie") &&
		getCookie("daysSinceCookie") !== "undefined"
	) {
		daysSoberForm.hide();
		let daysSince = daydiff(
			parseDate(getCookie("daysSinceCookie")),
			dateToday
		);
		$(".result", daysSoberResult).text(daysSince + " days");
		datePicker.val(getCookie("daysSinceCookie"));
		daysSoberResult.show();

		if (
			document.cookie.includes("moneySavedCookie") &&
			getCookie("moneySavedCookie") > 0
		) {
			let moneySaved = (getCookie("moneySavedCookie") / 7) * daysSince;
			$(".savings", moneyResult).text("$" + moneySaved.toFixed(2));
			$(".daysSoberAction").css("display", "none");
			daysSoberMoney.val(getCookie("moneySavedCookie"));
		} else {
			$(".saved").css("display", "none");
		}
	}

	// DL OLD Jquery way
	// if (typeof $.cookie("daysSinceCookie") !== "undefined") {
	// 	daysSoberForm.hide();
	// 	var daysSince = daydiff(
	// 		parseDate($.cookie("daysSinceCookie")),
	// 		dateToday
	// 	);
	// 	$(".result", daysSoberResult).text(daysSince + " days");

	// 	datePicker.val($.cookie("draysSinceCookie"));

	// 	daysSoberResult.show();

	// 	if (
	// 		typeof $.cookie("moneySavedCookie") !== "undefined" &&
	// 		$.cookie("moneySavedCookie") > 0
	// 	) {
	// 		var moneySaved = ($.cookie("moneySavedCookie") / 7) * daysSince;
	// 		$(".savings", moneyResult).text("$" + moneySaved.toFixed(2));
	// 		$(".daysSoberAction").css("display", "none");

	// 		daysSoberMoney.val($.cookie("moneySavedCookie"));
	// 	} else {
	// 		$(".saved").css("display", "none");
	// 	}
	// }

	// Say sober calculator form
	$("#daysSoberForm").submit(function (e) {
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
		var moneySaved = (daysSoberMoney.val() / 7) * daysSince;
		if (daysSince == 0) {
			moneySaved = daysSoberMoney.val() / 7;
		}

		// Update and display the days sober result
		daysSoberForm.fadeOut(300, function () {
			$(".result", daysSoberResult).text(daysSince + " days");

			if (moneySaved) {
				$(".savings", moneyResult).text("$" + moneySaved.toFixed(2));

				// $(".saved").css('display', 'block');
				$(".saved").fadeIn(300);
				$(".daysSoberAction").css("display", "none");
			} else {
				$(".saved").css("display", "none");
				$(".daysSoberAction").css("display", "flex");
			}

			daysSoberResult.fadeIn(300);
			recalcButton.fadeIn(300);
		});

		// Store the result in a session length cookie
		// DL NEW Way
		document.cookie = `daysSinceCookie=${
			datePicker.val()
		}; ${serializeCookieSettings(cookieSettings)}`;
		document.cookie = `moneySavedCookie=${
			daysSoberMoney.val()
		}; ${serializeCookieSettings(cookieSettings)}`;

		// DL OLD Way with jquery
		// $.cookie("daysSinceCookie", datePicker.val(), cookieSettings);
		// $.cookie("moneySavedCookie", daysSoberMoney.val(), cookieSettings);
	});

	/**
	 * Clear the calculator result and start again.
	 */
	$("a.recalc").click(function (e) {
		e.preventDefault();

		// Clear the result cookie
		// $.removeCookie("daysSinceCookie", cookieSettings);
		// $.removeCookie("moneySavedCookie", cookieSettings);

		// Clear any user entered or calculated text
		recalcButton.fadeOut(300, function () {
			$(".daysSoberAction").css("display", "flex");
			daysSoberForm.fadeIn(300);
			//$(".result", daysSoberResult).text("");
			//$(".savings", moneyResult).text("");
			datePicker.val("");
			daysSoberMoney.val("");
		});
	});
	$("#cancel").click(function (e) {
		e.preventDefault();
		daysSoberForm.fadeOut(300, function () {
			$(".daysSoberAction").css("display", "none");
			recalcButton.fadeIn(300);
		});
	});
});

function getCookie(name) {
	let matches = document.cookie.match(
		new RegExp(
			"(?:^|; )" +
				name.replace(/([.$?*|{}()[]\\\/+^])/g, "\\$1") +
				"=([^;]*)"
		)
	);
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

function serializeCookieSettings(settings) {
	return Object.entries(settings)
		.map(([key, value]) => `${key}=${value}`)
		.join("; ");
}

/**
 * Create a new date object from the datepicker value.
 * @param <string> dateString
 * @returns <Date>
 */
function parseDate(dateString) {
	var mdy = dateString.split("/");
	// -1 on the month as months start at 0 in JS
	return new Date(mdy[2], mdy[1] - 1, mdy[0]);
}

function daydiff(first, second) {
	// Round to the nearest whole day.
	var days = Math.floor((second - first) / (1000 * 60 * 60 * 24));
	return days;
}
