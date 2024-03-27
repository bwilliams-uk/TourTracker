export default class AlertTableRow {

    constructor(EventService) {
        this.EventService = EventService;
        this.departureId = null;
        this.daysAgo = "";
        this.tourName = "";
        this.tourUrl = "";
        this.startDate = "";
        this.alertType = "";
        this.priceChange;
        this.newPrice;
        this.availabilityChange;
        this.new_availability;
        this.percentage;

    }
    createDescription(){
        switch(this.alertType){
            case "AVAILABILITY REDUCED":
                return this.createSpacesRemainText(this.newAvailability);
                break;
            case "AVAILABILITY INCREASED":
                return this.createSpacesRemainText(this.newAvailability);
                break;
            case "PRICE REDUCED":
                return this.priceChange;
                break;
            case "PRICE INCREASED":
                return "+"+this.priceChange;
            default:
                return "";
                break;
        }
    }

    createSpacesRemainText(count){
        if(count === 1){
            return count+" space remaining";
        }
        else{
            return count+" spaces remaining";
        }
    }

    buildElement() {
        var tr = document.createElement("tr");
        tr.appendChild(this.buildDaysAgoCell());
        tr.appendChild(this.buildTourNameCell());
        tr.appendChild(this.buildStartDateCell());
        tr.appendChild(this.buildAlertTypeCell());
        tr.appendChild(this.buildDescriptionCell());
        tr.appendChild(this.buildHistoryCell());
        return tr;
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

    buildDaysAgoCell() {
        var text = "";
        switch (this.daysAgo) {
            case 0:
                text = "Today";
                break;
            case 1:
                text = "Yesterday";
                break;
            default:
                text = this.daysAgo + " days ago";
        }
        var td = document.createElement("td");
        td.innerHTML = text;
        return td;
    }

    buildStartDateCell() {
        var td = document.createElement("td");
        td.innerHTML = this.startDate;
        return td;
    }

    buildAlertTypeCell() {
        var td = document.createElement("td");
        td.innerHTML = this.alertType;
        return td;
    }

    buildDescriptionCell() {
        var td = document.createElement("td");
        td.innerHTML = this.createDescription();
        td.style.textAlign = "right";
        return td;
    }

    buildDismissCell() {
        var element = document.createElement("td");
        element.appendChild(this.buildDismissButton());
        return element;
    }

    buildDismissButton() {
        var btn = document.createElement("button");
        btn.innerHTML = "Dismiss";
        btn.classList.add("delete");
        var es = this.EventService;
        var departureId = this.departureId;
        var onclick = () => es.send("onAlertDismissBtnClick", departureId);
        btn.addEventListener("click", onclick);
        return btn;
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
