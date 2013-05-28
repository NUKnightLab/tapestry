<?php
	require( '../../../wp-load.php' );
	
	
	$taxonomy = 'stream';

	$my_stream = get_term_by("name", $_GET['stream'], $taxonomy);
	$terms = get_terms( $taxonomy );

	echo get_the_ID();

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


foreach ($streamposts as $post_id) :
        if ($my_post_id == $post_id){
                echo '<strong>' . get_post($post_id)->post_title . "<br>";
                echo $post_id . "<br>";
                echo get_post_meta( $post_id, 'tapestry_altdate', true ) . '</strong>';}
        else{
                echo get_post($post_id)->post_title . "<br>";
                echo $post_id . "<br>";
                echo get_post_meta( $post_id, 'tapestry_altdate', true );}
        echo "<br><br>";

endforeach;






	echo json_encode($streamposts);
?>
