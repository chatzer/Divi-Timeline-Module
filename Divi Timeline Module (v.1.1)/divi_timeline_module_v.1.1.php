<?php
/*
 * Plugin Name: Divi Timeline Module
 * Plugin URI: http://divi.tutsdirectory.com/
 * Version: 1.1
 * Description: Timelines provide a logical, clean way to tell your story. It enables a viewer to understand temporal relationships quickly. Use this Divi timeline module to showcase information and events that happen over time.
 * Author: Tuts Directory
 * Author URI: http://divi.tutsdirectory.com/
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


define( 'TL_TIMELINE_PLUGIN_DIR', trailingslashit( dirname(__FILE__) ) );
define( 'TL_TIMELINE_PLUGIN_URI', plugins_url('', __FILE__) );


add_action('plugins_loaded', 'tl_init');
    
function tl_init() {
	add_action('et_builder_ready', 'tl_module');
	add_action('wp_enqueue_scripts', 'tl_enqueue', 9999);
	add_action('admin_enqueue_scripts', 'tl_enqueue_admin', 9999);
}
	
	
function tl_enqueue_admin() {
	wp_enqueue_style('tl_admin_css', TL_TIMELINE_PLUGIN_URI.'/css/admin_divi_timeline_module_v.1.1.css');
}
    
function tl_enqueue() {
	wp_enqueue_style('tl_custom_css',TL_TIMELINE_PLUGIN_URI.'/css/divi_timeline_module_v.1.1.css');
}
	
function tl_module(){

 class TL_Builder_Module_Timeline extends ET_Builder_Module {
	function init() {
		$this->name       = esc_html__( 'Timeline', 'et_builder' );
		$this->slug       = 'et_pb_timeline';
		$this->fb_support = true;
		$this->child_slug = 'et_pb_timeline_item';

		$this->whitelisted_fields = array(
			'admin_label',
			'module_id',
			'module_class',
			'horizontal_line',
			'horizontal_line_color'
		);
		
		$this->fields_defaults = array(
			'horizontal_line'            => array( 'on' ),
			'horizontal_line_color'		   => array( '#eeeeee' ),
		);
		
		
		$this->main_css_element = '%%order_class%%.et_pb_timeline';
		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => esc_html__( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h4.timeline-title",
					),
					'font_size' => array(
						'default' => '20px',
					),
					'letter_spacing' => array(
						'default' => '0px',
					),
					'line_height' => array(
						'default' => '1em',
					),
				),
				'body'   => array(
					'label'    => esc_html__( 'Body', 'et_builder' ),
					'css'      => array(
						'main'        => "{$this->main_css_element} .timeline-body",
						'line_height' => "{$this->main_css_element} .timeline-body p",
					),
					'font_size' => array(
						'default' => '20px',
					),
					'letter_spacing' => array(
						'default' => '0px',
					),
					'line_height' => array(
						'default' => '1em',
					),
				),
			),
			'border' => array(
				'css'        => array(
					'main' => "{$this->main_css_element} .timeline-panel",
				),
			),
			'custom_margin_padding' => array(
				'use_margin' => false,
				'css'        => array(
					'main' => "{$this->main_css_element} .timeline-panel",
					'important' => 'all',
				),
			),
		);
		
	}

	function get_fields() {
	
		
		
		$fields = array(
			'disabled_on' => array(
				'label'           => esc_html__( 'Disable on', 'et_builder' ),
				'type'            => 'multiple_checkboxes',
				'options'         => array(
					'phone'   => esc_html__( 'Phone', 'et_builder' ),
					'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
					'desktop' => esc_html__( 'Desktop', 'et_builder' ),
				),
				'additional_att'  => 'disable_on',
				'option_category' => 'configuration',
				'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => esc_html__( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => esc_html__( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'et_pb_custom_css_regular',
			),
			'module_class' => array(
				'label'           => esc_html__( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'et_pb_custom_css_regular',
			),
			'horizontal_line' => array(
				'label'             => esc_html__( 'Horizontal Line', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( "No", 'et_builder' ),
				),
				'description'       => esc_html__( 'If enabled, Horizontal Line will display', 'et_builder' ),
			),
			'horizontal_line_color' => array(
				'label'             => esc_html__( 'Horizontal Line Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'description'       => esc_html__( 'Here you can define a custom color for Horizontal Line', 'et_builder' ),
			),
		);
		return $fields;
	}

	function pre_shortcode_content() {
		global $et_pb_timeline_item_number;

		$et_pb_timeline_item_number = 1;

	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
	
		$module_id                      = $this->shortcode_atts['module_id'];
		$module_class                   = $this->shortcode_atts['module_class'];

		$horizontal_line              	= $this->shortcode_atts['horizontal_line'];
		$horizontal_line_color          = $this->shortcode_atts['horizontal_line_color'];
		
		global $et_pb_timeline_item_number;
		
		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $horizontal_line_color ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .timeline::before',
					'declaration' => sprintf(
						'background-color: %1$s',
						esc_html( $horizontal_line_color )
					),
				) );
		}
		
		if ( 'on' !== $horizontal_line ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '.timeline::before',
					'declaration' =>  'display: none;'
				) );
		}


		$output = sprintf(
			'<div%3$s class="et_pb_module et_pb_timeline%2$s">
			  <ul class="timeline">
				%1$s
			  </ul>
			</div> <!-- .et_pb_accordion -->',
			$this->shortcode_content,
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' )
		);

		return $output;
	}
 }
 new TL_Builder_Module_Timeline;

 class TL_Builder_Module_Timeline_Item extends ET_Builder_Module {
	function init() {
		$this->name                  = esc_html__( 'Timeline', 'et_builder' );
		$this->slug                  = 'et_pb_timeline_item';
		$this->fb_support            = true;
		$this->type                  = 'child';
		$this->child_title_var       = 'title';
		//$this->no_shortcode_callback = true;

		$this->whitelisted_fields = array(
			'title',
			'content_new',
			'box_background_color',
			'title_text_color',
			'content_text_color',
			'use_icon',
			'font_icon',
			'icon_color',
			'circle_color',
			'circle_border_color',
			'image',
			'alt',
			'animation',
		);
		
		$et_accent_color = et_builder_accent_color();
		
		$this->fields_defaults = array(
			'use_icon'            => array( 'off' ),
			'icon_color'          => array( $et_accent_color, 'add_default_setting' ),
			'animation'           => array( 'top' ),
		);
		
		
		$this->custom_css_options = array(
			'Box' => array(
				'label'    => esc_html__( 'Timeline Box', 'et_builder' ),
				'selector' => '.timeline-panel',
			),
			'box_title' => array(
				'label'    => esc_html__( 'Timeline Box Title', 'et_builder' ),
				'selector' => '.timeline-heading',
			),
			'toggle_content' => array(
				'label'    => esc_html__( 'Timeline Box Content', 'et_builder' ),
				'selector' => '.timeline-body',
			),
		);
	}

	function get_fields() {
	
		$et_accent_color = et_builder_accent_color();

	
		$fields = array(
			'title' => array(
				'label'           => esc_html__( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'The toggle title will appear above the content and when the toggle is closed.', 'et_builder' ),
			),
			'content_new' => array(
				'label'           => esc_html__( 'Content', 'et_builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can define the content that will be placed within the current tab.', 'et_builder' ),
			),
			'box_background_color' => array(
				'label'             => esc_html__( 'Box Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'title_text_color' => array(
				'label'             => esc_html__( 'Title Text Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'content_text_color' => array(
				'label'             => esc_html__( 'Content Text Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'use_icon' => array(
				'label'           => esc_html__( 'Use Icon', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'affects'     => array(
					'#et_pb_font_icon',
					'#et_pb_icon_color',
					'#et_pb_image',
					'#et_pb_alt',
				),
				'description' => esc_html__( 'Here you can choose whether icon set below should be used.', 'et_builder' ),
			),
			'font_icon' => array(
				'label'               => esc_html__( 'Icon', 'et_builder' ),
				'type'                => 'text',
				'option_category'     => 'basic_option',
				'class'               => array( 'et-pb-font-icon' ),
				'renderer'            => 'et_pb_get_font_icon_list',
				'renderer_with_field' => true,
				'description'         => esc_html__( 'Choose an icon to display with your blurb.', 'et_builder' ),
				'depends_default'     => true,
			),
			'icon_color' => array(
				'label'             => esc_html__( 'Icon Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'description'       => esc_html__( 'Here you can define a custom color for your icon.', 'et_builder' ),
				'depends_default'   => true,
			),
			'circle_color' => array(
				'label'           => esc_html__( 'Circle Color', 'et_builder' ),
				'type'            => 'color',
				'description'     => esc_html__( 'Here you can define a custom color for the icon circle.', 'et_builder' ),
			),
			'circle_border_color' => array(
				'label'           => esc_html__( 'Circle Border Color', 'et_builder' ),
				'type'            => 'color',
				'description'     => esc_html__( 'Here you can define a custom color for the icon circle border.', 'et_builder' ),
			),
			'image' => array(
				'label'              => esc_html__( 'Image', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As Image', 'et_builder' ),
				'depends_show_if'    => 'off',
				'description'        => esc_html__( 'Upload an image to display at the top of your blurb.', 'et_builder' ),
			),
			'alt' => array(
				'label'           => esc_html__( 'Image Alt Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the HTML ALT text for your image here.', 'et_builder' ),
				'depends_show_if' => 'off',
			),
			'animation' => array(
				'label'             => esc_html__( 'Image/Icon Animation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'top'    => esc_html__( 'Top To Bottom', 'et_builder' ),
					'left'   => esc_html__( 'Left To Right', 'et_builder' ),
					'right'  => esc_html__( 'Right To Left', 'et_builder' ),
					'bottom' => esc_html__( 'Bottom To Top', 'et_builder' ),
					'off'    => esc_html__( 'No Animation', 'et_builder' ),
				),
				'description'       => esc_html__( 'This controls the direction of the lazy-loading animation.', 'et_builder' ),
			),
		);
		return $fields;
	}
	
	function shortcode_callback( $atts, $content = null, $function_name ) {
		global $et_pb_timeline_item_number;
		
		$timeline_title                      = $this->shortcode_atts['title'];
		$timeline_content                    = $content;

		$box_background_color                = $this->shortcode_atts['box_background_color'];
		$title_text_color                    = $this->shortcode_atts['title_text_color'];
		$content_text_color                  = $this->shortcode_atts['content_text_color'];
		$circle_border_color  				 = $this->shortcode_atts['circle_border_color'];

		$use_icon              				 = $this->shortcode_atts['use_icon'];
		$font_icon             				 = $this->shortcode_atts['font_icon'];
		$icon_color           				 = $this->shortcode_atts['icon_color'];
		$circle_color          				 = $this->shortcode_atts['circle_color'];
		$image              			     = $this->shortcode_atts['image'];
		$alt                			     = $this->shortcode_atts['alt'];
		$animation            				 = $this->shortcode_atts['animation'];

		
		$et_pb_timeline_item_number++;
		

		
		if ( '' !== trim( $image ) || '' !== $font_icon ) {
			if ( 'off' === $use_icon ) {	
				$image = sprintf(
					'<img src="%1$s" alt="%2$s" class="et-waypoint%3$s" />',
					esc_url( $image ),
					esc_attr( $alt ),
					esc_attr( " et_pb_animation_{$animation}" )
				);
			} else {
				$icon_style = sprintf( 'color: %1$s;', esc_attr( $icon_color ) );
				$icon_style .= sprintf( ' background-color: %1$s;', esc_attr( $circle_color ) );
				$icon_style .= sprintf( ' border-color: %1$s;', esc_attr( $circle_border_color ) );

				$image = sprintf(
					'<span class="et-pb-icon et-waypoint%2$s%3$s%4$s" style="%5$s">%1$s</span>',
					esc_attr( et_pb_process_font_icon( $font_icon ) ),
					esc_attr( " et_pb_animation_{$animation}" ),
					' et-pb-icon-circle',
					' et-pb-icon-circle-border',
					$icon_style
				);
			}

		}
	
		if ( '' !== trim( $box_background_color ) ) {
			$box_background_color_style .= sprintf( ' background-color: %1$s;', esc_attr( $box_background_color ) );
		}
		
		if ( '' !== trim( $title_text_color ) ) {
			$title_text_color_style .= sprintf( ' color: %1$s;', esc_attr( $title_text_color ) );
		}
		
		if ( '' !== trim( $box_background_color ) ) {
			$content_text_color_style .= sprintf( ' color: %1$s;', esc_attr( $content_text_color ) );
		}
		
		$tl_bg_icon =  ($image == '') ? 'timeline-badge-bg' :'';
		$tl_class = ($et_pb_timeline_item_number % 2 != 0) ? 'timeline-inverted' :'';
		$output = sprintf(
			'<li class="%1$s">
			  <div class="timeline-badge %5$s">%2$s</div>
			  <div class="timeline-panel" style="%6$s">
				<div class="timeline-heading">
				  <h4 class="timeline-title" style="%7$s">%3$s</h4>
				</div>
				<div class="timeline-body" style="%8$s">
				  %4$s
				</div>
			  </div>
       		 </li>',
			 $tl_class,
			 $image,
			( '' !== $timeline_title ? $timeline_title : '' ),
			( '' !== $timeline_content ? $timeline_content : ''),
			$tl_bg_icon,
			$box_background_color_style,
			$title_text_color_style,
			$content_text_color_style
		);

		return $output;
		
	}
	
 }
 new TL_Builder_Module_Timeline_Item;

}

 ?>