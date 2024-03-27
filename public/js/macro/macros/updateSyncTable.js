var updateSyncTable = (app, data) => {
    // Function to put data in the UI
    var withData = (data) => {
        var n = data.length;
        var table = app.UI.SyncTable;
        table.clearRows();
        for (let i = 0; i < n; i++) {
            var row = table.createRow();
            var dRow = data[i];
            row.daysAgo = dRow.days_ago;
            row.availability = dRow.availability;
            row.price = dRow.price;
        }
        table.render();
    };

    app.API.getPriceHistoryData(data).then(withData);
}

export default updateSyncTable;
