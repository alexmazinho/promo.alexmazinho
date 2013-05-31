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
	
	redimensionarImatgesCataleg = function(id, maxWidth, maxHeight) {
		$(id).each(function() {
			var vmaxWidth = (maxWidth == 0)?$(this).parent().width():maxWidth;
			var vmaxHeight = (maxHeight == 0)?$(this).parent().height():maxHeight;
	    	var a = calculateAspectRatioFit($(this).attr("width"), $(this).attr("height"), vmaxWidth, vmaxHeight - 10);
	    	$(this).attr("width", Math.round(a.width));
	    	$(this).attr("height", Math.round(a.height));
	    	
	    	if (Math.round(a.width) > Math.round(a.height)) {
	        	$(this).parent().addClass("cataleg-imatge-cell");
			}
		});
	}	
		
	/************************************************* Fi Utils *****************************************************/
	
	/************************************************* Cataleg *****************************************************/
	
	hoverCatalegAdminActions = function(hoverobject, alphaselect, adminselect) {
	
		hoverobject.mouseenter( function(){
			/*$(this).find(".cataleg-imatge").addClass("hover-cataleg-item");
			$(this).find(".cataleg-nom").addClass("hover-cataleg-item");*/
			$(this).find(alphaselect).addClass("hover-cataleg-item");
			$(this).find(adminselect).fadeIn(200);
		});
	
		hoverobject.mouseleave( function(){
			/*$(this).find(".cataleg-imatge").removeClass("hover-cataleg-item");
			$(this).find(".cataleg-nom").removeClass("hover-cataleg-item");*/
			$(this).find(adminselect).fadeOut(200);
			$(this).find(alphaselect).removeClass("hover-cataleg-item");
		});
	}
	
	/*************************************************** Fi Cataleg *****************************************************/
	
})(jQuery);
