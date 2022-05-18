<?php get_header(); ?>
	<section id="section3" class="container">
		<div class="row">
			<div class="col-12 position-relative">
				<div class="left">				
					<?php
				
										
							global $markers;
							$markers = array();
							$i = 0;
					
							$location = get_field('adresse');
							
							echo '<div class="infostore">';
							echo '<a href="javascript:history.back();" class="link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="10"><path d="M115.53,256a25,25,0,0,1,7.33-17.67l231-231a25,25,0,0,1,35.35,35.36L175.89,256,389.22,469.32a25,25,0,0,1-35.35,35.36l-231-231A25.06,25.06,0,0,1,115.53,256Z"/></svg>Changer de magasin</a>';
							echo '<h1>'.get_the_title().'</h1>';
							echo '</div>'.get_the_content().'</div>';
							echo '<p>'.$location['street_number'] . ' ' .$location['street_name'].'<br />'.$location['post_code'].' '.$location['city'].'</p>';
							echo '<p class="mt-3"><b>Tél : </b>'.get_field('telephone').'</p>';
							echo '<div class="mt-5">';
							echo '<b>Plan</b><br>';
							
							echo '</div>';

							echo '</div>';
					
							$markers[$i]['nom'] = get_the_title();
							$markers[$i]['rue'] = $location['street_number'] . ' ' .$location['street_name'];
							$markers[$i]['cp'] = $location['post_code'];
							$markers[$i]['localite'] = $location['city'];
							$markers[$i]['latitude'] = $location['lat'];
							$markers[$i]['longitude'] = $location['lng'];
							$markers[$i]['link'] = get_permalink();
					?>					
				</div>
			</div>
			<div class="col-12">
				<div class="position-sticky" style="top: 121px">
					<div id="map" class="map" style="height: 860px; width: 100%;"></div>
				</div>
			</div>
		</div>
	</section>
	<?php get_footer(); ?>