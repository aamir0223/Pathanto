<?php

add_action( 'init' , 'education_empowerment_posts' );
function education_empowerment_posts(){

	Kirki::add_section( 'education_empowerment_posts_sections', array(
        'title'   => esc_html__( 'Top Posts', 'education-empowerment' ),
        'section' => 'homepage'
    ) );

    Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'top_posts_status',
		'label'       => esc_html__( 'Enable / Disable', 'education-empowerment' ),
		'section'     => 'education_empowerment_posts_sections',
		'default'     => false,
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'top_posts_title',
		'label'    => esc_html__( 'Title', 'education-empowerment' ),
		'default'  => esc_html__( 'TOP POSTS', 'education-empowerment' ),
		'section'  => 'education_empowerment_posts_sections',
		'active_callback' => [
			[
				'setting'  => 'top_posts_status',
				'operator' => '==',
				'value'    => true,
			]
		],
	] );

	Kirki::add_field( 'bizberg', array(
    	'type'        => 'advanced-repeater',
    	'label'       => esc_html__( 'Posts', 'education-empowerment' ),
	    'section'     => 'education_empowerment_posts_sections',
	    'settings'    => 'education_empowerment_posts_sections',
	    'active_callback' => [
			[
				'setting'  => 'top_posts_status',
				'operator' => '==',
				'value'    => true,
			]
		],
	    'choices' => [
	        'button_label' => esc_html__( 'Add Post', 'education-empowerment' ),
	        'row_label' => [
	            'value' => esc_html__( 'Posts', 'education-empowerment' ),
	        ],
	        'fields' => [
	        	'icon'  => [
	                'type'        => 'fontawesome',
	                'label'       => esc_html__( 'Icon', 'education-empowerment' ),
	                'default'     => 'fab fa-accusoft',
	                'choices'     => bizberg_get_fontawesome_options(),
	            ],
	            'page_id' => [
	                'type'        => 'select',
	                'label'       => esc_html__( 'Page', 'education-empowerment' ),
	                'choices'     => bizberg_get_all_pages()
	            ],
	        ],
	    ]
    ));

}