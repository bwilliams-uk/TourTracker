class FormSubmissionHandler {

    onSubmit() {
        var sub = this.getDataFromForm();
        var ip = new ItineraryProcessor();
        ip.setTourSequence(sub.tours);
        window.ip = ip;

        var callback = (data) => {
            ip.receiveItineraryData(data);
        }

        $.post("../itineraries/find", sub, callback);
        var handle = this;
        sub.tours.forEach((id) => handle.getDepartureData(ip, id));
    }

    getDepartureData(ipObject, tourId) {
        var callback = function (data) {
            ipObject.receiveDepartureData(tourId, data);
        }
        $.get('../tour/' + tourId + '/departures', null, callback);
    }

    getDataFromForm() {
        var sub = {};
        sub.start = document.getElementById("start-date").value;
        sub.window = document.getElementById("depart-within").value;
        sub.end = document.getElementById("end-date").value;
        sub.tours = this.getTourIdsFromSelects();
        sub.minInterval = document.getElementById("min").value;
        sub.maxInterval = document.getElementById("max").value;
        return sub;
    }

    getTourIdsFromSelects() {
        var selects = document.querySelectorAll("select[id^='tour-']");
        var n = selects.length;
        var array = [];
        for (let i = 0; i < n; i++) {
            if (selects[i].value !== "0") {
                array.push(parseInt(selects[i].value));
            }
        }
        return array;
    }

}
