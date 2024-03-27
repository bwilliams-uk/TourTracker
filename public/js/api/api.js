export default class API {
    getTourData() {
        var exec = (resolve) => {
            var url = "report/tours";
            $.get(url, null, resolve);
        }
        return new Promise(exec);
    }
    getDepartureData(data) {
        var exec = (resolve) => {
            var url = "report/departures/"+((data.tour_id) ?? ""); // TODO TIDY
            $.get(url,data, resolve);
        };
        return new Promise(exec);
    }
    getPriceHistoryData(data) {
        var exec = (resolve) => {
            var url = "report/history/"+data.departure_id;
            $.get(url, null, resolve);
        };
        return new Promise(exec);
    }

    getAlertData(data) {
        var exec = (resolve) => {
            var url = "report/alerts";
            $.get(url, null, resolve);
        };
        return new Promise(exec);
    }


    createTour(tourUrl) {
        var exec = (resolve) => {
            var postUrl = "tour/create";
            var data = {
                "url": tourUrl
            };
            $.post(postUrl, data, resolve);
        };
        return new Promise(exec);
    }
    syncTour(tourId) {
        var exec = (resolve) => {
            var postUrl = "tour/sync/"+tourId;
            var data = {
                "tour": tourId
            };
            $.post(postUrl, data, resolve);
        };
        return new Promise(exec);
    }
    deleteTour(tourId) {
        var exec = (resolve) => {
            var postUrl = "tour/delete/"+tourId;
            var data = {
                "tour": tourId
            };
            $.post(postUrl, data, resolve);
        };
        return new Promise(exec);
    }
    watchDeparture(departureId) {
        var exec = (resolve) => {
            var postUrl = "departure/watch/"+departureId;
            var data = {
                "departure": departureId
            };
            $.post(postUrl, data, resolve);
        };
        return new Promise(exec);
    }
    unwatchDeparture(departureId) {
        var exec = (resolve) => {
            var postUrl = "departure/unwatch/"+departureId;
            var data = {
                "departure": departureId
            };
            $.post(postUrl, data, resolve);
        };
        return new Promise(exec);
    }
}
