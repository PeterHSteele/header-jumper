(function( $ ) {
	'use strict';

	$( window ).load(function(){

		/* Definitions */

		//allow headings to be used as anchors
		function addHeadingIds(){
			let headingTypes = ['h2','h3','h4','h5','h6'];
			
			for (let i=0; i<headingTypes.length; i++){
				let elements = $( headingTypes[i] );
				
				elements.attr('id',function(){
					return $(this).text().replace(/\s/g,'_');
				});

			}
		}

		function showSubmenu( element ){
			element
				.slideDown()
				.removeClass('heading-jumper-no-display')
				.attr('aria-expanded','true');
		}

		function hideSubmenu ( element ) {
			element
				.slideUp()
				.addClass('heading-jumper-no-display')
				.attr('aria-expanded','false');
		}

		function toggleDisplay( element ){
			if ( element.hasClass('heading-jumper-no-display') ){
				showSubmenu( element );
			} else {
				hideSubMenu( element );
				//element.parent().removeClass('child-open');
			}
		}

		function changeArrowIconDirection( element ){
				let degrees;

				/*
				parse arrow's css transform property from radians to degrees: 
				https://css-tricks.com/get-value-of-css-rotation-through-javascript/
				*/
				let style = element.css('transform');
				if ( style != 'none' ) {
					let values = style.split('(')[1];
					values = values.split(')')[0];
					values = values.split(',');
					let a = values[0],b=values[1];
				
					degrees = Math.round(Math.atan2(b,a)*(180/Math.PI));
				} else {
					degrees = 0;
				}

				let limit = degrees + 180;
				
				//rotate arrow by 5 degrees every 8 ms until we've gone 180
				let loop = setInterval(function(){
					if (degrees<limit){
						degrees+=5;
						element.css( 'transform' , 'rotate('+degrees+'deg)' );
					}else{
						clearInterval(loop);
					}
				},8)
		} 

		/* Execution */

		addHeadingIds();

		//handle click on arrow button
		let cooldown = false;//variable to determine whether arrow button can be clicked

		$('.heading-jumper-toc li button').on('click',function(){
				let	childSubmenu,
					childSubmenuIsOpen,
					clearCooldown = () => cooldown = false ;

				//toggle aria-pressed
				$(this).attr('aria-pressed', ! ( $( this ).attr('aria-pressed') ) );	
				
				//prevent rotation animation from being disrupted
				if ( ! cooldown ){
					cooldown = true;
					changeArrowIconDirection( $(this).children( 'span' ) );
					window.setTimeout( clearCooldown, 300 );
				
				//show or hide submenu 
					childSubmenu = $(this).parent().children('ul');
					childSubmenuIsOpen = ! childSubmenu.hasClass('heading-jumper-no-display');
					if ( childSubmenuIsOpen ){
						hideSubmenu( childSubmenu );
					} else {
						showSubmenu ( childSubmenu );
					}
				}
				
		})
	});

})( jQuery );
