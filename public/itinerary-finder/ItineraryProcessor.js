class ItineraryProcessor{

    constructor(){
        this.tourSequence = null ; // Set an array of tour IDs expecting to receive data for.
        this.departureData = []; // Departure Data for each tour. [ nthTour =>
        this.itineraries = null; //Set to Array of valid itineraries.
        this.departureDataRecieveCount = 0;
    }
    setTourSequence(array){
        this.tourSequence = array;
    }
    receiveDepartureData(tourId,data){
        var formatted = [];

        while(data[0]){
            formatted[data[0].departure_id] = data[0];
            data.splice(0,1);
        }

        var index = this.tourSequence.indexOf(tourId);
        this.departureData[index] = formatted;
        this.departureDataRecieveCount++;
        this.onDataReceived();
    }
    receiveItineraryData(data){
        this.itineraries = data;
        this.onDataReceived();
    }

    onDataReceived(){
        var departureDataLoaded = (this.departureDataRecieveCount === this.tourSequence.length);
        var itineraryDataLoaded = (this.itineraries !== null);

        if(itineraryDataLoaded && departureDataLoaded){
            this.renderResults();
        }
    }

    renderResults(){
        var container = document.getElementById("result");
        var itineraryCount = this.itineraries.length;
        if(itineraryCount ===0){
            container.innerHTML = "No Itineraries Found.";
            return true;
        }
        container.innerHTML = itineraryCount+" result(s) found.<br><br>";
        for(let i = 0; i < itineraryCount; i++){
            var info = this.getDepartureData(this.itineraries[i]);
            var html = this.createHtmlForItinerary(info);
            container.appendChild(html);
        }
    }

    getDepartureData(departureIds){
        var data = [];
        var n = departureIds.length;
        for(let i = 0; i < n; i++){
            data[i] = this.departureData[i][departureIds[i]];
        }
        return data;
    }

    createHtmlForItinerary(info){
        var div = document.createElement("div");
        var aggregates = this.getAggregatedInfo(info);
        var txt = "Start Date : "+aggregates.startDate+"<br>";
        txt += "End Date : "+aggregates.endDate+"<br>";
        txt += "Total Cost : "+aggregates.totalCost+"<br>";
        txt += "Availability : "+aggregates.availability+"<br><br><br>";
        div.innerHTML = txt;
        return div;
    }

    getAggregatedInfo(info){
        console.log(info);
        var agg = {};
        agg.totalCost = 0;
        agg.startDate;
        agg.endDate;
        agg.availability;

        var n = info.length;
        for(let i = 0; i < n; i++){
            var price = parseFloat(info[i].price);
            agg.totalCost += price; // Add price to total cost

            if(i===0){ // set start date/availability for first tour
                agg.startDate = info[i].start_date;
                agg.availability = info[i].availability;
            }
            if(i===n-1){ //set end date for last tour
                agg.endDate = info[i].end_date;
            }
            // Reduce availability if less than previous
            if(info[i].availability < agg.availability){
                agg.availability = info[i].availability;
            }
        }
        return agg;
    }

}
