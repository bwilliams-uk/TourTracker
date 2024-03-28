/*
Purpose: to recieve data pertaining to a
single itinerary and format it as an html
element.
*/

class ItineraryResultFormatter{

    constructor(data){
        this.data = data; // Contains an array of departure Objects forming the itinerary.
    }

    createElement(){
        var div = document.createElement("div");
        var aggregates = this.getAggregatedInfo(this.data);
        var txt = "Start Date : "+aggregates.startDate+"<br>";
        txt += "End Date : "+aggregates.endDate+"<br>";
        txt += "Total Cost : "+aggregates.totalCost+"<br>";
        txt += "Availability : "+aggregates.availability+"<br><br>";
        div.innerHTML = txt;
        var table = this.generateDeparturesTable(this.data);
        div.appendChild(table);
        div.innerHTML += "<br><br><br>";
        return div;
    }

    // Iterate through the departures to get aggregated info e.g. total cost.
    getAggregatedInfo(info){
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

    generateDeparturesTable(info){
        var t = document.createElement('table');
        var h = document.createElement('tr');
        var headings = ['Tour Name', 'Start Date', 'End Date','Availability','Price','Last Updated'];

        //Create heading cells
        headings.forEach((txt)=>{
            var cell = document.createElement('th');
            cell.innerHTML = txt;
            h.appendChild(cell);
        });
        //Append heading row to table
        t.appendChild(h);

        // Function for adding text to sync field
        var formatSync = function(sync){
            if(sync === 0){
                return "Today";
            }
            else if(sync === 1){
                return "Yesterday";
            }
            else{
                return sync+' days ago';
            }
        };

        //Define create row function
        var createRow = (tourName,startDate,endDate,availability,price,sync)=>{
            var sync = formatSync(sync);
            var array = [tourName,startDate,endDate,availability,price,sync];
            var tr = document.createElement('tr');
            array.forEach((txt)=>{
                var cell = document.createElement('td');
                cell.innerHTML = txt;
                tr.appendChild(cell);
            });
            return tr;
        };

        //Create rows and append to table.
        info.forEach((departure)=>{
            var row = createRow(departure.tour_name,
                                departure.start_date,
                                departure.end_date,
                                departure.availability,
                                departure.price,
                                departure.sync_days_ago);
            t.appendChild(row);
        });

        //return table
        return t;
    }
}
