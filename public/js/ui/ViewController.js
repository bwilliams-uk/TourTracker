export default class ViewController{
    hideAll() {
        var views = document.getElementsByClassName("view");
        var n = views.length;
        for(let i = 0; i < n; i++){
            views[i].style.display = "none";
        }
    }
    show(name){
        var el = document.querySelector(`.view[data-view-name="${name}"]`);
        el.style.display = "block";
    }
}
