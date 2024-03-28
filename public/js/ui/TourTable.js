import TourTableRow from "./TourTableRow.js";

export default class TourTable {
    constructor(EventService) {
        this.EventService = EventService;
        this.rows = [];
        this.columns = ["Tour","Duration", "Operator", "", "",""];
        this.destinationNode = null;
    }
    createRow() {
        var row = new TourTableRow(this.EventService);
        this.rows.push(row);
        return row;
    }
    clearRows() {
        this.rows = [];
    }

    render(){
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

    createEmptyNotice(){
        var element = document.createElement("tr");
        var td = document.createElement("td");
        td.innerHTML = "No tours to show.";
        td.setAttribute("colspan",this.columns.length);
        element.appendChild(td);
        return element;
    }
}
