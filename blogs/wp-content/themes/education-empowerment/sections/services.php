<?php

add_action( 'bizberg_before_homepage_blog', 'education_empowerment_services' );
function education_empowerment_services(){ 

	$data = bizberg_get_theme_mod( 'education_empowerment_services' );
	$data = json_decode( $data , true );

	if( empty( $data ) ){
		return;
	} ?>

	<section class="space-top space-extra-bottom services">
	    <div class="container">
	        <div class="row">

	        	<?php 
	        	foreach( $data as $value ){ 

	        		$icon    = !empty( $value['icon'] ) ? $value['icon'] : '';
	        		$page_id = !empty( $value['page_id'] ) ? $value['page_id'] : '';

	        		$services_post = get_post( $page_id ); ?>

		            <div class="col-md-4 col-xl-4 col-sm-12">
		                <div class="feature-style1">
		                    <div class="feature-icon">
		                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
		                        <div class="vs-circle"></div>
		                    </div>
		                    <h4 class="feature-title">
		                    	<a href="<?php echo esc_url( get_permalink( $services_post->ID ) ); ?>" class="text-inherit" >
		                    		<?php echo esc_html( $services_post->post_title ); ?>
		                    	</a>
		                    </h4>
		                    <p class="feature-text">
		                    	<?php echo esc_html( wp_trim_words( sanitize_text_field( $services_post->post_content ), 15, ' [...]' ) ); ?>
		                    </p>
		                </div>
		            </div>

		            <?php 
		        } ?>

	        </div>
	    </div>
	</section>

	<?php
}