import config from "./config.js";
import API from "./api/api.js";
import UI from "./ui/ui.js";
import EventService from "./event/EventService.js";
import EventLoader from "./event/EventLoader.js";
import MacroService from "./macro/MacroService.js";
import MacroLoader from "./macro/MacroLoader.js";

var es = new EventService(); //Service for triggering events
var ms = new MacroService(); //Service for calling reusable actions "Macros"
var api = new API(es, config); // Object for making API calls
var ui = new UI(es, config);

var app = {
    EventService: es,
    MacroService: ms,
    API: api,
    UI: ui
};

new MacroLoader(app,ms); //Construct Macros
new EventLoader(app,es); //Register Event Methods

onload = () => es.send("onStartup"); //Trigger startup event

if (config.development) {
    window.app = app;
}
