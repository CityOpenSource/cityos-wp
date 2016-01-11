<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://ingmmo.com
 * @since      1.0.0
 *
 * @package    Cityos
 * @subpackage Cityos/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cityos
 * @subpackage Cityos/public
 * @author     Marco Montanari <marco.montanari@gmail.com>
 */
class Cityos_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cityos_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cityos_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cityos-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cityos_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cityos_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cityos-public.js', array( 'jquery' ), $this->version, false );

	}

	public function register_shortcodes(){
      function cityos_map($atts){
				//[cityos map="mappina" filters="" secret="<codice>"]
				//[cityos map="mappina" mapper="marco.montanari" filters="" secret="<codice>"]
        $a = shortcode_atts( array(
					"height" => 300,
					"type" => "school",
					"secret" => "",
					"filters" => "",
					"map" => "",
					"mapper" => ""
        ), $atts );

				$data = file_get_contents("http://cityopensource.com/api/v1/spaces/".$a["map"]."?secret=".$a["secret"]);
				$data = json_decode($data);

				$ret = "";
				$ret .= "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/leaflet.css' />\n";
				$ret .= "<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.css' rel='stylesheet' />\n";
				$ret .= "<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.Default.css' rel='stylesheet' />\n";
				$ret .= "<script src='http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js'></script>";
				$ret .= "<div id='map' style='height:".$a["height"]."px;'></div>\n";
				$ret .= "<script>\n";
				$ret .= "jQuery(function(){\n";
				$ret .= "\tvar map = L.map('map');\n";
				$ret .= "\tvar layer = L.tileLayer('".$data->background."', {attribution: '".$data->attribution."'});\n";
				$ret .= "\tmap.addLayer(layer);\n";
				$ret .= "\tmap.setView([".$data->center->coordinates[1].",".$data->center->coordinates[0]."], ".$data->zoom.");\n";
				$ret .= "\tvar locations='http://cityopensource.com/api/v1/spaces/".$a["map"]."?secret=".$a["secret"]."'\n";
				$ret .= "});\n";
				$ret .= "</script>";

				return $ret;
      }

      function cityos_map_contributors($atts){
				//[cityos_contributors map="mappina" secret="<codice>" num="6"]
				$a = shortcode_atts( array(
					"mode" => "default",
					"height" => 300,
					"type" => "school",
					"secret" => "",
					"map" => "",
					"mapper" => ""
				), $atts );

				$data = file_get_contents("http://cityopensource.com/api/v1/spaces/".$a["map"]."/contributors?secret=".$a["secret"]);
				return $data;
				//$data = json_decode($data);

      }

      function cityos_items($atts){
				//[cityos_items map="mappina" secret="<codice>" num="5"]
				$a = shortcode_atts( array(
					"secret" => "",
					"map" => "",
					"num" => 5
				), $atts );
				$data = file_get_contents("http://cityopensource.com/api/v1/spaces/".$a["map"]."/locations/latest?secret=".$a["secret"]."&a=".$a["num"]);
				return $data;
      }

      function cityos_activity($atts){
				//[cityos_activity map="mappina" secret="<codice>" num="20"]
				$a = shortcode_atts( array(
					"map" => "",
					"secret" => "",
					"num" => 20,
				), $atts );
				$data = file_get_contents("http://cityopensource.com/api/v1/spaces/".$a["map"]."/activities?secret=".$a["secret"]."&a=".$a["num"]);
				return $data;

      }

			add_shortcode("cityos", "cityos_map");
			add_shortcode("cityos_contributors", "cityos_map_contributors");
			add_shortcode("cityos_items", "cityos_items");
			add_shortcode("cityos_activity", "cityos_activity");
    }

}
