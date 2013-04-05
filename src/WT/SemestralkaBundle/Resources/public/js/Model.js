/**
 * Objekt slouží jednak pro získání modelu ze serveru, jednak pro jeho 
 * vykreslení klientovi a následné úpravy. S objektem se nepracuje přímo
 * ale pomocí objektů vytvářených ModelHandlerFactory, který poskytuje
 * handlery pro jednotlivé události.
 * 
 * @param container objekt nebo jQuery selektor - HTML kontejner pro 
 *                  vykreslení
 * @param saveDelay hodnota definujici dobu pozastaveni operace save
 *                  na serveru
 */
function Model(container) {
	
	this.data;
	this.container = container;
	
	/**
	 * Vytvori handler pro ulozeni zmeny. Handler bude volan mimo tento
	 * objekt, je tedy nutne vytvorit closure s patricnym prostredim.
	 */
	this.saveHandler = function () {
		var model = this;
		return function(event) {
			// poslání požadavku na změnu hodnoty na serveru
			model.save($(this));
		}
	}
	
	/**
	 * Upravi model jak na klientovi, tak na serveru
	 * @param element jQuery objekt vytvoreny nad DOM elementem, na kterem
	 *                vznikla udalost click
	 */
	this.save = function(element) {
		var model = this.data;
		var delay = "" + this.saveDelay + " > option:selected";
		element.text('').addClass('ajax-loader');
		// odeslaní na server
		$.post(modelApiSave, JSON.stringify([element.data("i"), element.data("j"), element.text()]), function(data) {
			data = $.parseJSON(data);
			model[data['data'][0]][data['data'][1]] = data['data'][2];
			element.text(data['data'][2]).removeClass('ajax-loader');
			Reporter(data['message']).report();
		});
	}

	/**
	 * Vykresleni HTML tabulky
	 */
	this.render = function() {
		// pomocne objekty
		var tableHelper = TableHelper();
		var handler = this.saveHandler();
		// postupné vytvoření HTML tabulky podle získaných dat
		var table = tableHelper.createTable();
		for (i in this.data) {
			var tr = tableHelper.createTableRow(table);
			for (j in this.data[i]) {
				var td = tableHelper.createTableData(tr);
				// doplnění hodnot k buňce, lze použít .data() k uložení libovolných 
				// dvojic klíč-hodnota do elementu DOM
				$(td)
					.attr("id", "" + i + "_" +j)
					.data("i", i)
					.data("j", j)
					.text(this.data[i][j])
				 	.click(handler);
			}
		}
		// nahrazení stávajícího obsahu kontejneru pro model
		// nově vytvořeným
		$(this.container).empty();
		$(this.container).append(table);
	}
}