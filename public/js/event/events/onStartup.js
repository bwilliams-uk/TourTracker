import UrlUtils from "./../../utilities/UrlUtils.js";

var onStartup = (app, data) => {


    //Identify the requested view and show it using appropriate macro:
    var url = new UrlUtils();
    var view = url.getParamValue("view") ?? "tours";
    switch (view) {
        case "tours":
            app.MacroService.run("updateTourInfo"); //TODO move to showView Macros?
            app.UI.ViewController.show("tours");
            break;
        case "departures":
            var tourId = url.getParamValue("filter_tour");
            var watch = url.getParamValue("filter_watch");
            var d = {
                "tour_id": tourId,
            };
            if(watch !== null){
                d.watched = watch;
            }
            app.MacroService.run("updateDeparturesTable", d);
            app.UI.ViewController.show("departures");

            break;
        case "sync_history":
            data = {};
            data.departure_id = url.getParamValue("departure");
            app.MacroService.run("updateSyncTable",data);
            app.UI.ViewController.show("sync-history");
            break;
        case "alerts":
            data = {};
            app.MacroService.run("updateAlertsData");
            app.UI.ViewController.show("alerts");
            break;
        default:
            app.MacroService.run("updateTourInfo");
            app.UI.ViewController.show("tours");
            break;
    }
}
export default onStartup;
