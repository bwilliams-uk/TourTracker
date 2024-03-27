var onCreateTourResponse = (app, data) => {
    if (data.success) {
        app.UI.AddTourUtility.setTourUrlInputValue("");
        app.MacroService.run("updateTourInfo");
    } else {
        alert("Failed to create tour: " + data.errorMessage);
    }
}
export default onCreateTourResponse;

