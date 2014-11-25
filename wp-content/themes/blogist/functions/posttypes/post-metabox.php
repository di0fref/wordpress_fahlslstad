<?php 
$prefix = 'tmnf_';
$meta_boxes = array();
$meta_box = array(
    'id' => 'meta-box-video',
    'title' => 'Themnific Post Options',
    'page' => 'post',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(

		array(
			'name' => '',
			'desc' => 'Post formats options',
			'id' => $prefix . 'main_rating',
			'type' => 'heading',
			'std' => ''
		),
			
				array(
					'name' => 'Video',
					'desc' => 'Enter iframe video code - 785px width.  ',
					'id' => $prefix . 'video',
					'type' => 'textarea',
					'std' => ''
				),
				
				array(
					'name' => 'Audio',
					'desc' => 'Enter iframe audio code.',
					'id' => $prefix . 'audio',
					'type' => 'textarea',
					'std' => ''
				),
				
				
				array(
					'name' => 'Link',
					'desc' => 'Insert the URL you wish to link to.',
					'id' => $prefix . 'linkss',
					'type' => 'text',
					'std' => ''
				),
				
				
		array(
			'name' => '',
			'desc' => 'Main Rating & Rating Category',
			'id' => $prefix . 'main_rating',
			'type' => 'heading',
			'std' => ''
		),
		
				array(
					'name' => 'Main rating - Score',
					'desc' => 'Add rating in &#37;.',
					'id' => $prefix . 'rating_main',
					'type' => 'text',
					'std' => ''
				),

				array(
					'name' => 'Summary',
					'desc' => 'Add short rating description',
					'id' => $prefix . 'rating_summary',
					'type' => 'text',
					'std' => ''
				),
				
		
		array(
			'name' => '',
			'desc' => 'Partial rating',
			'id' => $prefix . 'rating_partial',
			'type' => 'heading',
			'std' => ''
		),
				


				array(
					'name' => 'Rating #1',
					'desc' => '',
					'id' => $prefix . '',
					'type' => 'line',
					'std' => ''
				),

				array(
					'name' => 'Label',
					'desc' => 'Add partial rating label (eg. Design, Performance, Price...)',
					'id' => $prefix . 'rating_1_label',
					'type' => 'text',
					'std' => ''
				),
			
				array(
					'name' => 'Score',
					'desc' => 'Add rating: 1-100 (will be displayed in &#37;).',
					'id' => $prefix . 'rating_1',
					'type' => 'text',
					'std' => ''
				),
		



				array(
					'name' => 'Rating #2',
					'desc' => '',
					'id' => $prefix . '',
					'type' => 'line',
					'std' => ''
				),

				array(
					'name' => 'Label',
					'desc' => 'Add partial rating label (eg. Design, Performance, Price...)',
					'id' => $prefix . 'rating_2_label',
					'type' => 'text',
					'std' => ''
				),
			
				array(
					'name' => 'Score',
					'desc' => 'Add rating: 1-100 (will be displayed in &#37;).',
					'id' => $prefix . 'rating_2',
					'type' => 'text',
					'std' => ''
				),





				array(
					'name' => 'Rating #3',
					'desc' => '',
					'id' => $prefix . '',
					'type' => 'line',
					'std' => ''
				),

				array(
					'name' => 'Label',
					'desc' => 'Add partial rating label (eg. Design, Performance, Price...)',
					'id' => $prefix . 'rating_3_label',
					'type' => 'text',
					'std' => ''
				),
			
				array(
					'name' => 'Score',
					'desc' => 'Add rating: 1-100 (will be displayed in &#37;).',
					'id' => $prefix . 'rating_3',
					'type' => 'text',
					'std' => ''
				),






				array(
					'name' => 'Rating #4',
					'desc' => '',
					'id' => $prefix . '',
					'type' => 'line',
					'std' => ''
				),
				array(
					'name' => 'Label',
					'desc' => 'Add partial rating label (eg. Design, Performance, Price...)',
					'id' => $prefix . 'rating_4_label',
					'type' => 'text',
					'std' => ''
				),
			
				array(
					'name' => 'Score',
					'desc' => 'Add rating: 1-100 (will be displayed in &#37;).',
					'id' => $prefix . 'rating_4',
					'type' => 'text',
					'std' => ''
				),
				
				


				array(
					'name' => 'Rating #5',
					'desc' => '',
					'id' => $prefix . '',
					'type' => 'line',
					'std' => ''
				),				
				
				array(
					'name' => 'Label',
					'desc' => 'Add partial rating label (eg. Design, Performance, Price...)',
					'id' => $prefix . 'rating_5_label',
					'type' => 'text',
					'std' => ''
				),
			
				array(
					'name' => 'Score',
					'desc' => 'Add rating: 1-100 (will be displayed in &#37;).',
					'id' => $prefix . 'rating_5',
					'type' => 'text',
					'std' => ''
				),				
				
				
				

				array(
					'name' => 'Rating #6',
					'desc' => '',
					'id' => $prefix . '',
					'type' => 'line',
					'std' => ''
				),
				
				array(
					'name' => 'Label',
					'desc' => 'Add partial rating label (eg. Design, Performance, Price...)',
					'id' => $prefix . 'rating_6_label',
					'type' => 'text',
					'std' => ''
				),
			
				array(
					'name' => 'Score',
					'desc' => 'Add rating: 1-100 (will be displayed in &#37;).',
					'id' => $prefix . 'rating_6',
					'type' => 'text',
					'std' => ''
				),				
				
				
				
				
				
				
				
	
    )
);




add_action('admin_menu', 'mytheme_add_box');

// Add meta box
function mytheme_add_box() {
    global $meta_box;

    add_meta_box($meta_box['id'], $meta_box['title'], 'mytheme_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
}


// enqueue scripts and styles, but only if is_admin
add_action('admin_head','add_custom_scripts');
function add_custom_scripts() {

	if(is_admin()) {
		wp_enqueue_style('metaboxes', get_template_directory_uri().'/functions/posttypes/lib/metaboxes.css');
	}

}

// Callback function to show fields in meta box
function mytheme_show_box() {
    global $meta_box, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table">';

    foreach ($meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);

        echo '<tr class="meta">',
                '<th style="width:15%; padding-left:30px;"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
        switch ($field['type']) {
            case 'text':
                echo '<input style="width:34%;" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', esc_attr($meta ? $meta : $field['std']), '" size="30" style="width:97%" />', '<br />', $field['desc'];
                break;
				
            case 'heading':
                echo '<h3 id="', $field['id'], '" class="meta">'; echo $field['desc']; echo '</h3>';
                break;
				
				
            case 'line':
                echo '<hr class="meta">';
                break;
				
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">',esc_textarea( $meta ? $meta : $field['std']), '</textarea>', '<br />', $field['desc'];
                break;
            case 'select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '" class="meta">';
                foreach ($field['options'] as $option) {
                    echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>', '<br />', $field['desc'];
                break;
            case 'radio':
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                }
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                break;
        }
        echo     '<td>',
            '</tr>';
    }

    echo '</table>';
}


add_action('save_post', 'mytheme_save_data');

// Save data from meta box
function mytheme_save_data($post_id) {
    global $meta_box;

    // verify nonce
	
	if (isset($_POST['mytheme_meta_box_nonce'])) {
	
    if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;

    }

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    foreach ($meta_box['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];

        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}}

?>