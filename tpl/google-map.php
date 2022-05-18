<?php
/**
 * Template Name: Librairies
 * Template Post Type: page
 *
 * @package WordPress
 */

get_header();

?>
	<?php if ($zone = $wp_query->query_vars['zone']) : ?>
		<?php $idzone = $wp_query->query_vars['idzone']; ?>
		<?php if ($idzone === 'geolocalisation') :?>
			<section id="section3" class="container">
				<div class="row">
					<div class="col-12">
						<h1>Librairies autour de vous</h1>
					</div>
					<div class="col-12">
						<div class="position-sticky" style="top: 121px">
							<div id="map" class="map"></div>
						</div>
					</div>
					<div class="col-12 position-relative">
						<div class="left">
							<?php
								$args = array(
									'post_type' => 'librairie',
									'post_status' => 'publish',
									'posts_per_page' => '-1'
								);

								$query = new WP_Query( $args );

								global $markers;
								$markers = array();
								$i = 0;

								if ( $query->have_posts() ) :
									while ( $query->have_posts() ) {
										$query->the_post();
										$markers[$i]['client'] = get_field('nucli');
										$location = get_field('adresse');
										$markers[$i]['nom'] = get_the_title();
										$markers[$i]['rue'] = $location['street_number'] . ' ' .$location['street_name'];
										$markers[$i]['cp'] = $location['post_code'];
										$markers[$i]['localite'] = $location['city'];
										$markers[$i]['latitude'] = $location['lat'];
										$markers[$i]['longitude'] = $location['lng'];
										$markers[$i]['link'] = get_permalink();
										$i++;
									}
								endif;

								wp_reset_postdata();

							?>					
						</div>
					</div>
				</div>
			</section>
		<?php else :?>
			<section id="section3" class="container">
				<div class="row">
					<div class="col-12">
						
						<?php if ( $zone !== 'store' ) : ?>
							<?php if ( $zone === 'ville' ) { echo '<a href="#" class="link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="10"><path d="M115.53,256a25,25,0,0,1,7.33-17.67l231-231a25,25,0,0,1,35.35,35.36L175.89,256,389.22,469.32a25,25,0,0,1-35.35,35.36l-231-231A25.06,25.06,0,0,1,115.53,256Z"/></svg>Changer de ville</a>'; } ?>
							<?php if ( $zone === 'departement' || $zone === 'region' ) { echo '<a href="#" class="link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="10"><path d="M115.53,256a25,25,0,0,1,7.33-17.67l231-231a25,25,0,0,1,35.35,35.36L175.89,256,389.22,469.32a25,25,0,0,1-35.35,35.36l-231-231A25.06,25.06,0,0,1,115.53,256Z"/></svg>Nouvelle recherche</a>'; } ?>
							<h1>Les librairies partenaires pour <?= mb_convert_case(getZoneName($zone,$idzone), MB_CASE_TITLE, "UTF-8"); ?></h1>
						<?php endif; ?>
					</div>
					<div class="col-12">
						<div class="position-sticky" style="top: 121px">
							<div id="map" class="map"></div>
						</div>
					</div>
					<div class="col-12 position-relative">
						<div class="left">

							<?php
							if ( $zone === 'ville' ) :

								$idzone = urldecode($idzone);
								$args = array(
									'post_type' => 'librairie',
									'post_status' => 'publish',
									'posts_per_page' => '-1',
									'meta_query' => array(
										array(
											'key'     => 'adresse',
											'value'   => $idzone,
											'compare' => 'LIKE',
										),
									),

								);

								$query = new WP_Query( $args );

								global $markers;
								$markers = array();
								$i = 0;

								if ( $query->have_posts() ) :
									while ( $query->have_posts() ) {
										$query->the_post();
										$markers[$i]['client'] = get_field('nucli');
										$location = get_field('adresse');
										$markers[$i]['nom'] = get_the_title();
										$markers[$i]['rue'] = $location['street_number'] . ' ' .$location['street_name'];
										$markers[$i]['cp'] = $location['post_code'];
										$markers[$i]['localite'] = $location['city'];
										$markers[$i]['latitude'] = $location['lat'];
										$markers[$i]['longitude'] = $location['lng'];
										$markers[$i]['link'] = get_permalink();
										$i++;
									}
								endif;

								wp_reset_postdata();

								if ($markers) :
									foreach ($markers as $marker) :
										?>
										<div class="infostore"><h3><?= $marker['nom'];?></h3>
											<p><?= $marker['rue'];?><br /><?= $marker['cp'];?> <?= $marker['localite'];?></p>
											<p><a href="<?= get_permalink();?>librairie/<?= $marker['client'];?>/<?= cleanName($marker['localite']);?>-<?= cleanName($marker['nom']);?>">Plus d'info</a></p>
										</div>
										<?php
									endforeach;
								endif;

							else :

								if ( $zone == 'region' ) :
							echo $idzone;
										$args = array(
											'post_type' => 'librairie',
											'post_status' => 'publish',
											'posts_per_page' => '-1',
											'meta_query' => array(
												array(
													'key'     => 'region',
													'value'   => $idzone,
													'compare' => '=',
												),
											),

										);
								else :

									$args = array(
										'post_type' => 'librairie',
										'post_status' => 'publish',
										'posts_per_page' => '-1',
										'meta_query' => array(
											array(
												'key'     => 'departement',
												'value'   => $idzone,
												'compare' => '=',
											),
										),

									);
								endif;

								$query = new WP_Query( $args );

								$villes = array();

								global $markers;
								$markers = array();
								$i = 0;

								if ( $query->have_posts() ) :
									while ( $query->have_posts() ) {
										$query->the_post();
										$location = get_field('adresse');
										$markers[$i]['nom'] = get_the_title();
										$markers[$i]['rue'] = $location['street_number'] . ' ' .$location['street_name'];
										$markers[$i]['cp'] = $location['post_code'];
										$markers[$i]['localite'] = $location['city'];
										$markers[$i]['latitude'] = $location['lat'];
										$markers[$i]['longitude'] = $location['lng'];
										$markers[$i]['link'] = get_permalink();
										if ( !in_array($location['city'],$villes) ) {
											$villes[] = $location['city']; 
										}
										$i++;
									}
								endif;

								wp_reset_postdata();

								if ($villes) :
									echo '<ul>';
									foreach ($villes as $ville) {
										echo '<li><a href="'.get_permalink().'ville/'.cleanName($ville).'" data-type="ville">'.$ville.'</a></li>';
									}
									echo '</ul>';
								endif;
							endif;
							?>					
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>
	<?php else : ?>
		<section id="section1">
			<div class="container-fluid">
				<div class="row justify-content-center">
					<div class="col-md-6 text-center">
						<h1><?php the_title();?></h1>
						<div class="introduction">
							<?php the_content();?>
						</div>

					</div>
					<div class="w-100"></div>
					<div class="col-md-3 mt-5 position-relative">
						<input type="text" class="form-control" placeholder="Ville ou code postal" id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" maxlength="2048" tabindex="1">
						<svg id="search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" class="position-absolute" style="top: 50%; right: 30px; transform: translateY(-50%);">
							<g><path d="M504.49,468.3,388.43,352.23A216.47,216.47,0,0,0,435.19,217.6c0-120-97.6-217.6-217.58-217.6S0,97.61,0,217.6s97.61,217.6,217.6,217.6a216.43,216.43,0,0,0,134.62-46.77L468.29,504.5a25.6,25.6,0,0,0,36.2-36.2ZM51.21,217.6c0-91.75,74.65-166.4,166.4-166.4S384,125.85,384,217.6a165.86,165.86,0,0,1-48.35,117.16l-.88.89A165.84,165.84,0,0,1,217.61,384C125.86,384,51.21,309.35,51.21,217.6Z"/><path d="M335.64,334.76l-.88.89c.13-.15.28-.31.43-.46S335.49,334.89,335.64,334.76Z"/></g>
						</svg>
						<div id="boxcheck"></div>
					</div>
					<div class="text-center help-text">
						Aucune librairie dans votre ville ?<br />Tentez une recherche par département ou région.
					</div>
					<div class="text-center help-text">
						<a class="btn btn-primary" href="<?php echo get_permalink(43); ?>map/geolocalisation">Découvrez les librairies autour de vous</a>
					</div>
				</div>
			</div>
		</section>
		<section id="section2">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-3 offset-md-1">
						<h3 class="on">Régions <svg width="20" class="float-end d-md-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256,396.51a25,25,0,0,1-17.67-7.33l-231-231a25,25,0,0,1,35.36-35.35L256,336.15,469.32,122.82a25,25,0,0,1,35.36,35.35l-231,231A25,25,0,0,1,256,396.51Z"/></svg></h3>
						<ul class="accordion on">
							<?php
								$args = array(
									'post_type' => 'librairie',
									'post_status' => 'publish',
									'posts_per_page' => '-1',
									'meta_key' => 'region',
									'orderby' => 'meta_value_num',
									'order' => 'ASC'
								);

								$query = new WP_Query( $args );

								$regions = array();

								if ( $query->have_posts() ) :
									while ( $query->have_posts() ) {

										$query->the_post();

										if ( !in_array(get_field('region'),$regions) ) {
											array_push($regions,get_field('region'));
										}

									}
								endif;

								wp_reset_postdata();

								foreach ($regions as $region) {
									echo '<li><a href="'.get_permalink().'region/'.esc_attr($region['value']).'"  data-type="region">'.esc_html($region['label']).'</a></li>';
								}
							?>
							
						</ul>
					</div>
					<hr class="d-md-none mt-5">
					<div class="col-md-7">
						<h3>Départements <svg width="20" class="float-end d-md-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256,396.51a25,25,0,0,1-17.67-7.33l-231-231a25,25,0,0,1,35.36-35.35L256,336.15,469.32,122.82a25,25,0,0,1,35.36,35.35l-231,231A25,25,0,0,1,256,396.51Z"/></svg></h3>
						<div class="row accordion">
							<div class="col">
								<ul class="triple">
								<?php
									$args = array(
										'post_type' => 'librairie',
										'post_status' => 'publish',
										'posts_per_page' => '-1',
										'meta_key' => 'departement',
										'orderby' => 'meta_value_num',
										'order' => 'ASC'
									);

									$query = new WP_Query( $args );

									$departements = array();

									if ( $query->have_posts() ) :
										while ( $query->have_posts() ) {

											$query->the_post();

											if ( !in_array(get_field('departement'),$departements) ) {
												array_push($departements,get_field('departement'));
											}

										}
									endif;

									wp_reset_postdata();

									foreach ($departements as $departement) {
										echo '<li><a href="'.get_permalink().'departement/'.esc_attr($departement['value']).'"  data-type="departement">'.esc_html($departement['label']).'</a></li>';
									}
								?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php get_footer(); ?>