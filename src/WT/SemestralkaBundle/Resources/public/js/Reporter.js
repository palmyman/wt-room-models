/**
 * Factory pro vytváření zpráv
 * 
 * @param body tělo zprávy
 * @param header hlavička zprávy
 * @param container id elementu, který tvoří kontejner (#reportArea)
 * @param delay doba zobrazení zprávy
 * @param fadeIn doba trvání efektu fadeIn
 * @param fadeOut doba trvání efektu fadeOut
 * @returns {___anonymous776_923}
 */
function Reporter(body, header, container, delay, fadeIn, fadeOut) {
	// hodnoty 
	var messageContainer = (container ? container : '#reportContainer'); 
	var messageDelay = (delay ? delay : 1500);
	var effectFadeIn = (fadeIn ? fadeIn : 300);
	var effectFadeOut = (fadeOut ? fadeOut : 300);
	// zahlavi zpravy
	var messageHeader = document.createElement('h3');
	$(messageHeader)
		.addClass('ui-corner-all')
		.html((header ? header :'Zpráva'));
	// zapati zpravy
	var messageBody = document.createElement('p');
	$(messageBody)
		.html((body ? body : ''));
	// zprava
	var message = document.createElement('div');
	$(message)
		.addClass('ui-corner-all')
		.append(messageHeader)
		.append(messageBody);
	$(messageContainer).append(message);
	// predani objektu ...
	return {
		report: function() {
			$(message).show('fade', {}, fadeIn, 
				function() {$(message).delay(messageDelay).hide('fade', {}, fadeOut);});
		}
	} 
}