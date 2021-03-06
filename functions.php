<?php

/* CONFIG */

add_theme_support( 'post-thumbnails' );

function my_acf_init() {
    acf_update_setting('google_api_key', 'AIzaSyBBiou-2FJn9GIIm2v-QBdLNUT47cX5kEg');
}
add_action('acf/init', 'my_acf_init');

function mail_set_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','mail_set_content_type' );

add_filter( 'wp_is_application_passwords_available', '__return_false' );

/* JS et CSS */
	
function mlbr_register_scripts() {
	
	$theme_version = '1.1';
	
	wp_enqueue_style( 'maincss', get_template_directory_uri() . '/assets/css/main.css', array(), $version, 'all');
    
	wp_enqueue_script('modernizr', get_template_directory_uri().'/assets/js/vendor/modernizr-3.11.2.min.js', [], $theme_version, true);
	wp_enqueue_script('jquery', get_template_directory_uri().'/assets/js/vendor/jquery-3.6.0.min.js', [], $theme_version, true);
	wp_enqueue_script('pluggins', get_template_directory_uri() . '/assets/js/plugins.js', [], $theme_version, true);
	wp_enqueue_script('mainjs', get_template_directory_uri() . '/assets/js/main.js', [], $theme_version, true);
	wp_script_add_data('mainjs', 'defer', true);

	wp_localize_script('mainjs', 'ajaxurl', array(admin_url('admin-ajax.php')));
	
}
add_action('wp_enqueue_scripts', 'mlbr_register_scripts');

/* CPT */

require get_template_directory() . '/cpt/store.php';

/* Auto add page when add user */

function mlbr_registration_save( $user_id ) {

        // Create post object
        $my_post = array(
          'post_title' => $_POST['last_name'],
  		  'post_type' => 'librairie',
          'post_author' => $user_id
        );

        // Insert the post into the database
        wp_insert_post( $my_post );

}
add_action( 'user_register', 'mlbr_registration_save', 10, 1 );

/* hide menu item for autors */

function mlbr_hideitems() {
	
	$user = wp_get_current_user();
	$roles = ( array ) $user->roles;
	
	if ($roles[0]=='author') {	
   		
		remove_menu_page( 'edit.php' );
    	remove_menu_page( 'tools.php' );
    	remove_menu_page( 'upload.php' );
	
	}
	
}
add_action('admin_head', 'mlbr_hideitems');


/* Rewrite url */

function mlbr_rewrite_url() {
    
    add_rewrite_tag( '%zone%','([^&]+)' );
    add_rewrite_tag( '%idzone%','([^&]+)' );
    
    add_rewrite_rule(
      'nos-librairies-partenaire/([^/]+)/([^/]+)',
      'index.php?pagename=nos-librairies-partenaire&zone=$matches[1]&idzone=$matches[2]',
      'top'
    );
}
add_action( 'init', 'mlbr_rewrite_url' );

function add_custom_query_var( $vars ){
  	$vars[] = "zone";
	$vars[] = "idzone";
  	return $vars;
}
add_filter( 'query_vars', 'add_custom_query_var' );

/* Custom login page */

function my_login_stylesheet() {
 wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/login-style.css' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );


/* AUTOCOMPLETE*/

add_action( 'wp_ajax_autocomplete', 'mlbr_autocomplete' );
add_action( 'wp_ajax_nopriv_autocomplete', 'mlbr_autocomplete' );


function mlbr_autocomplete () {
	
	$val = $_POST['val'];
	
	if ( is_numeric($val) ) :
	
		$args = array(
			'post_type' => 'librairie',
			'post_status' => 'publish',
			'posts_per_page' => '-1',
			'meta_query' => array(
				array(
					'key'     => 'adresse',
					'value'   => $val,
					'compare' => 'LIKE',
				),
			),
			'orderby' => 'cp',
			'order' => 'ASC'
		);

		$qv = new WP_Query( $args );
	
		$villes = array();

		if ( $qv->have_posts() ) :
			
			$html = '<ul>';

			while ( $qv->have_posts() ) {

				$qv->the_post();
				
				$label = get_field('adresse')['post_code'].' - '.get_field('adresse')['city'];

				if ( !in_array($label,$villes) ) {
					$villes[] = $label;
					$name = str_replace(strtolower($val),'<mark>'.strtolower($val).'</mark>',$label);
					$html .= '<li>'.$name.'</li>';
				}
			}
	
			$html .= '</ul>';

		else:
			$html = '<ul><span>Pas de magasin pour '.$val.'</span></ul>';
		endif;
		
	else :
		$args = array(
			'post_type' => 'librairie',
			'post_status' => 'publish',
			'posts_per_page' => '-1',
			'meta_query' => array(
				array(
					'key'     => 'adresse',
					'value'   => $val,
					'compare' => 'LIKE',
				),
			),
		);

		$qv = new WP_Query( $args );
	
		$villes = array();

		if ( $qv->have_posts() ) :
			
			$html = '<ul>';

			while ( $qv->have_posts() ) {

				$qv->the_post();

				if ( !in_array(get_field('adresse')['city'],$villes) ) {
					$villes[] = get_field('adresse')['city'];
					$name = str_replace(strtolower($val),'<mark>'.strtolower($val).'</mark>',strtolower(get_field('adresse')['city']));
					$html .= '<li>'.$name.'</li>';
				}
			}
	
			$html .= '</ul>';

		else:
			$html = '<ul><span>Pas de magasin ?? '.$val.'</span></ul>';
		endif;
		
	endif;
	
	if ($val === '') {
		$html = '';
	}
	
	echo $html;
	
	wp_die();
						
}


function getZoneName($zone,$name) {
	
	switch ($zone) {
		case 'region' :
			switch ($name) {
				case '1':
					$name = 'Auvergne-Rh??ne-Alpes';
					break;
				case '2':
					$name = 'Bourgogne-Franche-Comt??';
					break;
				case '3':
					$name = 'Bretagne';
					break;
				case '4':
					$name = 'Centre-Val de Loire';
					break;
				case '5':
					$name = 'Corse';
					break;
				case '6':
					$name = 'Grand-Est';
					break;
				case '7':
					$name = 'Hauts-de-France';
					break;
				case '8':
					$name = '??le-de-France';
					break;
				case '9':
					$name = 'Normandie';
					break;
				case '10':
					$name = 'Nouvelle-Aquitaine';
					break;
				case '11':
					$name = 'Occitanie';
					break;
				case '12':
					$name = "Pays de la Loire";
					break;
				case '13':
					$name = 'Provence-Alpes-C??te d\'Azur';
					break;
				case '14':
					$name = 'Guadeloupe';
					break;
				case '15':
					$name = 'Martinique';
					break;
				case '16':
					$name = 'Guyane';
					break;
				case '17':
					$name = 'La R??union';
					break;
				case '18':
					$name = 'Mayotte';
					break;
				case '19':
					$name = 'Belgique';
					break;
			}
			break;
		case 'departement' :
			$cd = substr($name,0,2);
			$name = nameDep($cd);
			break;
		case 'ville' :
			$name = urldecode($name);
			break;
		case 'store' :
			$name = urldecode($name);
			break;
	}		

	return $name;
}

function cleanName ($str) {
	
	$str = htmlentities($str, ENT_NOQUOTES, 'utf-8');
	$str = preg_replace('#&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring);#', '\1', $str);
	$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
	$str = preg_replace('#&[^;]+;#', '', $str);
	//$str = str_replace( ' ', '-', $str);
	
	return strtolower($str);
	
}

function nameDep($cp) {
    $nom_dept = array (
		"01" => "Ain",
		"02" => "Aisne",
		"03" => "Allier",
		"04" => "Alpes-de-Haute Provence",
		"05" => "Hautes-Alpes",
		"06" => "Alpes Maritimes",
		"07" => "Ard??che",
		"08" => "Ardennes",
		"09" => "Ari??ge",
		"10" => "Aube",
		"11" => "Aude",
		"12" => "Aveyron",
		"13" => "Bouches-du-Rh??ne",
		"14" => "Calvados",
		"15" => "Cantal",
		"16" => "Charente",
		"17" => "Charente-Maritime",
		"18" => "Cher",
		"19" => "Corr??ze",
		"20" => "Corse",
		"21" => "C??te d'Or",
		"22" => "C??tes d'Armor",
		"23" => "Creuse",
		"24" => "Dordogne",
		"25" => "Doubs",
		"26" => "Dr??me",
		"27" => "Eure",
		"28" => "Eure-et-Loire",
		"29" => "Finist??re",
		"30" => "Gard",
		"31" => "Haute-Garonne",
		"32" => "Gers",
		"33" => "Gironde",
		"34" => "H??rault",
		"35" => "Ille-et-Vilaine",
		"36" => "Indre",
		"37" => "Indre-et-Loire",
		"38" => "Is??re",
		"39" => "Jura",
		"40" => "Landes",
		"41" => "Loir-et-Cher",
		"42" => "Loire",
		"43" => "Haute-Loire",
		"44" => "Loire-Atlantique",
		"45" => "Loiret",
		"46" => "Lot",
		"47" => "Lot-et-Garonne",
		"48" => "Loz??re",
		"49" => "Maine-et-Loire",
		"50" => "Manche",
		"51" => "Marne",
		"52" => "Haute-Marne",
		"53" => "Mayenne",
		"54" => "Meurthe-et-Moselle",
		"55" => "Meuse",
		"56" => "Morbihan",
		"57" => "Moselle",
		"58" => "Ni??vre",
		"59" => "Nord",
		"60" => "Oise",
		"61" => "Orne",
		"62" => "Pas-de-Calais",
		"63" => "Puy-de-D??me",
		"64" => "Pyren??es-Atlantiques",
		"65" => "Hautes-Pyren??es",
		"66" => "Pyren??es-Orientales",
		"67" => "Bas-Rhin",
		"68" => "Haut-Rhin",
		"69" => "Rh??ne",
		"70" => "Haute-Sa??ne",
		"71" => "Sa??ne-et-Loire",
		"72" => "Sarthe",
		"73" => "Savoie",
		"74" => "Haute-Savoie",
		"75" => "Paris",
		"76" => "Seine-Maritime",
		"77" => "Seine-et-Marne",
		"78" => "Yvelines",
		"79" => "Deux-S??vres",
		"80" => "Somme",
		"81" => "Tarn",
		"82" => "Tarn-et-Garonne",
		"83" => "Var",
		"84" => "Vaucluse",
		"85" => "Vend??e",
		"86" => "Vienne",
		"87" => "Haute-Vienne",
		"88" => "Vosges",
		"89" => "Yonne",
		"90" => "Territoire de Belfort",
		"91" => "Essonne",
		"92" => "Hauts-de-Seine",
		"93" => "Seine-Saint-Denis",
		"94" => "Val-de-Marne",
		"95" => "Val-d'Oise"
	);

    return $nom_dept[$cp];
	
}

// dashborad

function wpdocs_add_dashboard_widgets() {
    wp_add_dashboard_widget( 'dashboard_widget1', 'Bienvenue sur votre espace de gestion', 'dashboard_widget_function1' );
    wp_add_dashboard_widget( 'dashboard_widget2', 'Besoin d\'aide', 'dashboard_widget_function2' );
    wp_add_dashboard_widget( 'dashboard_widget3', 'T??l??chargements', 'dashboard_widget_function3' );
}
add_action( 'wp_dashboard_setup', 'wpdocs_add_dashboard_widgets' );
 
function dashboard_widget_function1( $post, $callback_args ) {
    echo 'Pour administrer votre page, <a href="https://www.nuitonepiece.com/wp-admin/edit.php?post_type=librairie">cliquez ici</a>';
}
function dashboard_widget_function2( $post, $callback_args ) {
    echo '<a href="#">Suivez le tuto</a> pour bien remplir votre page';
}
function dashboard_widget_function3( $post, $callback_args ) {
    echo '<ul><li><a href="#">Kit d\'animation</a></li><li><a href="#">Kit de communication</a></li><li><a href="#">Kit d\'exposition</a></li></ul>';
}