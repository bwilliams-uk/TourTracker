var updateDeparturesTable = (app, data) => {
    // Function to put data in the UI
    var withData = (data) => {
        var nDepartures = data.length;
        var table = app.UI.DepartureTable;
        table.clearRows();
        for (let i = 0; i < nDepartures; i++) {
            var row = table.createRow();
            var dRow = data[i];
            row.departureId = dRow.departure_id;
            row.watch = dRow.watch;
            row.startDate = dRow.start_date;
            row.endDate = dRow.end_date;
            row.tourName = dRow.tour_name;
            row.duration = dRow.duration_days;
            row.availability = dRow.availability;
            row.price = dRow.price;
        }
        table.render();
    };

    app.API.getDepartureData(data).then(withData);
}

export default updateDeparturesTable;
