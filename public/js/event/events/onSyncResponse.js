var onSyncResponse = (app, data) => {
    if (data.success) {
        app.MacroService.run("updateTourInfo");
        //alert("Sync was successful.");
    } else {
        alert("Sync failed: " + data.errorMessage);
    }
}
export default onSyncResponse;
