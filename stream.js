
jQuery(document).ready(function($) {

	jQuery("#stream_dropdownbox").change(function() {
	  var stream = jQuery("#stream_dropdownbox option:selected").val();
	  var postid = jQuery("#post_ID").val();

	  jQuery.ajax({
		url: "http://www.knightlab.dhrumilmehta.com/wp-content/plugins/tapestry2/al.php?postid=" + postid + "&stream=" + stream
	  }).done(function( resp ) {

	  	var stories = JSON.parse(resp);

	  	var timeliner = "";
		timeliner += "<div id='timelineContainer'>";
			// timeliner += "<div class='timelineToggle'><p><a class='expandAll'>+ expand all</a></p></div>";
			timeliner += "<div class='timelineMajor'>";
				timeliner += "<h2 class='timelineMajorMarker'>" + stream + "</h2>";

				for (var i = 0; i < stories.length; i++) {
					if (stories[i][1] == postid) {

						var post_title = stories[i][0]; post_title = (post_title == "Auto Draft") ? post_title = "New Post" : post_title;
						var date = new Date(stories[i][2]);
						var displayDate = date.toLocaleDateString() + " " + date.toLocaleTimeString();

						var headline = stories[i][4]; // headline = (headline != "") ? headline : post_title;
						var summary = stories[i][3];
						var postdate = new Date(stories[i][6]);
    					postdate = postdate.getFullYear() + "-" + ('0' + (postdate.getMonth()+1)).slice(-2) + "-" + ('0' + postdate.getDate()).slice(-2) + "T" + ('0' + postdate.getHours()).slice(-2) + ":" + ('0' + postdate.getMinutes()).slice(-2);
						var altdate = stories[i][2]; altdate = (altdate != "") ? altdate : postdate;
						var priority_val = stories[i][5]; priority_val = (priority_val != "") ? priority_val : 5;
						var checked_low = (priority_val == '1') ? 'checked' : '';
						var checked_high= (priority_val == '5') ? 'checked' : '';

						timeliner += "<dl class='timelineMinor'>";
							timeliner += "<dt id='event" + i + "'><a>" + post_title + " </a><a class='edit'>Edit</a></dt>";
							timeliner += "<dd class='timelineEvent' id='event" + i + "EX' style='display: none; '>";

								var current_post = "";
								current_post += "<label for='tapestry_headline'> Headline for post in stream </label>";
								current_post += "<br>";
								current_post += "<input class='' type='text' name='tapestry_headline' placeholder='Enter headline here' style='width: 340px;' id='tapestry_headline' value='" + headline +"'/>";
								current_post += "<br>";
								current_post += "<label for='tapestry_summary'> Summary for post in stream </label>";
								current_post += "<br>";
								current_post += "<textarea class='' name='tapestry_summary' placeholder='Enter summary here' id='tapestry_summary' rows='4' cols='50'>";
								current_post += summary;
								current_post += "</textarea>";
								current_post += "<br>";
								current_post += "<label for='tapestry_altdate'> Alternative Date for post in stream </label>";
								current_post += "<br>";
								current_post += "<input class='' type='datetime-local' name='tapestry_altdate' id='tapestry_altdate' value='" + altdate + "' />";
								current_post += "<br>";
								current_post += "<label for='tapestry_priority'> Priority high or low.</label>";
								current_post += "<br>";
								current_post += "<input type='radio' id='tapestry_priority_low' name='priority' value='1'" + checked_low  + "/>Low";
								current_post += "<br>";
								current_post += "<input type='radio' id='tapestry_priority_high' name='priority' value='5'" + checked_high + "/>High";

								timeliner+= current_post;
							timeliner += "</dd>";
						timeliner += "</dl>";

					}
					else {

						timeliner += "<dl class='timelineMinor'>";
							timeliner += "<dt id='event" + i + "'><a>" + stories[i][0] + "</a></dt>";
							timeliner += "<dd class='timelineEvent' id='event" + i + "EX' style='display: none; '>";
								var date = new Date(stories[i][2]);
								timeliner += "<p><b>Date: </b>" + date.toLocaleDateString() + "</p>";
								timeliner += "<p><b>Time: </b>" + date.toLocaleTimeString() + "</p>";
								timeliner += "<p><b>Headline: </b>" + stories[i][4] + "</p>";								
								timeliner += "<p><b>Summary: </b>" + stories[i][3] + "</p>";
								timeliner += "<p><b>Priority: </b>";
								timeliner += (stories[i][5] == 5) ? "High" : "Low";
								timeliner += "</p>";
							timeliner += "</dd>";
						timeliner += "</dl>";

					}
				}

			timeliner += "</div>";
		timeliner += "</div>";

		jQuery("#streamdisplayer").html(timeliner);

		jQuery.timeliner();

		jQuery("a.edit").click(function() {
			var current = jQuery(this).text();
			if (current ==  "Edit") jQuery(this).text("Close");
			else jQuery(this).text("Edit");
		});

	  });

	});

	jQuery("#stream_dropdownbox").trigger('change');


});