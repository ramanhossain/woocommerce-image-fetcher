<?php
/*
Plugin Name: WooCommerce Image Fetcher
Plugin URI: https://Sarubureau.nl
Description: Een plugin voor het automatisch ophalen en toevoegen van afbeeldingen aan WooCommerce-producten zonder afbeelding op basis van de EAN.
Version: 1.0
Author: Je Naam
Author URI: https://Sarubureau.nl
*/

// Functie om de producten uit WooCommerce te halen
function wif_get_woocommerce_products($page = 1, $per_page = 20) {
    $options = get_option('wif_settings');
    $woocommerce_url = isset($options['woocommerce_url']) ? $options['woocommerce_url'] : '';
    $consumer_key = isset($options['consumer_key']) ? $options['consumer_key'] : '';
    $consumer_secret = isset($options['consumer_secret']) ? $options['consumer_secret'] : '';

    if(empty($woocommerce_url) || empty($consumer_key) || empty($consumer_secret)) {
        return new WP_Error('missing_credentials', 'WooCommerce API credentials are missing.');
    }

    $url = $woocommerce_url . "products?per_page=$per_page&page=$page&consumer_key=$consumer_key&consumer_secret=$consumer_secret";
    
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Functie om te zoeken naar afbeeldingen via de Google Custom Search API
function wif_search_google_image($ean) {
    $options = get_option('wif_settings');
    $google_api_key = isset($options['google_api_key']) ? $options['google_api_key'] : '';
    $google_search_engine_id = isset($options['google_search_engine_id']) ? $options['google_search_engine_id'] : '';

    if(empty($google_api_key) || empty($google_search_engine_id)) {
        return new WP_Error('missing_google_credentials', 'Google API credentials are missing.');
    }

    $url = "https://www.googleapis.com/customsearch/v1?q=$ean&searchType=image&key=$google_api_key&cx=$google_search_engine_id";
    
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if (isset($data['items'][0]['link'])) {
        return $data['items'][0]['link']; // Retourneer de eerste afbeelding URL
    }
    return null; // Geen afbeelding gevonden
}

// Functie om de afbeelding toe te voegen aan een product in WooCommerce
function wif_update_product_image($product_id, $image_url) {
    $options = get_option('wif_settings');
    $woocommerce_url = isset($options['woocommerce_url']) ? $options['woocommerce_url'] : '';
    $consumer_key = isset($options['consumer_key']) ? $options['consumer_key'] : '';
    $consumer_secret = isset($options['consumer_secret']) ? $options['consumer_secret'] : '';
    
    if(empty($woocommerce_url) || empty($consumer_key) || empty($consumer_secret)) {
        return new WP_Error('missing_credentials', 'WooCommerce API credentials are missing.');
    }

    $data = [
        'images' => [
            [
                'src' => $image_url
            ]
        ]
    ];
    
    $url = $woocommerce_url . "products/$product_id?consumer_key=$consumer_key&consumer_secret=$consumer_secret";
    
    $options = [
        'http' => [
            'method'  => 'PUT',
            'header'  => "Content-type: application/json",
            'content' => json_encode($data)
        ]
    ];
    
    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    return json_decode($response, true);
}

// Functie voor de admin pagina in WordPress
function wif_admin_page() {
    if (isset($_POST['action']) && $_POST['action'] == 'get_images') {
        // Verkrijg een lijst van producten en verwerk afbeeldingen
        $products = wif_get_woocommerce_products(1, 20); // Haal de eerste 20 producten op
        if (is_wp_error($products)) {
            echo "<div class='error'><p>" . $products->get_error_message() . "</p></div>";
            return;
        }

        $successful_updates = 0;
        $updated_eans = [];
        
        foreach ($products as $product) {
            // Controleer of het product al een afbeelding heeft
            if (empty($product['images'])) {
                // Haal de EAN op uit de meta_data (key = _alg_ean)
                $ean = null;
                if (isset($product['meta_data']) && is_array($product['meta_data'])) {
                    foreach ($product['meta_data'] as $meta) {
                        if ($meta['key'] == '_alg_ean') {
                            $ean = $meta['value'];
                            break;
                        }
                    }
                }
                
                if ($ean) {
                    $image_url = wif_search_google_image($ean);
                    if ($image_url) {
                        wif_update_product_image($product['id'], $image_url);
                        $successful_updates++;
                        $updated_eans[] = $ean;
                    }
                }
            }
        }
        
        echo "<div class='updated'><p>Aantal succesvolle updates: $successful_updates</p></div>";
        echo "<h3>Aangepaste EAN's:</h3><ul>";
        foreach ($updated_eans as $ean) {
            echo "<li>$ean</li>";
        }
        echo "</ul>";
    } else {
        // Stap 1: Toon de lijst van EAN's voor producten zonder afbeelding
        $products = wif_get_woocommerce_products(1, 20); // Haal de eerste 20 producten op
        echo "<h2>Stap 1: Lijst van EAN's (Producten zonder afbeelding)</h2>";
        echo "<table class='widefat'><tr><th>Product ID</th><th>EAN Nummer</th></tr>";
        
        foreach ($products as $product) {
            // Controleer of het product al een afbeelding heeft
            if (empty($product['images'])) {
                $ean = 'N/A'; // Default waarde als er geen EAN is
                if (isset($product['meta_data']) && is_array($product['meta_data'])) {
                    foreach ($product['meta_data'] as $meta) {
                        if ($meta['key'] == '_alg_ean') {
                            $ean = $meta['value'];
                            break;
                        }
                    }
                }
                echo "<tr><td>{$product['id']}</td><td>$ean</td></tr>";
            }
        }
        
        echo "</table>";
        // Stap 2: Knop om afbeeldingen op te halen en toe te voegen
        echo "<form method='POST' action=''>
                <input type='hidden' name='action' value='get_images'>
                <input type='submit' value='Haal afbeeldingen op en voeg toe'>
              </form>";
    }
}

// Functie voor de instellingenpagina van de plugin
function wif_settings_page() {
    ?>
    <div class="wrap">
        <h1>WooCommerce Image Fetcher Instellingen</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wif_settings_group');
            do_settings_sections('wif-settings');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">WooCommerce API URL</th>
                    <td><input type="text" name="wif_settings[woocommerce_url]" value="<?php echo esc_attr(get_option('wif_settings')['woocommerce_url']); ?>" class="regular-text"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Consumer Key</th>
                    <td><input type="text" name="wif_settings[consumer_key]" value="<?php echo esc_attr(get_option('wif_settings')['consumer_key']); ?>" class="regular-text"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Consumer Secret</th>
                    <td><input type="text" name="wif_settings[consumer_secret]" value="<?php echo esc_attr(get_option('wif_settings')['consumer_secret']); ?>" class="regular-text"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Google API Key</th>
                    <td><input type="text" name="wif_settings[google_api_key]" value="<?php echo esc_attr(get_option('wif_settings')['google_api_key']); ?>" class="regular-text"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Google Search Engine ID</th>
                    <td><input type="text" name="wif_settings[google_search_engine_id]" value="<?php echo esc_attr(get_option('wif_settings')['google_search_engine_id']); ?>" class="regular-text"></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Functie om de instellingen in WordPress te registreren
function wif_register_settings() {
    register_setting('wif_settings_group', 'wif_settings');
    add_settings_section('wif_main_section', 'Voer de API gegevens in.', null, 'wif-settings');
}

add_action('admin_init', 'wif_register_settings');

// Voeg een menu-item toe voor de plugin
function wif_add_admin_menu() {
    add_menu_page(
        'WooCommerce Image Fetcher', // Pagina titel
        'Image Fetcher', // Menu titel
        'manage_options', // Gebruikersrol
        'woocommerce-image-fetcher', // Menu slug
        'wif_admin_page', // Functie die de pagina toont
        'dashicons-image-crop' // Icoon
    );
    
    add_submenu_page(
        'woocommerce-image-fetcher', // Bovenliggende menu
        'Instellingen', // Pagina titel
        'Instellingen', // Menu titel
        'manage_options', // Gebruikersrol
        'wif-settings', // Menu slug
        'wif_settings_page' // Functie die de instellingenpagina toont
    );
}

add_action('admin_menu', 'wif_add_admin_menu');
