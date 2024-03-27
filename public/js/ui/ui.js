import ViewController from "./ViewController.js";
import TourTable from "./TourTable.js";
import AlertTable from "./AlertTable.js";
import DepartureTable from "./DepartureTable.js";
import SyncTable from "./SyncTable.js";
import AddTourUtility from "./AddTourUtility.js";

export default class UI {
    constructor(EventService, config) {
        this.EventService = EventService;

        this.ViewController = new ViewController();
        this.ViewController.hideAll();

        this.TourTable = new TourTable(EventService);
        this.TourTable.destinationNode = document.getElementById("tour-data");

        this.DepartureTable = new DepartureTable(EventService);
        this.DepartureTable.destinationNode = document.getElementById("departure-data");

        this.SyncTable = new SyncTable(EventService);
        this.SyncTable.destinationNode = document.getElementById("sync-data");

        this.AlertTable = new AlertTable(EventService);
        this.AlertTable.destinationNode = document.getElementById("alert-data");


        this.AddTourUtility = new AddTourUtility(EventService);

    }

}
