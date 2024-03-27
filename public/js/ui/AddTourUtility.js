export default class AddTourUtility {
    constructor(EventService) {
        this.EventService = EventService;
        this.createAddTourBtnEventListener();
    }

    //TODO Move to 'AddTourUtility' Class
    createAddTourBtnEventListener() {
        var es = this.EventService;
        var fn = () => es.send("onAddTourBtnClick");
        document.getElementById("btn-add-tour").addEventListener("click", fn);
    }

    //TODO create an 'AddTourUtility' child object to handle this.
    getTourUrlInputValue() {
        return document.getElementById("inp-tour-url").value;
    }
    //TODO create an 'AddTourUtility' child object to handle this.
    setTourUrlInputValue(url) {
        document.getElementById("inp-tour-url").value = url;
    }

}
