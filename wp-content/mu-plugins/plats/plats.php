<?php

add_action('admin_enqueue_scripts', function () {
    // The path to the JavaScript file within the mu-plugins directory
    $script_path = '/plats/plats.js';

    // Convert mu-plugins path to URL
    $mu_plugin_url = content_url() . '/mu-plugins';

    // Enqueue the script
    wp_enqueue_script('plats', $mu_plugin_url . $script_path, array('jquery'), filemtime(ABSPATH . '/' . MUPLUGINDIR . $script_path), true);
});


// // Add the action hook
// add_action(
//     'wp_insert_post_data',
//     function ($data, $post) {

//         if ($post['post_type'] != 'product') return $data;
//         if ($post['post_status'] == 'auto-draft') return $data;
//         $pid = $post['ID'];

//         $plat_viande = get_field('plat_viande', $pid);
//         $accompagnement_viande = get_field('accompagnement_viande', $pid);
//         $plat_vege = get_field('plat_vege', $pid);
//         $accompagnement_vege = get_field('accompagnement_vege', $pid);
//         $desserts = get_field('desserts', $pid);

//         if ($plat_viande) {
//             $texte[] = '<h3 class="plat" id="plat_viande">' . $plat_viande . '</h3>';
//         }
//         if ($accompagnement_viande) {
//             $texte[] = '<p class="accompagnement" id="accompagnement_viande">' . $accompagnement_viande . '</p>';
//         }
//         if ($plat_vege) {
//             $texte[] = '<h3 class="plat" id="plat_vege">' . $plat_vege . '</h3>';
//         }
//         if ($accompagnement_vege) {
//             $texte[] = '<p class="accompagnement" id="accompagnement_vege">' . $accompagnement_vege . '</p>';
//         }
//         if ($desserts) {
//             $texte[] = '<p id="desserts">' . $desserts . '</p>';
//         }

//         if(count($texte)) {
//             $data['excerpt'] = implode("<br>\n", $texte);
//         }
//         return $data;
//     },
//     99,
//     3
// );
