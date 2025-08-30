<?php

add_action( 'bizberg_before_homepage_blog', 'education_empowerment_top_posts' );
function education_empowerment_top_posts(){ 

	$title  = bizberg_get_theme_mod( 'top_posts_title' );
	$status = bizberg_get_theme_mod( 'top_posts_status' );
	$data   = bizberg_get_theme_mod( 'education_empowerment_posts_sections' );
	$data   = json_decode( $data , true );

	if( empty( $status ) ){
		return;
	} ?>

	<section class="top_posts">
		<div class="container">
			<div class="row">
				<div class="title_wrapper">
					<h3><?php echo esc_html( $title ); ?></h3>
				</div>
				<div class="post2_wrapper">
					<?php 
					foreach( $data as $value ){
						$icon     = !empty( $value['icon'] ) ? $value['icon'] : '';
						$page_id  = !empty( $value['page_id'] ) ? $value['page_id'] : '';
						$page_obj = get_post( $page_id ); ?>
						<div class="col">
							<a href="<?php echo esc_url( get_permalink( $page_obj->ID ) ); ?>">
								<div class="item">
									<span><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
									<?php echo esc_html( $page_obj->post_title ); ?>
								</div>
							</a>
						</div>
						<?php 
					} ?>
				</div>
			</div>
		</div>
	</section>
	
	<?php
}