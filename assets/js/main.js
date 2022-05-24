var map;
var zoom = 7;
var poscenter = { lat: 46.52863469527167, lng: 2.43896484375 };

var mobilew = 992;

function isMobile () {
	var vw = document.documentElement.clientWidth;
	
	if ( vw < mobilew) {
		jQuery('body').addClass('mobile');
	} else {
		jQuery('body').removeClass('mobile');
	}
}

function delay(milliseconds){
    return new Promise(resolve => {
        setTimeout(resolve, milliseconds);
    });
}

async function calculPurcent () {
	
	var cpt = parseInt(1);
	var val = parseInt(0);
	
	while (cpt <= 100) {
		val = Math.floor(Math.random() * 10);
		cpt = parseInt(cpt) + parseInt(val);
		if (cpt > 100) { cpt = 100; }
		await delay(80);
		jQuery('.preload .purcent span').html(cpt);
	}
	
}

function compteur () {
	
	var date_actuelle = new Date();
    var date_evenement = new Date("Sept 13, 2022 00:00:00");
	//var date_evenement = new Date("May 13, 2022 16:30:00");
	
	var total_secondes = (date_evenement - date_actuelle) / 1000;
	
	if (total_secondes < 0) {
		
		console.log('evenement terminé');
	
	} else {
		
		var jours = Math.floor(total_secondes / (60 * 60 * 24));
        var heures = Math.floor((total_secondes - (jours * 60 * 60 * 24)) / (60 * 60));
        var minutes = Math.floor((total_secondes - ((jours * 60 * 60 * 24 + heures * 60 * 60))) / 60);
        var secondes = Math.floor(total_secondes - ((jours * 60 * 60 * 24 + heures * 60 * 60 + minutes * 60)));
		
		jQuery('#j span').html(jours);
		jQuery('#h span').html(heures);
		jQuery('#m span').html(minutes);
		jQuery('#s span').html(secondes);
	
	}
	
	setTimeout(compteur, 1000);
}

jQuery( document ).ready(function() {
	
	/* INIT */
	isMobile();
	
	/*  COMPTE A REBOURS */
	
	var car = jQuery('#compte');
	compteur();
	
	/* TOOLTIPS NL */
	
	$(document).on("mouseover", ".tooltip-content-open", function () {
		var t = $(this).attr("rel"),
			e = $(this).offset().top,
			n = $(this).offset().left + $(this).width() / 2;
		$(this).offset().left + $(t).width() / 2 > m && (console.log("lella"), console.log($(t).width()), console.log(m), (n = m - $(t).width() / 2 - 25) < $(t).width() / 2 && (n = 12.5 + $(t).width() / 2)),
			$(t).css("top", e).css("left", n).fadeIn();
	})
	$(document).on("mouseout", ".tooltip-content-open", function () {
		var t = $(this).attr("rel");
		$(t).fadeOut();
	})
	$(document).on("click", ".tooltip-content-close", function () {
		var t = $(this).attr("rel");
		$(t).fadeOut();
		var e = $(this);
		e.addClass("off"),
			setTimeout(function () {
				e.parent().find(".pave-gradient-content").removeClass("off");
			}, 300);
	})
	
	/* BTN LIBRAIRE */
	
	if ( jQuery('body').hasClass('mobile') ) {
		jQuery('.libraire').click(function () {
			$(this).addClass('on');
		});
	} else {
		jQuery('.libraire')
			.mouseenter ( function () {
				jQuery(this).toggleClass('on');
			})
			.mouseleave ( function () {
				jQuery(this).toggleClass('on');
			});
	}

	
	
	/* STORE LOCATOR */	
	
	if ( jQuery('body').hasClass('page-template-google-map') ) {
		
		jQuery('input#autoComplete').keyup(function () {
			var val = jQuery(this).val();
			
			jQuery.ajax({
			  	url: ajaxurl,
			  	type: "POST",
			  	data: {
					'action': 'autocomplete',
					'val': val,
				}
			}).done(function(response) {
			  	jQuery('#boxcheck').html(response);
			});
			
		});
		
		jQuery(document).on('click','#boxcheck ul li',function (){

			var val = jQuery(this).html();
			val = val.replace('<mark>', '');
			val = val.replace('</mark>', '');
			
			jQuery('input#autoComplete').val(val);
			jQuery('#boxcheck').html('');
			
			var fc = jQuery("#autoComplete").val().substr(0,1);
			
			
			if ( isNaN(fc) ) {
				jQuery(location).attr('href','/nos-librairies-partenaire/ville/'+jQuery("#autoComplete").val());
			} else {
				jQuery(location).attr('href','/nos-librairies-partenaire/ville/'+jQuery("#autoComplete").val().substr(8));
			}
			
		});
		
		if ( jQuery('body').hasClass('mobile') ) {
			
			jQuery(document).on( 'click','#section2 h3',function (){
				
				jQuery(this).toggleClass('on');
				jQuery(this).next().toggleClass('on');
				
			});
			
		}
		
	}

	/* LOADING HP */
	
	calculPurcent ();

	setTimeout(function () {
		
		jQuery('body').addClass('loaded').removeClass('noscroll').addClass('animate');
		
	}, 2000)
	
	
	jQuery(window).on('resize', function () {
		isMobile();
	})
	
});