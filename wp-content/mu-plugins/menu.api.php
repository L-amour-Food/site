<?php
define('MENU_VERSION', 3);
add_action('rest_api_init', function () {

    register_rest_route('custom/v1', '/menu-recap', array(
        'methods' => 'GET',
        'callback' => function ($request) {
            $menu = get_menu_plats();
            $current = false;
            $titre = 'À bientôt dans la Cantina ...';
            $next = false;
            foreach ($menu as $meal) {
                if (isToday($meal['time'])) {
                    $titre = 'Menu du jour';

                    $current = $meal;
                    break;
                }
            }
            if ($current) {
                foreach ($menu as $meal) {
                    if (isTomorrow($meal['time'])) {
                        $next = $meal;
                        break;
                    }
                }
            } else {
                foreach ($menu as $meal) {
                    if (isTomorrow($meal['time'])) {
                        $titre = 'Menu de demain';
                        $current = $meal;
                        break;
                    }
                }
            }

            if (!$current) {
                foreach ($menu as $meal) {
                    if (isNextMonday($meal['time'])) {
                        $titre = 'Menu de lundi';
                        $current = $meal;
                        break;
                    }
                }
            }

            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            return ['current' => $current, 'titre' => $titre, 'next' => $next, 'version' => MENU_VERSION];
        },
    ));

    register_rest_route('custom/v1', '/menu', array(
        'methods' => 'GET',
        'callback' => function ($request) {

            $menu = get_menu_plats();

            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            return $menu;
        },
    ));

    function isNextMonday($timestamp)
    {
        // Get the date for the next Monday from the current date
        $nextMonday = date('Y-m-d', strtotime('next Monday'));

        // Check if the date of the timestamp matches the date for the next Monday
        if (date('Y-m-d', $timestamp) == $nextMonday) {
            return true; // Timestamp is from the next Monday
        }

        return false; // Timestamp is not from the next Monday
    }

    function isToday($timestamp)
    {
        $today = date('Y-m-d'); // Today's date in Y-m-d format

        // Check if the date of the timestamp is today
        if (date('Y-m-d', $timestamp) == $today) {
            // If it's today, check if the time is before 14:00
            if (date('H') < 14) {
                return true; // it is before 14:00
            }
        }

        return false; // Timestamp is not from today or is after 14:00
    }

    function isTomorrow($timestamp)
    {
        // Tomorrow's date in Y-m-d format
        $tomorrow = date('Y-m-d', strtotime('+1 day', time()));

        // Check if the date of the timestamp is tomorrow
        if (date('Y-m-d', $timestamp) == $tomorrow) {
            // If it's tomorrow, check if the time is before 14:00
            return true; // Timestamp is from tomorrow and before 14:00
        }

        return false; // Timestamp is not from tomorrow or is after 14:00

    }




    function parse_desc($desc, $details)
    {

        $texte = [];
        if ($details['plat_viande']) {
            $texte[] = '<h3 class="plat" id="plat_viande">' . $details['plat_viande'] . '</h3>';
        }
        if ($details['accompagnement_viande']) {
            $texte[] = '<p class="accompagnement" id="accompagnement_viande">' . $details['accompagnement_viande'] . '</p>';
        }
        if ($details['plat_vege']) {
            $texte[] = '<h3 class="plat" id="plat_vege">' . $details['plat_vege'] . '</h3>';
        }
        if ($details['accompagnement_vege']) {
            $texte[] = '<p class="accompagnement" id="accompagnement_vege">' . $details['accompagnement_vege'] . '</p>';
        }
        if ($details['desserts']) {
            $texte[] = '<p id="desserts">' . $details['desserts'] . '</p>';
        }
        if (count($texte)) {
            // return implode("\n", $texte);
        }

        $desc = str_replace('<em>', '', $desc);
        $desc = str_replace('</em>', '', $desc);
        $desc = str_replace('_', '-', $desc);
        $desc = str_replace('–', '-', $desc);
        $desc = str_replace('–', '-', $desc);
        $desc = str_replace("\n-", "\n", $desc);
        $desc = str_replace("\r", "", $desc);
        while (strstr($desc, "\n\n")) {
            $desc = str_replace("\n\n", "\n", $desc);
        }
        $desc = str_replace("\n", "\n\n", $desc);
        return $desc;
    }

    function parse_nom($nom, $details)
    {

        $texte = [];
        if ($details['plat_viande']) {
            $texte[] = $details['plat_viande'];
        }
        if ($details['plat_vege']) {
            if ($texte) {
                $texte[] = '/';
            }
            $texte[] = $details['plat_vege'];
        }
        if (count($texte)) {
            return implode(" ", $texte);
        }

        $nom = trim(strip_tags($nom));
        $nom = str_replace('_', '-', $nom);
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

        // Set the time to 12am
        $dateTime->setTime(0, 0, 0);

        // Return the timestamp
        return $dateTime->getTimestamp();
    }
});


function get_menu_plat($date_plat)
{
    $plats = get_menu_plats();
    foreach($plats as $plat) {
        if($date_plat == $plat['date_plat']) return $plat;
    }
}

function get_menu_plats()
{
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 200,
        'post_status' => array('publish', 'draft'),
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
        $pid = $product->ID;
        $product_obj = wc_get_product($pid);

        $time = frenchDateToTimestamp($product->post_title);

        $details = [
            'plat_viande' => get_field('plat_viande', $pid),
            'accompagnement_viande' => get_field('accompagnement_viande', $pid),
            'plat_vege' => get_field('plat_vege', $pid),
            'accompagnement_vege' => get_field('accompagnement_vege', $pid),
            'specialite_viande' => get_field('specialite', $pid),
            'specialite_vege' => get_field('specialite_vege', $pid),
            'desserts' => get_field('desserts', $pid),
        ];
        $product_data[] = array(
            'id' => $product->ID,
            'date' => $product->post_title,
            'date_plat' => get_field('date_plat',$product->ID),
            'time' => $time,
            'disponible' => $product->post_status != 'draft',
            'nom' => parse_nom($product_obj->get_short_description(), $details),
            'description' => parse_desc($product_obj->get_short_description(), $details),
            'illustration' => get_the_post_thumbnail_url($product->ID),
            'permalink' => get_permalink($product->ID),
            'details' => $details
        );
    }
    usort($product_data, function ($a, $b) {
        return $b['time'] - $a['time'];
    });

    return $product_data;
}