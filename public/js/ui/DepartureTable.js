//TODO - Copy from TourTable
import DepartureTableRow from "./DepartureTableRow.js";

export default class DepartureTable {
    constructor(EventService) {
        this.EventService = EventService;
        this.rows = [];
        this.columns = ["", "Start Date", "End Date", "Tour", "Duration","Availability","Price",""];
        this.destinationNode = null;
    }
    createRow() {
        var row = new DepartureTableRow(this.EventService);
        this.rows.push(row);
        return row;
    }
    clearRows() {
        this.rows = [];
    }

    render() {
        this.destinationNode.innerHTML = "";
        this.destinationNode.appendChild(this.buildElement());
    }

    buildElement() {
        var element = document.createElement("table");
        //Add header
        element.appendChild(this.createHeader());

        //Add body
        var nrows = this.rows.length;
        if (nrows === 0) {
            element.appendChild(this.createEmptyNotice());
        } else {
            for (let i = 0; i < nrows; i++) {
                element.appendChild(this.rows[i].buildElement());
            }
        }
        return element;
    }

    createHeader() {
        var element = document.createElement("tr");
        var ncols = this.columns.length;
        for (let i = 0; i < ncols; i++) {
            var th = document.createElement("th");
            th.innerHTML = this.columns[i];
            element.appendChild(th);
        }
        return element;
    }

    createEmptyNotice() {
        var element = document.createElement("tr");
        var td = document.createElement("td");
        td.innerHTML = "No depatures to show.";
        td.setAttribute("colspan", this.columns.length);
        element.appendChild(td);
        return element;
    }
}
