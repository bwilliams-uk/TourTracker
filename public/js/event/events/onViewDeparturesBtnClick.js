var onViewDeparturesBtnClick = (app, data) => {
    //app.MacroService.run("updateDeparturesTable",data);
    var tourId = data.tourId;
    location.href="?view=departures&filter_tour="+tourId;
};
export default onViewDeparturesBtnClick;
