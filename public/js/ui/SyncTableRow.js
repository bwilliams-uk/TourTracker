export default class SyncTableRow {

    constructor() {
        this.daysAgo = "";
        this.availability = "";
        this.price = "";
    }
    buildElement(){
        var tr = document.createElement("tr");
        tr.appendChild(this.buildDaysAgoCell());
        tr.appendChild(this.buildAvailabilityCell());
        tr.appendChild(this.buildPriceCell());
        return tr;
    }
    buildDaysAgoCell(){
        var text = "";
        switch(this.daysAgo){
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

    buildAvailabilityCell(){
        var td = document.createElement("td");
        td.innerHTML = this.availability;
        return td;
    }

    buildPriceCell(){
        var td = document.createElement("td");
        td.innerHTML = this.price;
        return td;
    }
}
