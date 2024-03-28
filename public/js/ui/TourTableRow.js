export default class TourTableRow {
    constructor(EventService) {
        this.EventService = EventService;
        this.tourId = null;
        this.tourName = "";
        this.tourDuration = null;
        this.tourOperator = "";
        this.tourUrl = "";
        this.syncAvailable = false;
    }

    //Create and attach cells to row, return row
    buildElement() {
        var element = document.createElement("tr");
        element.appendChild(this.buildTourNameCell());
        element.appendChild(this.buildTourDurationCell());
        element.appendChild(this.buildTourOperatorCell());
        element.appendChild(this.buildViewCell());
        element.appendChild(this.buildSyncCell());
        element.appendChild(this.buildDeleteCell());
        return element;
    }

    buildTourNameCell() {
        var element = document.createElement("td");
        var link = document.createElement("a");
        link.innerHTML = this.tourName;
        link.setAttribute("href", this.tourUrl);
        link.setAttribute("target", "_blank");
        element.appendChild(link);
        return element;
    }
    buildTourDurationCell() {
        var element = document.createElement("td");
        element.innerHTML = this.tourDuration+' days';
        return element;
    }

    buildTourOperatorCell() {
        var element = document.createElement("td");
        element.innerHTML = this.tourOperator;
        return element;
    }

    buildViewCell() {
        var element = document.createElement("td");
        element.appendChild(this.buildViewButton());
        return element;
    }

    buildViewButton() {
        var btn = document.createElement("button");
        var tourId = this.tourId;
        var data = {
            "tourId": tourId
        };
        var es = this.EventService;
        var click = () => {
            es.send("onViewDeparturesBtnClick", data);
        }
        btn.innerHTML = "View Departures";
        btn.addEventListener("click", click);
        return btn;
    }

    buildSyncCell() {
        var element = document.createElement("td");
        if (this.syncAvailable) element.appendChild(this.buildSyncButton());
        return element;
    }

    buildSyncButton() {
        var btn = document.createElement("button");
        btn.classList.add("sync");
        btn.innerHTML = "Sync Now";
        var tourId = this.tourId;
        var data = {
            "tourId": tourId
        };
        var es = this.EventService;
        var click = () => {
            btn.classList.add("syncing");
            btn.innerHTML = "Syncing...";
            btn.classList.remove("sync");
            es.send("onSyncBtnClick", data);
        }
        btn.addEventListener("click", click);
        return btn;
    }



    buildDeleteCell() {
        var element = document.createElement("td");
        element.appendChild(this.buildDeleteButton());
        return element;
    }

    buildDeleteButton() {
        var btn = document.createElement("button");
        btn.innerHTML = "Delete";
        btn.classList.add("delete");
        var es = this.EventService;
        var tourId = this.tourId;
        var tourName = this.tourName;
        var data = {
            "tourId": tourId,
            "tourName": tourName
        };
        var onclick = () => es.send("onTourDeleteBtnClick", data);
        btn.addEventListener("click", onclick);
        return btn;
    }




}
