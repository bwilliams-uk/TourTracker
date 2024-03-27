var onTourDeleteBtnClick = (app, data) => {

    var withData = (data) => {
        app.MacroService.run("updateTourInfo");
    };

    var tourId = data.tourId;
    var tourName = data.tourName;
    var confirm = window.confirm("Are you sure you want to delete tour '" + tourName + "'? All price and availability data will be removed.");
    if (confirm) {
        app.API.deleteTour(tourId).then(withData);
    }
}
export default onTourDeleteBtnClick;
