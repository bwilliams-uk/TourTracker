var updateTourInfo = (app, data) => {

    // Function to put data in the UI
    var withData = (data) => {
        var nTours = data.length;
        var table = app.UI.TourTable;
        table.clearRows();
        for (let i = 0; i < nTours; i++) {
            var row = table.createRow();
            row.tourName = data[i].tour_name;
            row.tourDuration = data[i].duration_days;
            row.tourOperator = data[i].operator_name; //TODO set to operator name
            row.tourId = data[i].tour_id;
            row.tourUrl = data[i].tour_url;
            row.syncAvailable = data[i].sync_due; //Todo update API to provide sync data
        }
        table.render();
    };

    app.API.getTourData().then(withData);
}
export default updateTourInfo;
