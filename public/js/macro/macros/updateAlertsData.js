var updateAlertsData = (app, data) => {

    var makeRow = (table, rowData) => {
        var r = rowData;
        var row = table.createRow();
        row.daysAgo = r.sync_days_ago;
        row.departureId = r.departure_id;
        row.tourName = r.tour_name;
        row.tourUrl = r.tour_url;
        row.startDate = r.start_date;
        return row;
    }

    // Function to put data in the UI
    var withData = (data) => {
        var nAlerts = data.length;
        var table = app.UI.AlertTable;
        table.clearRows();
        for (let i = 0; i < nAlerts; i++) {
            var r = data[i];
            var row = table.createRow();
            row.daysAgo = r.sync_days_ago;
            row.departureId = r.departure_id;
            row.tourName = r.tour_name;
            row.tourUrl = r.tour_url;
            row.startDate = r.start_date;
            row.alertType = r.alert_type;
            row.priceChange = r.price_change;
            row.newPrice = r.new_price;
            row.availabilityChange = r.availability_change;
            row.newAvailability = r.new_availability;
            row.percentage = r.percentage;

            //TODO define description.inde
        }
        table.render();
    };

    app.API.getAlertData().then(withData);
}
export default updateAlertsData;
