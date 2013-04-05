function ModelHandlerFactory(modelContainer) {
	// model
	var model = new Model(modelContainer);
	
	/**
	 * Funkce na nacteni modelu
	 */
	var load = function() {
		$.getJSON(modelApiLoad, function(data) {
			model.data = data;
			model.render();
		});
	}; 
	
	// objekt pro praci s modelem
	var handler = {
		loadModel: function() {
			load();
		},

		newModel: function() {
			$.getJSON(modelApiNew, function(data) {
				Reporter(data).report();
				load();
			});
		},
		
		deleteModel: function() {
			$.getJSON(modelApiDelete, function(data) {
				Reporter(data).report();
				load();
			});
		}
		
		// saveModel: function( event ) {
		// 	event.preventDefault();
		// 	if ($('#modelName').val().length === 0) {
		// 		alert("Zadejte název modelu.")
		// 		return false;
		// 	}
		// 	else {
		// 		$.post(modelSave, {
		// 			formName: $('#modelName').val(),
		// 			formDesc: $('#modelDesc').val()
		// 		},function(data) {
		// 			var obj = $.parseJSON(data);
		// 			Reporter(obj).report();
		// 		});
		// 	}
		// }
	};
	
	return handler;
}