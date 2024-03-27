export default class UrlUtils {
    constructor() {
        this.url = window.location;
    }
    getParamValue(param){
        var url = new URL(this.url);
        return url.searchParams.get(param);
    }
}
