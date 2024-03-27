var onSyncBtnClick = (app, data) => {
    var respond = (data) => app.EventService.send("onSyncResponse", data);
    //TODO trigger some UI event to show response is pending then cancel in response event
    var tourId = data.tourId;
    app.API.syncTour(tourId).then(respond);
}
export default onSyncBtnClick;
