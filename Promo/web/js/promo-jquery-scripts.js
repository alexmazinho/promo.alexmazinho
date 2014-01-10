/* Error IE. Se esperaba un identificador, una cadena o un número 
 * Cerca expressió regular ",[\s|\t|\n]*[}|\]]"
 * En la definició d'arrasy etc, l'últim no pot acabar amb coma */

(function($){
	
	/************************************************* Utils *****************************************************/

	menuActive = function(menuobj) {
		if( $("html").hasClass("ie8lte") ) {
			menuobj.addClass("menu-item-ie8lte-active");
		} else {
			menuobj.addClass("menu-item-active");
		}
	}
	
	
	bookmarkClick = function() {
		$('a#bookmark').click(function(e){
			e.preventDefault();
			var bookmarkURL = this.href;
			var bookmarkTitle = this.title;
			try {
				if (window.sidebar) { // moz
					window.sidebar.addPanel(bookmarkTitle, bookmarkURL, "");
				} else if (window.external || document.all) { // ie
					window.external.AddFavorite(bookmarkURL, bookmarkTitle);
				} else if (window.opera) { // duh
					$('a#bookmark').attr('href',bookmarkURL);
					$('a#bookmark').attr('title',bookmarkTitle);
					$('a#bookmark').attr('rel','sidebar');
				}
			} catch (err) { // catch all incl webkit
				alert('Pulsa ctrl+D para añadir esta página a favoritos (Command+D para macs)'); 
			}
		});
	};
	
	
	calculateAspectRatioFit = function(srcWidth, srcHeight, maxWidth, maxHeight) {
	    var ratio = [maxWidth / srcWidth, maxHeight / srcHeight ];
	    ratio = Math.min(ratio[0], ratio[1]);

	    return { width:srcWidth*ratio, height:srcHeight*ratio };
	};
	
	redimensionarImatgesCataleg = function(id, maxWidth, maxHeight) {
		$(id).each(function() {
			var vmaxWidth = (maxWidth == 0)?$(this).parent().width():maxWidth;
			var vmaxHeight = (maxHeight == 0)?$(this).parent().height():maxHeight;
	    	var a = calculateAspectRatioFit($(this).attr("width"), $(this).attr("height"), vmaxWidth - 10, vmaxHeight - 10);
	    	
	    	$(this).attr("width", Math.round(a.width));
	    	$(this).attr("height", Math.round(a.height));
	    	
	    	if (Math.round(a.width) > Math.round(a.height)) {
	    		if( ! $("html").hasClass("ie8lte") ) { 
	    			$(this).parent().addClass("imatge-cell");
	    		}
			}
	    	
	    	$(this).show();
		});
	};	
		
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
	};
	
	
	removeItem = function($dialog, strContent) {
		$('.ui-icon-trash').click(function (e) {
	        //Cancel the link behavior
	        e.preventDefault();
	        
	        var url = $(this).attr("href");
	        
	        $dialog.dialog({
				autoOpen: false,
				title: $(this).attr("title"),
				appendTo: "#content",
				resizable: false,
				height:150,
				modal: true,
				buttons: {
					"Si, borrar": function() {
				 		$( this ).dialog( "close" );
				 		window.location = url;
					},
					"cancelar": function() {
						$( this ).dialog( "close" );
					}
				}
			});
	        
	        $dialog.html(strContent);
	        $dialog.dialog( "open" );
		});
	};
	
	/*************************************************** Fi Cataleg *****************************************************/
	
	/************************************************* Usuari *****************************************************/
	
	pwdRecoverClick = function() {
		$("#pwd-recover").click(function(e) {
			e.preventDefault();
	        
			if ($("#form_usuari").val() == "") {
				showNotification({
					message: "Debes indicar una dirección de correo",
					type : "information",
					autoClose: true,
					duration: 4
				});	
				return false;
			}
			
	        var url = $(this).attr("href");
	        
	        $.get(url, {usuari: $("#form_usuari").val()},
	        	function(data, textStatus) {
					showNotification({
						message: data,
						type : "information",
						autoClose: true,
						duration: 4
					});						
	    		}
			);
		});
	};
	
	/************************************************* Fi Usuari *****************************************************/
	
	/************************************************* Formularis *****************************************************/
	
	hoverPortada = function(hoverobject) {
		
		hoverobject.mouseenter( function(){
			$(this).addClass("border-highlight-blue");
		});
	
		hoverobject.mouseleave( function(){
			$(this).removeClass("border-highlight-blue");
		});
	};
	
	
	$.fn.imagePreview = function(params){
		$(this).change(function(evt){

			if(typeof FileReader == "undefined") return true; // File reader not available.

			var fileInput = $(this);
			var files = evt.target.files; // FileList object
			var total = 0;

			$(params.selector).find(".image-uploaded").remove();  // Removes previous preview 

			// Loop through the FileList and render image files as thumbnails.
			for (var i = 0, f; f = files[i]; i++) {

				// Only process image files.
				if (!f.type.match('image.*')) {
					continue;
				}
				var reader = new FileReader();
				
				// Closure to capture the file information.
				reader.onload = (function(theFile) {
					return function(e) {
						// Render thumbnail.
						var imgHTML = '<img  height="200" title="'+params.textover+'" alt="'+params.textover+'" class="file-input-thumb" src="' + e.target.result + '" title="' + theFile.name + '"/>';

						if( typeof params.selector != 'undefined' ){
							if (params.multiple == true) {
								$novaimatge = $('<div class="image-preview image-uploaded">' + imgHTML +'</div>');
								$(params.selector).append($novaimatge);
								
								/* Les imatges que encara no han pujat al servidor no es poden posar a la portada */
								$novaimatge.find("img").draggable({	
									 cancel: "a.ui-icon", // clicking an icon won't initiate dragging
									 revert: "valid", // when not dropped, the item will revert back to its initial position
									 containment: ".drag-container",  // Contenidor
									 helper: function( event ) {
										 return $( "<div class='ui-widget-header'>Esta imagen aún no se ha subido al servidor</div>" );
									 },
									 opacity: 0.7,
									 cursor: "move"
								});
								
								
							} else {
								$(params.selector).html('<div class="image-portada">' + imgHTML +'</div>');
								hoverPortada($(".image-portada"));
							}
						}else{
							fileInput.before(imgHTML);
						}
					};
				})(f);

				// Read in the image file as a data URL.
				reader.readAsDataURL(f);
			}
		});
	};
	
	removeImageClick = function() {
		$('.image-producte .ui-icon').click(function (e) {
	        //Cancel the link behavior
	        e.preventDefault();
	        
	        var url = $(this).attr("href");
	        $parent = $(this).parent();
	        
	        $.get(url, 
	        	function(data, textStatus) {
	        	 	$parent.remove();
					
        	}).fail(function() { alert("Se ha producido un error borrando la imagen de portada"); });
		});
	};
	
	prepareDragAndDrop = function(url) {
		$(".image-producte img").draggable({
			 cancel: "a.ui-icon", // clicking an icon won't initiate dragging
			 revert: "invalid", // when not dropped, the item will revert back to its initial position
			 containment: ".drag-container",  // Contenidor
			 helper: "clone",
			 opacity: 0.7,
			 cursor: "move"
		});
		
		$( ".image-portada" ).droppable({
			accept: ".image-producte img",
			activeClass: "border-highlight-green",
			drop: function( event, ui ) {
				canviarPortada($(".image-portada img"), ui.draggable);				  // Element nou portada , Element a substituir
			}
		});
		
		function canviarPortada($old, $new) {
			$.get(url, {producteId: $("#producte_id").val(), oldId: $old.attr("data-imageid"), newId: $new.attr("data-imageid")},
			   	function(data, textStatus) {
					$new.parent().append($old);  // Afegir antiga portada a llista
		        	$(".image-portada").empty(); // Buidar portada
		        	$(".image-portada").append($new); // Agegir nova
		        	
		        	prepareDragAndDrop(url);
			}).fail(function() {  alert("Se ha producido un error cambiando la imagen de portada"); });
			

    	};  
    	
	};
	
	/************************************************* Fi Formularis *****************************************************/
	
})(jQuery);
