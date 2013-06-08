<?php
	// For an extreme performance boost remove this line and replace all wordpress functions
	// with simple database queries. Although this is against the Wordpress way of doing things,
	// this line loads an entire instance of Wordpress each time al.php is accessed (essentially
	// each time the select box is changed)

	require( '../../../wp-load.php' );


	$taxonomy = 'stream';

	$my_stream = get_term_by("name", $_GET['stream'], $taxonomy);
	$terms = get_terms( $taxonomy );

	// echo get_the_ID();

	$streamposts = array();

	foreach( $terms as $term ) :
		if($my_stream->name == $term->name) {
			$posts = new WP_Query( "taxonomy=$taxonomy&term=$term->slug&posts_per_page=-1" );
			// echo "Stream Name: ";
			// echo  $term->name ;

			while ( $posts->have_posts() ) :
				$posts->the_post();
				// echo '<li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>' . get_the_title() . '</li>';
				// echo get_the_ID();
				array_push($streamposts,get_the_ID());
			endwhile;
		}
	endforeach;


	$postid = $_GET['postid'];
	if ($postid) {
		$postid = intval($postid);
	}

	// $post = get_post($postid, OBJECT);

	if (!in_array($postid, $streamposts)) {
		array_push($streamposts, $postid);
	}

	/*
	foreach( $streamposts as $streampost) :
		$post = get_post($streampost, OBJECT);
		$altdate = get_post_meta($streampost, 'altdate', false);
		$date = $post->post_date;

	endforeach;
	*/

	// Comparison function
	function by_altdate ($post_ID_a, $post_ID_b) {
        	$alt_date_a = get_post_meta( $post_ID_a, 'tapestry_altdate', true );
        	$alt_date_b = get_post_meta( $post_ID_b, 'tapestry_altdate', true );



    		if ($alt_date_a == $alt_date_b) {
        		return 0;
    		}
    		return ($alt_date_a < $alt_date_b) ? -1 : 1;
	}

	uasort($streamposts, 'by_altdate');

	$response = array();

	foreach ($streamposts as $post_id) :
            $temp0 = get_post($post_id)->post_title;
            $temp1 = $post_id;
            $temp2 = get_post_meta( $post_id, 'tapestry_altdate', true ); // date in weird datetime-local format
            $temp3 = get_post_meta( $post_id, 'tapestry_summary', true );
            $temp4 = get_post_meta( $post_id, 'tapestry_headline', true );
            $temp5 = get_post_meta( $post_id, 'tapestry_priority', true );
            $temp6 = get_post($post_id) -> post_date;

            $temp = array($temp0, $temp1, $temp2, $temp3, $temp4, $temp5, $temp6);
            array_push($response, $temp);

            /*
            	this might be to prevent duplicate stories from being printed
            	if you are in a certain post, and $streamposts also has that postID
            	it will print that post twice
            	        if ($my_post_id == $post_id){
           	*/

	endforeach;

	echo json_encode($response);
?>
