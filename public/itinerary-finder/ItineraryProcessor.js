/*
Purpose to receive itinerary and departure
data for the API then combine as relevant
so that information about each itinerary
can be presented*/

class ItineraryProcessor{

    constructor(){
        this.containerElementId = "result";
        this.tourSequence = null ; // Set an array of tour IDs expecting to receive data for.
        this.departureData = []; // Departure Data for each tour. [ nthTour =>
        this.itineraries = null; //Set to Array of valid itineraries.
        this.departureDataRecieveCount = 0;
    }
    setTourSequence(array){
        this.tourSequence = array;
    }

    //FUTURE data should be cached globally so that resubmitting the form does not make another API call.
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
        var container = document.getElementById(this.containerElementId);
        var itineraryCount = this.itineraries.length;
        if(itineraryCount ===0){
            container.innerHTML = "No Itineraries Found.";
            return true;
        }
        container.innerHTML = itineraryCount+" result(s) found.<br><br>";
        for(let i = 0; i < itineraryCount; i++){
            var data = this.getItineraryData(this.itineraries[i]);
            console.log(data);
            var formatter = new ItineraryResultFormatter(data);
            var element = formatter.createElement();
            container.appendChild(element);
        }
    }

    getItineraryData(departureIds){
        var data = [];
        var n = departureIds.length;
        for(let i = 0; i < n; i++){
            data[i] = this.departureData[i][departureIds[i]];
        }
        return data;
    }



}
