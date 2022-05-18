<?php
/**
 * Template Name: Inscription
 * Template Post Type: page
 *
 * @package WordPress
 */
?>
<?php get_header(); ?>
	<div class="container-fluid">
		<div class="row justify-content-center my-5">
			<div class="col-6 text-center">
				<h1><?php the_title();?></h1>
			</div>
		</div>
		<div class="row justify-content-center my-5">
			<div class="col-6 text-center lead">
				<?php the_content();?>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-4">
				<form class="border p-5">
				  <div class="mb-3">
					<label for="nucli" class="form-label">NUCLI</label>
					<input type="text" class="form-control" id="nucli">
				  </div>
				  <div class="mb-3">
					<label for="nom" class="form-label">Nom de la librairie</label>
					<input type="text" class="form-control" id="nom">
				  </div>
				  <div class="mb-3">
					<label for="email" class="form-label">E-mail</label>
					<input type="email" class="form-control" id="email">
				  </div>
				  <div class="mb-3">
					<label for="telephone" class="form-label">Téléphone</label>
					<input type="tel" class="form-control" id="telephone">
				  </div>
				  <div class="mb-5">
					<label for="referent " class="form-label">Personne référente de la soiré</label>
					<input type="text" class="form-control" id="referent">
				  </div>
				  <div class="text-center"><button type="submit" class="btn btn-primary">Envoyer</button></div>
				</form>
			</div>
		</div>
	</div>
<?php get_footer(); ?>