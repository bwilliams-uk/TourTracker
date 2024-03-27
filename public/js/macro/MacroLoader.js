import updateAlertsData from "./macros/updateAlertsData.js";
import updateDeparturesTable from "./macros/updateDeparturesTable.js";
import updateSyncTable from "./macros/updateSyncTable.js";
import updateTourInfo from "./macros/updateTourInfo.js";

export default class MacroLoader{
    constructor(app,service){
        var fn0 = (data) => updateAlertsData(app,data);
        var fn1 = (data) => updateDeparturesTable(app,data);
        var fn2 = (data) => updateSyncTable(app,data);
        var fn3 = (data) => updateTourInfo(app,data);

        service.register("updateAlertsData",fn0);
        service.register("updateDeparturesTable",fn1);
        service.register("updateSyncTable",fn2);
        service.register("updateTourInfo",fn3);
    }
}
