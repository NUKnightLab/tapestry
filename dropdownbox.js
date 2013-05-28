function displayDate1(){
	var data = {
		action: 'my_action',
		whatever: ajax_object.we_value      // We pass php values differently!
	};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		alert('Got this from the server: ' + response);
	});
}

function displayDate(){
	alert('hello!');
}

jQuery("#stream_dropdownbox").change(function() {
  var stream = jQuery("#stream_dropdownbox option:selected").val();
  var postid = jQuery("#post_ID").val();


  jQuery.ajax({
	url: "http://www.knightlab.dhrumilmehta.com/wp-content/plugins/tapestry2/al.php?postid=" + postid + "&stream=" + stream	
  }).done(function( resp ) {
	//console.log(resp);
	jQuery("#abc_product_categories_sortable").html(resp);
  });

});
