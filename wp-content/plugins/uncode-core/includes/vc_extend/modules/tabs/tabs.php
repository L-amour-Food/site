<?php
/**
 * VC Tabs config
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$tab_id_1 = time() . '-1-' . rand(0, 100);
$tab_id_2 = time() . '-2-' . rand(0, 100);
vc_map(array(
	"name" => esc_html__('Tabs', 'uncode-core') ,
	'base' => 'vc_tabs',
	'weight' => 9300,
	'show_settings_on_create' => false,
	'is_container' => true,
	'icon' => 'fa fa-folder',
	'category' => array(
		esc_html__('Essentials', 'uncode-core') ,
	),
	'description' => esc_html__('Tabs tabbed content', 'uncode-core') ,
	'params' => array(
		array(
			'type' => 'checkbox',
			'heading' => esc_html__('Vertical tabs', 'uncode-core') ,
			'param_name' => 'vertical',
			'description' => esc_html__('Specify checkbox to allow all sections to be collapsible.', 'uncode-core') ,
			'value' => array(
				esc_html__("Yes, please", 'uncode-core') => 'yes'
			)
		) ,
		array(
			'type' => 'checkbox',
			'heading' => esc_html__('History (permalinks)', 'uncode-core') ,
			'param_name' => 'history',
			'description' => esc_html__('Activate this to activate url history for tabs.', 'uncode-core') ,
			'value' => array(
				esc_html__("Yes, please", 'uncode-core') => 'yes'
			)
		) ,
		array(
			'type' => 'dropdown',
			'heading' => esc_html__('Scroll target', 'uncode-core') ,
			'param_name' => 'target',
			'description' => esc_html__('Define the History scroll target on page load.', 'uncode-core') ,
			'dependency' => array(
				'element' => 'history',
				'not_empty' => true,
			) ,
			'value' => array(
				esc_html__('Scroll to tabs', 'uncode-core') => '',
				esc_html__('Scroll to parent row', 'uncode-core') => "row",
			) ,
		) ,
		array(
			'type' => 'checkbox',
			'heading' => esc_html__('Titles inherit typography', 'uncode-core') ,
			'param_name' => 'typography',
			'description' => esc_html__('Set the titles\' typography inherited from the general font family or inherited from the column font family.', 'uncode-core') ,
			'value' => array(
				esc_html__("Yes, please", 'uncode-core') => 'yes'
			)
		) ,
		array(
			'type' => 'dropdown',
			'heading' => esc_html__('Titles alignment', 'uncode-core') ,
			'param_name' => 'align',
			"value" => array(
				esc_html__('Center', 'uncode-core') => '',
				esc_html__('Left', 'uncode-core') => 'left',
				esc_html__('Right', 'uncode-core') => 'right',
			) ,
			'description' => esc_html__('Set the titles\'s alignment.', 'uncode-core') ,
			'dependency' => array(
				'element' => 'vertical',
				'is_empty' => true,
			) ,
		) ,
		array(
			'type' => 'checkbox',
			'heading' => esc_html__('Titles justify', 'uncode-core') ,
			'param_name' => 'width_100',
			'description' => esc_html__('Set the titles\'s justified alignment.', 'uncode-core') ,
			'value' => array(
				esc_html__("Yes, please", 'uncode-core') => 'yes'
			),
			'dependency' => array(
				'element' => 'vertical',
				'is_empty' => true,
			) ,
		) ,
		array(
			'type' => 'checkbox',
			'heading' => esc_html__('Divider Full-Width', 'uncode-core') ,
			'param_name' => 'border_100',
			'description' => esc_html__('Set the border divider to 100% width.', 'uncode-core') ,
			'value' => array(
				esc_html__("Yes, please", 'uncode-core') => 'yes'
			),
			'dependency' => array(
				'element' => 'vertical',
				'is_empty' => true,
			) ,
		) ,
		array(
			'type' => 'checkbox',
			'heading' => esc_html__('Scroll Tabs on Mobile', 'uncode-core') ,
			'param_name' => 'tab_scrolling',
			'description' => esc_html__('Enable the scroll horizontal for the tab navigation on mobile.', 'uncode-core') ,
			'value' => array(
				esc_html__("Yes, please", 'uncode-core') => 'yes'
			),
			'dependency' => array(
				'element' => 'vertical',
				'is_empty' => true,
			) ,
		) ,
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Element ID', 'uncode-core') ,
			'param_name' => 'el_id',
			'description' => esc_html__('This value has to be unique. Change it in case it\'s needed.', 'uncode-core') ,
			"group" => esc_html__("Extra", 'uncode-core') ,
		) ,
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Extra class name', 'uncode-core') ,
			'param_name' => 'el_class',
			'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your CSS file.', 'uncode-core'),
			"group" => esc_html__("Extra", 'uncode-core') ,
		),
	) ,
	'custom_markup' => '
<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
<ul class="tabs_controls">
</ul>
%content%
</div>',
	'default_content' => '
[vc_tab title="' . esc_html__('Tab 1', 'uncode-core') . '" tab_id="' . $tab_id_1 . '"][/vc_tab]
[vc_tab title="' . esc_html__('Tab 2', 'uncode-core') . '" tab_id="' . $tab_id_2 . '"][/vc_tab]
',
	'js_view' => 'VcTabsView'
));

vc_map(array(
	'name' => esc_html__('Tab', 'uncode-core') ,
	'base' => 'vc_tab',
	'allowed_container_element' => 'vc_row',
	'is_container' => true,
	'content_element' => false,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Title', 'uncode-core') ,
			'param_name' => 'title',
			'description' => esc_html__('Tab title.', 'uncode-core')
		) ,
		array(
			'type' => 'tab_id',
			'heading' => esc_html__('Tab ID', 'uncode-core') ,
			'param_name' => "tab_id",
		),
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Slug', 'uncode-core') ,
			'param_name' => "slug",
			'description' => esc_html__('Custom value used for permalink. This value has to be unique.', 'uncode-core')
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__('Icon', 'uncode-core') ,
			'param_name' => 'icon',
			'description' => esc_html__('Specify icon from library.', 'uncode-core') ,
			'value' => '',
			'settings' => array(
				'emptyIcon' => true,
				'iconsPerPage' => 1100,
				'type' => 'uncode'
			) ,
		) ,
		array(
			'type' => 'dropdown',
			'heading' => esc_html__('Icon position', 'uncode-core') ,
			'param_name' => 'icon_position',
			'value' => array(
				esc_html__('Left', 'uncode-core') => '',
				esc_html__('Right', 'uncode-core') => "right",
				// esc_html__('Above', 'uncode-core') => 'above',
			) ,
			'description' => esc_html__('Specify title location.', 'uncode-core'),
			'dependency' => array(
				'element' => 'icon',
				'not_empty' => true,
			) ,
		) ,
		array(
			"type" => "type_numeric_slider",
			"heading" => esc_html__("Inner Vertical Spacing", 'uncode-core') ,
			"param_name" => "gutter_size",
			"min" => 0,
			"max" => 4,
			"step" => 1,
			"value" => 2,
			"description" => esc_html__("Set the vertical rhythm between elements.", 'uncode-core') ,
		) ,
		array(
			"type" => "type_numeric_slider",
			"heading" => esc_html__("Custom Padding", 'uncode-core') ,
			"param_name" => "column_padding",
			"min" => 0,
			"max" => 5,
			"step" => 1,
			"value" => 2,
			"description" => esc_html__("Define a custom top and bottom padding.", 'uncode-core') ,
		) ,
	) ,
	'js_view' => 'VcTabView'
));
