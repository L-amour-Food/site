<?php
add_action('rest_api_init', function () {

    register_rest_route('custom/v1', '/menu', array(
        'methods' => 'GET',
        'callback' => function ($request) {

            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => 'a-la-carte',
                    ),
                ),
            );

            $products = get_posts($args);
            $product_data = array();
            setlocale(LC_TIME, 'fr_FR');
            foreach ($products as $product) {
                $product_obj = wc_get_product($product->ID);

                $time = frenchDateToTimestamp($product->post_title);
                $product_data[] = array(
                    'date' => $product->post_title,
                    'time' => $time,
                    'nom' => parse_nom($product_obj->get_short_description()),
                    'description' => parse_desc($product_obj->get_short_description()),
                    'illustration' => get_the_post_thumbnail_url($product->ID),
                    'permalink' => get_permalink($product->ID)
                );
            }

            return $product_data;
        },
    ));


    function parse_desc($desc)
    {
        $desc = str_replace('<em>', '', $desc);
        $desc = str_replace('</em>', '', $desc);
        $desc = str_replace('–', '-', $desc);
        $desc = str_replace('–', '-', $desc);
        $desc = str_replace("\n-", "\n", $desc);
        $desc = str_replace("\r", "", $desc);
        while (strstr($desc, "\n\n")) {
            $desc = str_replace("\n\n", "\n", $desc);
        }
        return $desc;
    }

    function parse_nom($nom)
    {
        $nom = trim(strip_tags($nom));
        $nom = str_replace('–', '-', $nom);
        $nom = str_replace("\n-\r", "\n", $nom);
        return $nom;
    }

    function frenchDateToTimestamp($dateString)
    {
        // Define a mapping of French month names to English
        $monthMapping = [
            'Janvier'   => 'January',
            'Février'   => 'February',
            'Mars'      => 'March',
            'Avril'     => 'April',
            'Mai'       => 'May',
            'Juin'      => 'June',
            'Juillet'   => 'July',
            'Août'      => 'August',
            'Septembre' => 'September',
            'Octobre'   => 'October',
            'Novembre'  => 'November',
            'Décembre'  => 'December',
        ];

        // Replace the French month names with their English counterparts
        foreach ($monthMapping as $french => $english) {
            $dateString = str_replace($french, $english, $dateString);
        }

        // Define the format of the provided date string
        $format = 'd F Y';

        // Create DateTime object from the given string
        $dateTime = DateTime::createFromFormat($format, $dateString);

        // Check if the creation was successful
        if (!$dateTime) return;

        // Return the timestamp
        return $dateTime->getTimestamp();
    }
});
