/* Error IE. Se esperaba un identificador, una cadena o un número 
 * Cerca expressió regular ",[\s|\t|\n]*[}|\]]"
 * En la definició d'arrasy etc, l'últim no pot acabar amb coma */

(function($){
	
	/************************************************* Utils *****************************************************/

	calculateAspectRatioFit = function(srcWidth, srcHeight, maxWidth, maxHeight) {
	    var ratio = [maxWidth / srcWidth, maxHeight / srcHeight ];
	    ratio = Math.min(ratio[0], ratio[1]);

	    return { width:srcWidth*ratio, height:srcHeight*ratio };
	}
	
	redimensionarImatgesCataleg = function(id) {
		$(id).each(function() {
	    	var a = calculateAspectRatioFit($(this).attr("width"), $(this).attr("height"), $(this).parent().width(), $(this).parent().height() - 10);
	    	$(this).attr("width", Math.round(a.width));
	    	$(this).attr("height", Math.round(a.height));
	    	
	    	if (Math.round(a.width) > Math.round(a.height)) {
	        	$(this).parent().addClass("cataleg-imatge-cell");
			}
		});
	}	
		
	/************************************************* Fi Utils *****************************************************/
	
	/************************************************* Cataleg *****************************************************/
	
	hoverCatalegAdminActions = function() {
		$("#list-cataleg li").mouseenter( function(){
			$(this).find(".cataleg-imatge").addClass("hover-cataleg-item");
			$(this).find(".cataleg-nom").addClass("hover-cataleg-item");
			$(this).find(".admin-actions").fadeIn(200);
		});
	
		$("#list-cataleg li").mouseleave( function(){
			$(this).find(".cataleg-imatge").removeClass("hover-cataleg-item");
			$(this).find(".cataleg-nom").removeClass("hover-cataleg-item");
			$(this).find(".admin-actions").fadeOut(200);
		});
	}
	
	/*************************************************** Fi Cataleg *****************************************************/
	
})(jQuery);
