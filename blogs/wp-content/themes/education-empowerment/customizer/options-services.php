<?php

add_action( 'init' , 'education_empowerment_servicess' );
function education_empowerment_servicess(){

	Kirki::add_section( 'education_empowerment_services', array(
        'title'   => esc_html__( 'Services', 'education-empowerment' ),
        'section' => 'homepage'
    ) );

    Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'services_status',
		'label'       => esc_html__( 'Enable / Disable', 'education-empowerment' ),
		'section'     => 'education_empowerment_services',
		'default'     => false,
	] );

	Kirki::add_field( 'bizberg', array(
    	'type'        => 'advanced-repeater',
    	'label'       => esc_html__( 'Services', 'education-empowerment' ),
	    'section'     => 'education_empowerment_services',
	    'settings'    => 'education_empowerment_services',
	    'active_callback' => [
			[
				'setting'  => 'services_status',
				'operator' => '==',
				'value'    => true,
			]
		],
	    'choices' => [
	        'button_label' => esc_html__( 'Add Services', 'education-empowerment' ),
	        'row_label' => [
	            'value' => esc_html__( 'Services', 'education-empowerment' ),
	        ],
	        'limit'  => 3,
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