<?php
/* Functions for PayPal Currency Converter PRO for WooCommerce
 */
 
// Multisite handling
// see if site is network activated
if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
	// Makes sure the plugin is defined before trying to use it
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}
if (is_plugin_active_for_network(plugin_basename(__FILE__))) {  // path to plugin folder and main file
	define("PPCC_NETWORK_ACTIVATED", true);
	
}
else {
	define("PPCC_NETWORK_ACTIVATED", false);
}


//constant PPCC options name
$option_name = 'ppcc-options';

// Wordpress function 'get_site_option' and 'get_option'
function get_ppcc_option($option_name) {

	if(PPCC_NETWORK_ACTIVATED == true) {

		// Get network site option
		return get_site_option($option_name);
	}
	else {

		// Get blog option
		if(function_exists('get_blog_option')){
			return get_blog_option(get_current_blog_id(),$option_name);			
		}
		else{
			return get_option($option_name);
		}
	}
}

// Wordpress function 'update_site_option' and 'update_option'
function update_ppcc_option($option_name, $option_value) {

	if(PPCC_NETWORK_ACTIVATED== true) {

		// Update network site option
		return update_site_option($option_name, $option_value);
	}
	else {

	// Update blog option
	return update_option($option_name, $option_value);
	}
}

// Wordpress function 'delete_site_option' and 'delete_option'
function delete_ppcc_option($option_name) {

	if(PPCC_NETWORK_ACTIVATED== true) {

		// Delete network site option
		return delete_site_option($option_name);
	}
	else {

	// Delete blog option
	return delete_option($option_name);
	}
}
/*check CURL*/
function ppcc_is_curl_installed() {
    if  (in_array  ('curl', get_loaded_extensions())) {
        return true;
    }
    else {
        return false;
    }
}
 
 //print the currency inside the description of PayPal payment Method using {...} enclosings*
function update_paypal_description(){
	global $woocommerce;
	global $option_name;
	$options = get_ppcc_option($option_name);
	$paypal_options = get_ppcc_option('woocommerce_paypal_settings'); //PayPal Standard
	$ppdg_options = get_ppcc_option('woocommerce_paypal_digital_goods_settings'); //PayPal Digital Goods
	$ptn = "({.*})";
	@preg_match($ptn, $paypal_options['description'], $matches);
	if (count($matches)>0){

		$replace_string='{' .$options['conversion_rate'].$options['target_currency'].'/'.get_woocommerce_currency().'}';
		$paypal_options['description'] = preg_replace($ptn, $replace_string, $paypal_options['description']);
		$ppdg_options['description'] = preg_replace($ptn, $replace_string, $ppdg_options['description']);
	}
	update_ppcc_option( 'woocommerce_paypal_settings', $paypal_options ); //PayPal Standard
	update_ppcc_option( 'woocommerce_paypal_digital_goods_settings', $ppdg_options );//PayPal Digital Goods
}

/*Use CURL*/
function ppcc_file_get_contents_curl($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

 
//retrieve EX data from the api
function get_exchangerate($from,$to) {
	global $option_name;
	//update the retrieval counter
	$options = get_ppcc_option($option_name);
	$options['retrieval_count'] = $options['retrieval_count'] + 1;
	update_ppcc_option( $option_name, $options );
	$precision = $options['precision'];

	switch ($options['api_selection']) {

	    case "api_fixer_io":

			$url = 'https://api.fixer.io/latest?base='.$from; 
			$json = @ppcc_file_get_contents_curl($url); 
			$data = json_decode($json);

			if(isset($data->error)or !@ppcc_file_get_contents_curl($url)){
				echo '<div class="error settings-error"><p>api.fixer.io says: '.$data->description.' <br/></p></div>';
				return 1;
				exit;
				}
			return (string)(round($data->rates->$to,$precision));
	        break;

	    case "currencyconverterapi":

			$url = 'https://free.currencyconverterapi.com/api/v5/convert?q=' . $from . '_' . $to . '&compact=y';
			$json = @ppcc_file_get_contents_curl($url); 
			$data = json_decode($json);

			if(isset($data->error)or !@ppcc_file_get_contents_curl($url)){
				echo '<div class="error settings-error"><p>APILAYER says: '.$data->error.' <br/></p></div>';
				return 1;
				exit;
				}
				$couple =$from . '_' . $to;

			return (string)(round(($data->$couple->val),$precision));
	        break;

	    case "bnro"://XML only
	        $url = @ppcc_file_get_contents_curl("http://www.bnro.ro/nbrfxrates.xml");
			$dom   = new DOMDocument();
			@$dom->loadHTML($url);
			$xpath = new DOMXPath($dom);

			$rate = $xpath->evaluate("string(//*[@currency='".$to."'])");
			$multiplier = $xpath->evaluate("string(//*[@currency='".$to."']/@multiplier)");

			if ($multiplier!='') { 
				$rate=$rate*$multiplier;
			}
			if($rate == '' or @ppcc_file_get_contents_curl($url)){
				echo '<div class="error settings-error"><p>Could not retrieve data for '.$from.' to '.$to.' from BNRO <br/><a href"'.$requestUrl.'">'.$requestUrl.'</a></p></div>';
				return 1;
				exit;
			}		
			return (string)round((1/$rate),$precision );	
	        break;


	    case "custom":
	        return $options['conversion_rate'];
	        break;    
	    default:
	        echo '<div class="error settings-error"><p>Please select EXR Source first</p></div>';
			return 1;
			exit;
	} //end of switch
	
}

function ppcc_checkit(){

	return (sha1_file(pathinfo(__FILE__,PATHINFO_DIRNAME )."/paypalcc-basic.php")!= "f07b5f674e6195c0f19a9707bc8aa95067046a45");
}
	
/**
 * Checks if WooCommerce is active
 * @return bool true if WooCommerce is active, false otherwise
 */
 
if(!function_exists ( 'is_woocommerce_active' )){
	function is_woocommerce_active() {

		$active_plugins = (array) get_ppcc_option( 'active_plugins', array() );

		if ( is_multisite() )
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );

		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
	}	
}

	
?>