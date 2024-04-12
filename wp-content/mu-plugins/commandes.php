<?php

/**
 * Ajoute un lien personnalisé au menu d'administration de WordPress.
 */
add_action('admin_menu',function() {
    global $menu;
    $url = admin_url('?voir-commandes'); // Construit l'URL pour le menu.
    array_unshift($menu, array('Commandes', 'manage_options', $url, '', 'menu-top', '', 'dashicons-cart')); // Ajoute à la première position.
}

); // Ajoute l'action au hook admin_menu.


add_action('rest_api_init', function () {

    register_rest_route('custom/v1', '/commande-traitee/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => function ($request) {
          $id = $request->get_param( 'id' );
            $etat = get_field('commande_traitee', $id);
            $etat = $etat ? false : true;
            update_field('commande_traitee',$etat, $id);
            return ['id'=>$id, 'etat'=>$etat];        
        }
    ));
});

if(isset($_GET['voir-commandes'])) {
    add_action('admin_init',function() {

    if(!current_user_can('manage_options')) return;
        $voir_commandes = $_GET['voir-commandes'];
        if(!$voir_commandes) {
            $voir_commandes = 7;
        }
        if($voir_commandes == 7) {
            $autres = 31;
        } else {
            $autres = 7;
        }
        setlocale(LC_TIME, 'fr_FR.UTF-8'); // Set locale to French
        $args = array(
            'limit'=>10000,
            'status' => [
                'pending' ,
                'processing' ,
                'on-hold' ,
                'completed' ,
            ],
            'date_created' => '>' . (new DateTime($voir_commandes.' days ago'))->format('Y-m-d H:i:s')
        );
        $orders = wc_get_orders($args);
        $commandes = [];
        foreach($orders as &$order) {
            $id = $order->get_id();

            $order_data = $order->get_data(); // The Order data
            $commande = [];
            $commande['id'] = $id;
            $commande['nom'] = $order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name'];
            $commande['date'] = strftime('%e %B %Y à %H:%M', strtotime($order_data['date_created']));
            $commande['total'] = wc_price($order_data['total']);
            $commande['payee'] = $order_data['status'] == 'completed';
            $commande['etat'] = get_field('commande_traitee', $id);
            $achats=[];
            $photos = [];
            foreach ($order->get_items() as $item_id => $item) {
                $product = $item->get_product();
                $achats[] = ['nom'=>$product->get_name(),'quantite'=>
                $quantity = $item->get_quantity()];
                $photos[] = get_the_post_thumbnail_url($product->get_parent_id());
            }

            $commande['achats'] = $achats;
            $commande['photos'] = array_unique($photos);
            $commande['photo'] = $commande['photos'][0]??false;

            $commandes[] = $commande;
        }
        include(__DIR__.'/commandes/index.php');
        // echo '<ul>';
        // foreach ($orders as $order) {
        //     $order_data = $order->get_data(); // The Order data
        //     $buyer_name = $order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name'];
        //     echo '<li>' . esc_html($buyer_name) . ' ordered:';
        //     echo '<ul>';
        //     foreach ($order->get_items() as $item_id => $item) {
        //         $product = $item->get_product();
        //         echo '<li>' . esc_html($product->get_name()) . '</li>';
        //     }
        //     echo '</ul>';
        //     echo '</li>';
        // }
        // echo '</ul>';
        exit;
        
});
}


/**
 * Ajoute une méta-donnée personnalisée à une commande WooCommerce.
 * @param int $order_id L'ID de la commande à laquelle ajouter la méta-donnée.
 * @param string $meta_key La clé de la méta-donnée.
 * @param mixed $meta_value La valeur de la méta-donnée.
 */
function add_custom_meta_to_order($order_id, $meta_key, $meta_value) {
    $order = wc_get_order($order_id);
    if ($order) {
        $order->update_meta_data( $meta_key, $meta_value );
    }
}



/**
 * Récupère une méta-donnée d'une commande WooCommerce.
 * @param int $order_id L'ID de la commande dont on veut récupérer la méta-donnée.
 * @param string $meta_key La clé de la méta-donnée à récupérer.
 * @return mixed La valeur de la méta-donnée récupérée, ou false si la méta-donnée n'existe pas.
 */
function get_custom_meta_from_order($order_id, $meta_key) {
    $order = wc_get_order($order_id);
    if ($order) {
        return $order->get_meta($meta_key, true); // Récupère la valeur de la méta-donnée.
    }
    return false; // Retourne false si la commande n'existe pas.
}
