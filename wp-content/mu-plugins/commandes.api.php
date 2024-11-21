<?php
add_action('rest_api_init', function () {
/**
 * route pour le detail du menu du jour + nb commandes pour le user (optionnel)
 *  
 */
   
    register_rest_route('custom/v1', '/commande', array(
        // 'methods' => 'POST',
        'methods' => ['GET', 'POST'],
        'callback' => function ($request) {
            $params = json_decode(file_get_contents('php://input'), true);
            if(empty($params)) {
                $params = $_POST;
            }
            if(empty($params)) {
                $params = $_GET;
            }
            // $params = $request->get_params();
            $email = $params['email'] ?? false;
            $user_name = trim(($params['prenom']??'').' '.($params['nom'] ?? ''));

            if (!$email) return erreur('email', 'Email manquant', 400);

            $date_commande = $params['date_commande'] ?? false;
            if (!$date_commande) return erreur('date_commande', 'Date de commande manquante', 400);

            $datetime_commande = DateTime::createFromFormat('Y-m-d', $date_commande);
            if (!$datetime_commande) return erreur('date_commande', 'Date de commande invalide (Format Attendu: yyyy-mm-dd)', 400);

            $produit = get_menu_plat(date('d/m/Y', $datetime_commande->getTimestamp()));
            if (!$produit) return erreur('produit', 'Aucun produit disponible à la date ' . $date_commande, 400);

            $infos = [];
            $infos['dessert'] = $params['dessert'] ?? false;

            $infos['plat'] = $params['plat'] ?? false;
            if (!$infos['dessert'] && !$infos['plat']) return erreur('plat', 'Plat manquant', 400);
            $plats = ['plat', 'specialite'];
            if (!$infos['dessert'] && !in_array($infos['plat'], $plats)) return erreur('plat', 'Type de plat invalide. Valeurs possibles: ' . implode(', ', $plats), 400);
            if ($infos['dessert'] && $infos['plat'] =='specialite') return erreur('type_plat', 'Impossible de commander un déssert avec la spécialité du jour', 400);
            
            
            $infos['type_plat'] = $params['type_plat'] ?? false;
            if (!$infos['dessert'] && !$infos['type_plat']) return erreur('type_plat', 'Type de plat manquant', 400);
            
            $types_plats = ['viande', 'vege'];
            if (!$infos['dessert'] && !in_array($infos['type_plat'], $types_plats)) return erreur('type_plat', 'Type de plat invalide. Valeurs possibles: ' . implode(', ', $types_plats), 400);

            $variation = get_variation_plat($produit['id'], $infos);
            if (!$variation) return erreur('variation', 'Impossbile de trouver un plat correspondat à la requète ' . json_encode($infos), 400);

            $user = get_or_create_woocommerce_customer($email, $user_name);

            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            if($params['test']??false) {
                return ['infos'=>$infos];
            }
            $commande = create_order_for_variation($user->ID, $variation->get_id());

            return $commande;
        },
    ));

    function get_variation_plat($id, $infos)
    {

        $variations = get_all_variations($id);
        // print_r([$infos, $variations]);
        if ($infos['plat']) {
            $search = 'Type de plat: ';
            if ($infos['plat'] == "specialite") {
                $search .= 'Suggestion du Jour';
                if ($infos['type_plat'] == 'vege') {
                    $search .= ' végétarienne';
                }
            } else {
                if ($infos['type_plat'] == 'vege') {
                    $search .= 'Végétarien';
                } else {
                    $search .= 'Plat';
                }

            if ($infos['dessert']) {
                $search .= ' + dessert';
            }
        }

            foreach ($variations as $variation) {
                if ($variation->get_attribute_summary() == $search) return $variation;
            }
        } else {
            if ($infos['dessert']) {
                foreach ($variations as $variation) {
                    if ($variation->get_attribute_summary() == 'Type de plat: Je veux que du dessert !') return $variation;
                }
            }
        }


    }
    /**
     * Retrieve all variations for a given product ID.
     * 
     * @param int $product_id The ID of the product.
     * @return array Array of WC_Product_Variation objects.
     */
    function get_all_variations($product_id)
    {
        $product = wc_get_product($product_id);
        $variations = [];

        if ($product && $product->is_type('variable')) {
            foreach ($product->get_children() as $child_id) {
                $variations[] = wc_get_product($child_id);
            }
        }

        return $variations;
    }

    /**
 * Create an order for a user with a specific product variation.
 * 
 * @param int $user_id The user ID for whom the order is created.
 * @param int $variation_id The product variation ID to include in the order.
 * @return WC_Order The created order object.
 */
function create_order_for_variation($user_id, $variation_id) {
    $user = new WC_Customer($user_id);
    $order = wc_create_order();
    $order->set_customer_id($user_id);
    $order->add_product(wc_get_product($variation_id), 1); // quantity set to 1
    $order->set_address($user->get_billing(), 'billing');
    $order->set_address($user->get_shipping(), 'shipping');
    $order->set_status('on-hold');
    $order->calculate_totals();

    return $order->get_id();
}


    function erreur($titre, $message, $code)
    {
        return new WP_Error(
            $titre,
            $message,
            array('status' => $code)
        );
    }
    /**
     * Get an existing user by email or create a new WooCommerce customer.
     *
     * @param string $user_email The user's email address.
     * @param string $user_name The user's full name.
     * @return WP_User The user object.
     */
    function get_or_create_woocommerce_customer($user_email, $user_name)
    {
        // Attempt to retrieve the user by email
        $user = get_user_by('email', $user_email);

        if (!$user) {
            // User does not exist, create a new WooCommerce customer
            $user_id = wc_create_new_customer($user_email, '', wp_generate_password(), [
                'first_name' => strtok($user_name, ' '), // First part before the space
                'last_name' => substr($user_name, strpos($user_name, ' ') + 1) // Remainder after the space
            ]);

            if (is_wp_error($user_id)) {
                // Handle the error appropriately
                error_log('Failed to create user: ' . $user_id->get_error_message());
                return null;
            }

            $user = get_user_by('id', $user_id);
        }

        return $user;
    }
});
