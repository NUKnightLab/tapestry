<?php

/**
 * Adds Foo_Widget widget.
 */
class Tapestry_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'tapestry_widget', // Base ID
			'Tapestry Widget', // Name
			array( 'description' => __( 'A Widget to Display Tapestry Story Streams', 'text_domain' ), ) // Args
		);
	}

	public function home_widget(){
		echo "I'm on the home page";
	}
	public function post_widget(){}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
			$title = apply_filters( 'widget_title', $instance['title'] );

			echo $before_widget;
			if ( ! empty( $title ) )
				echo $before_title . $title . $after_title;

			
			$taxonomy = 'stream';
			//Gets the stream of this post
			$my_streams = wp_get_post_terms(get_the_ID(), 'stream', array("fields" => "names"));
			$my_stream = $my_streams[0];
			$my_post_id = get_the_ID();
			// Gets every "category" (term) in this taxonomy to get the respective posts
		    $terms = get_terms( $taxonomy );

		if ( ! is_home() ) { //If this isn't the homepage - display the stream that the post is in
			$posts_in_stream = array(); //IDs of the posts in this stream
		    foreach( $terms as $term ) : 
		    	if($my_stream == $term->name){
		    	 $posts = new WP_Query( "taxonomy=$taxonomy&term=$term->slug&posts_per_page=-1" );
		    	 while ( $posts->have_posts() ) :
		    	 	$posts->the_post();
			    	array_push($posts_in_stream, get_the_ID());
		    	 endwhile;
		    	}
			endforeach;

			// Comparison function
			function by_altdate ($post_ID_a, $post_ID_b) {
				$alt_date_a = get_post_meta( $post_ID_a, 'tapestry_altdate', true );
				$alt_date_b = get_post_meta( $post_ID_b, 'tapestry_altdate', true );



			    if ($alt_date_a == $alt_date_b) {
			        return 0;
			    }
			    return ($alt_date_a < $alt_date_b) ? -1 : 1;
			}

			uasort($posts_in_stream, 'by_altdate');
			//print_r($posts_in_stream);
			echo "<strong> Stream Name : " . $my_stream . "</strong><br><br>";
			foreach ($posts_in_stream as $post_id) : 
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


		   // foreach( $posts_in_stream as $post_id ) : 
		   // 	echo get_post($post_id)->post_name . '<br>';
		   //endforeach;

		   

		}
		else //If this is the homepage, then show the streams
		{
			echo "<p><strong>Streams:</strong><br>";
			 foreach( $terms as $term ) : 
			 	echo $term->name.'<br>';

			 	endforeach;
			echo "</p>";

		}
		
		echo $after_widget;
	}

		

	


	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	/*
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}*/

} // class Foo_Widget

?>