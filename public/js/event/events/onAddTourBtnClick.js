var onAddTourBtnClick = (app, data) => {
    var withData = (data) => {
        app.EventService.send("onCreateTourResponse", data);
    }
    var url = app.UI.AddTourUtility.getTourUrlInputValue(); //TODO redesign.
    app.API.createTour(url).then(withData);
}
export default onAddTourBtnClick;
