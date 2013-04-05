/**
 * Aplikace editace modelu
 * 
 * @param buttonLoad
 * @param buttonNew
 * @param buttonDelete
 * @param modelContainer
 * @param modelSaveDelay
 * @returns
 */
function Application(buttonLoad, buttonNew, buttonDelete, modelContainer, modelSaveDelay) {
	
	this.buttonLoad = (buttonLoad ? buttonLoad : '#modelButtonLoad');
	this.buttonNew = (buttonNew ? buttonNew : '#modelButtonNew');
	this.buttonDelete = (buttonDelete ? buttonDelete : '#modelButtonDelete');
	this.modelContainer = (modelContainer ? modelContainer : '#modelContainer');
	this.modelSaveDelay = (modelSaveDelay ? modelSaveDelay : '#modelSaveDelay');
	
	this.modelHandler;
	
	this.start = function() {
		$(this.divModel).html('Čekám na akci uživatele ...');
		this.modelHandler = ModelHandlerFactory(this.modelContainer, this.modelSaveDelay);
		$(this.buttonLoad).click(this.modelHandler.loadModel);
		$(this.buttonNew).click(this.modelHandler.newModel);
		$(this.buttonDelete).click(this.modelHandler.deleteModel);
	}
}

// globalni promenna s aplikaci
var app;

// spusteni aplikace - az po kompletnim nacteni stranky a nikoliv 
// jen DOM ready 
$(window).load(function() {
	app = new Application();
	app.start();
});
