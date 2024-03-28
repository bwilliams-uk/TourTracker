        //Begin function definitons

        function setDefaultDateFields() {
            var sd = document.getElementById("start-date");
            var ed = document.getElementById("end-date");
            var sdDefault = new Date(Date.now() + 30 * 24 * 3600 * 1000); // 30 days from now
            var edDefault = new Date(Date.now() + 180 * 24 * 3600 * 1000); // 180 days from now
            var sdFormat = sdDefault.toISOString().split('T')[0];
            var edFormat = edDefault.toISOString().split('T')[0];
            sd.value = sdFormat;
            ed.value = edFormat;

        }

        function createSelectOption(selectNode, value, text) {
            var opt = document.createElement("option");
            opt.setAttribute('value', value);
            opt.innerHTML = text;
            selectNode.appendChild(opt);
        }

        function addToAllSelects(value, text) {
            var n = selects.length;
            for (let i = 0; i < n; i++) {
                createSelectOption(selects[i], value, text);
            }
        }

        function handleTourData(data) {
            var n = data.length;
            for (let i = 0; i < n; i++) {
                addToAllSelects(data[i].tour_id, data[i].tour_name+' | '+data[i].duration_days+' days | '+data[i].operator_name);
            }
        }

        //End function defintions


        setDefaultDateFields();

        var btn = document.getElementsByTagName("button")[0];
        var handler = new FormSubmissionHandler();
        btn.addEventListener("click", () => handler.onSubmit());

        var selects = document.querySelectorAll("select[id^='tour-']");
        addToAllSelects(0, "None");
        $.get("../report/tours", null, handleTourData);
