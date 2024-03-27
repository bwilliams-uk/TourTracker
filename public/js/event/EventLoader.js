import onAddTourBtnClick from "./events/onAddTourBtnClick.js";
import onCreateTourResponse from "./events/onCreateTourResponse.js";
import onStartup from "./events/onStartup.js";
import onSyncBtnClick from "./events/onSyncBtnClick.js";
import onSyncResponse from "./events/onSyncResponse.js";
import onTourDeleteBtnClick from "./events/onTourDeleteBtnClick.js";
import onUnwatchBtnClick from "./events/onUnwatchBtnClick.js";
import onViewDeparturesBtnClick from "./events/onViewDeparturesBtnClick.js";
import onWatchBtnClick from "./events/onWatchBtnClick.js";

export default class EventLoader{
    constructor(app,service){
        var fn0 = (data) => onAddTourBtnClick(app,data);
        var fn1 = (data) => onCreateTourResponse(app,data);
        var fn2 = (data) => onStartup(app,data);
        var fn3 = (data) => onSyncBtnClick(app,data);
        var fn4 = (data) => onSyncResponse(app,data);
        var fn5 = (data) => onTourDeleteBtnClick(app,data);
        var fn6 = (data) => onUnwatchBtnClick(app,data);
        var fn7 = (data) => onViewDeparturesBtnClick(app,data);
        var fn8 = (data) => onWatchBtnClick(app,data);

        service.register("onAddTourBtnClick",fn0);
        service.register("onCreateTourResponse",fn1);
        service.register("onStartup",fn2);
        service.register("onSyncBtnClick",fn3);
        service.register("onSyncResponse",fn4);
        service.register("onTourDeleteBtnClick",fn5);
        service.register("onUnwatchBtnClick",fn6);
        service.register("onViewDeparturesBtnClick",fn7);
        service.register("onWatchBtnClick",fn8);
    }
}
