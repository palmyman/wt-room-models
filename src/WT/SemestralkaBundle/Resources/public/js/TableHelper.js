/**
 * Funkce vytvori objekt pro vytvareni HTML tabulky pomoci
 * elementu DOM. Tabulka se vytvari shora dolu - elementy
 * se zapojuji ve stejnem miste stromu za sebe.
 * 
 * @returns objekt, ktery vytvari HTML tabulku
 * @author kadleto2
 */
function TableHelper() {
	return {
		createTable: function() { 
			var table = document.createElement('table');
			$(table).addClass('model');
			return table; 
		},
		createTableRow: function(table) {
			var tr = document.createElement('tr');
			$(table).append(tr);
			return tr;
		},
		createTableHead: function(tr) {
			var th = document.createElement('th');
			$(tr).append(th);
			return th;
		},
		createTableData: function(tr) {
			var td = document.createElement('td');
			$(tr).append(td);
			return td;
		}
	}
}