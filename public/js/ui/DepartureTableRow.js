export default class DepartureTableRow {
    constructor(EventService) {
        this.EventService = EventService;
        this.departureId = null;
        this.watch = 0;
        this.startDate = "";
        this.endDate = "";
        this.tourName = "";
        this.duration = null;
        this.availability = "";
        this.price = "";
    }
    buildElement() {
        var tr = document.createElement("tr");
        tr.appendChild(this.buildWatchCell());
        tr.appendChild(this.buildStartDateCell());
        tr.appendChild(this.buildEndDateCell());
        tr.appendChild(this.buildTourNameCell());
        tr.appendChild(this.buildDurationCell());
        tr.appendChild(this.buildAvailabilityCell());
        tr.appendChild(this.buildPriceCell());
        tr.appendChild(this.buildHistoryCell());
        if(this.availability <= 0) tr.classList.add("unavailable");
        return tr;
    }
    buildWatchCell() {
        var td = document.createElement("td");
        var watch = this.buildWatchButton();
        var unwatch = this.buildUnwatchButton();
        this.makeWatchToggle(watch, unwatch);
        td.appendChild(watch);
        td.appendChild(unwatch);
        return td;
    }
    buildWatchButton() {
        var btn = document.createElement("button");
        btn.innerHTML = "Watch";
        btn.classList.add("watch");
        var es = this.EventService;
        var depId = this.departureId;
        var click = () => es.send("onWatchBtnClick", depId);
        btn.addEventListener("click", click);
        return btn;
    }
    buildUnwatchButton() {
        var btn = document.createElement("button");
        btn.innerHTML = "Watching";
        btn.classList.add("watching");
        var es = this.EventService;
        var depId = this.departureId;
        var click = () => es.send("onUnwatchBtnClick", depId);
        btn.addEventListener("click", click);
        return btn;
    }

    //Ensures watch/unwatch button clicks toggle eachother to be shown.
    makeWatchToggle(watchBtn, unwatchBtn) {

        var displayType = "inline"; //CSS display property for displayed button

        var show = (this.watch === 0) ? watchBtn : unwatchBtn;
        var hide = (this.watch === 1) ? watchBtn : unwatchBtn;
        show.style.display = displayType;
        hide.style.display = "none";

        var onWatchClick = () => {
            watchBtn.style.display = "none";
            unwatchBtn.style.display = displayType;
        };

        var onUnwatchClick = () => {
            console.log("unwatch clicked!");
            unwatchBtn.style.display = "none";
            watchBtn.style.display = displayType;
        };

        watchBtn.addEventListener("click",onWatchClick);
        unwatchBtn.addEventListener("click",onUnwatchClick);
    }


    buildStartDateCell() {
        var td = document.createElement("td");
        td.innerHTML = this.startDate;
        return td
    }
    buildEndDateCell() {
        var td = document.createElement("td");
        td.innerHTML = this.endDate;
        return td;
    }
    buildTourNameCell() {
        var td = document.createElement("td");
        td.innerHTML = this.tourName;
        return td;
    }
    buildDurationCell() {
        var td = document.createElement("td");
        td.innerHTML = this.duration + " days";
        return td;
    }
    buildAvailabilityCell() {
        var td = document.createElement("td");
        td.innerHTML = this.availability; //Maybe apply formatting based on availability?
        return td;
    }
    buildPriceCell() {
        var td = document.createElement("td");
        td.innerHTML = this.price;
        return td;
    }
    buildHistoryCell() {
        var td = document.createElement("td");
        td.appendChild(this.buildHistoryButton());
        return td;
    }

    buildHistoryButton() {
        var btn = document.createElement("button");
        btn.innerHTML = "History";
        var depId = this.departureId;
        var click = () => location.href = "?view=sync_history&departure=" + depId;
        btn.addEventListener("click", click);
        return btn;
    }
}
