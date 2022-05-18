		<?php wp_footer(); ?>
		<?php if ( is_page( 43 ) || is_singular('librairie') ) :?>
			<?php if ( $zone || is_singular('librairie') ) : ?>
			<!-- Async script executes immediately and must be after any DOM elements used in callback. -->
			<script
				src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBiou-2FJn9GIIm2v-QBdLNUT47cX5kEg&callback=initMap"
				async
			></script>

			<script type="text/javascript">
				
				function geoloc () {
					if (navigator.geolocation)
					  var watchId = navigator.geolocation.watchPosition(successCallback, null, {
						enableHighAccuracy: true,
					  })
					else alert('Votre navigateur ne prend pas en compte la géolocalisation HTML5')
				}
				
				function successCallback(position) {
				  var mypos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				  map.panTo(mypos)
				  var marker = new google.maps.Marker({
					position: mypos,
					map: map,
				  })
				}
				
				function initMap() {
					
					map = new google.maps.Map(document.getElementById("map"), {
						zoom: zoom,
						center: poscenter,
						maxZoom: 18
					});

					bounds  = new google.maps.LatLngBounds();

					var iconBase = "<?php echo get_template_directory_uri();?>/assets/img/";
					
					<?php
					global $markers;

					if ( $markers ) :
						foreach ($markers as $marker) {
							?>
							marker = new google.maps.Marker({
								position: { lat: <?= $marker['latitude']; ?>, lng: <?= $marker['longitude']; ?> },
								title: "<?= $marker['nom']; ?>",
								map,
								icon: iconBase + 'marker.png'
							});
							infowindow = new google.maps.InfoWindow();

							google.maps.event.addListener(marker, 'click', (function(marker) {
								return function() {
									var content = '<div class="infobulle"><p class="nom"><?= addslashes($marker['nom']); ?></p><p class="adresse"><?= addslashes($marker['rue']); ?><br><?= $marker['cp']; ?> <?= addslashes($marker['localite']); ?></p><a href="<?php echo $marker['link'];?>">En savoir plus</a></div>';
									infowindow.setContent(content);
									infowindow.open(map, marker);
								}
							})(marker));

							loc = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
							bounds.extend(loc);
							<?php
						}
					endif;
					?>
					
					<?php if ($idzone === 'geolocalisation') :?>
						geoloc();
						map.setZoom(14);
					<?php else :?>
						map.fitBounds(bounds);
						map.panToBounds(bounds);
					<?php endif; ?>

					

				}

			</script>
			<?php endif; ?>
		<?php endif; ?>

	</body>

</html>
