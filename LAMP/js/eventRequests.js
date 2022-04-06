var urlBase = 'https://www.goldenknights.systems/API';
var extension = 'php';
function populateEventRequests() {
    var url = urlBase + '/eventRequests.' + extension;
    var list = "";
    var xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var jsonObject = JSON.parse(xhr.responseText);
                if (jsonObject.error == "") {
                    // Results is a JSON Array {results:[Name, Desc, Type, ...]}
                    var results = jsonObject.results;
                    // Go through results and form a new row in the table view you see in the events page.
                    for (var i = 0; i < results.length; i++) {
                        // Everything here is building up the HTML that goes inside of the table body (in publicEvents look at the id publicView)
                        var res = `results${i + 1}`;
                        // Start a row.
                        list += "<tr>";
                        //<td> is the columns.
                        // We need the index here because its how I get the information from local storage.
                        list += `<td>${results[i].EventName}</td>`;
                        // Just setting up a button that when clicked retreives the index it's in to open up that information page with the location.
                        list += `<td>
						<button type="button" id="${i + 1}" class="acceptButton" 
						onclick="accept(document.getElementById('publicView').rows[${i}].cells[0].innerText);">Accept
						</button>`;
                        list += `<td>
						<button type="button" id="${i + 1}" class="denyButton" 
						onclick="deny(document.getElementById('publicView').rows[${i}].cells[0].innerText);">Decline
						</button>`;
                        list += "</tr>";
                        // Save results in local storage.
                        localStorage.setItem(res, JSON.stringify(results[i]));
                    }
                    document.getElementById("publicView").innerHTML = list;
                }
                else {
                    document.getElementById("publicView").innerHTML = "list";
                }
            }
        };
        xhr.send(null);
    }
    catch (err) {
        document.getElementById("publicView").innerHTML = err.message;
    }
}
function accept(name) {
    // Similar to login, obtain values and send JSON to php.

    var tmp = { name: name, uid: parseInt(localStorage.getItem('uid')) };
    var jsonPayload = JSON.stringify(tmp);
    var xhr = new XMLHttpRequest();
    var url = urlBase + '/accept.' + extension;
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var jsonObject = JSON.parse(xhr.responseText);
                if (jsonObject.error == "") {
                    // This can change just placeholder.
                    location.reload();
                } else {
                    document.getElementById("resultCreate").innerHTML = jsonObject.error;
                }
            }

        };
        xhr.send(jsonPayload);
    }
    catch (err) {
        document.getElementById("result").innerHTML = err.message;
    }
}
function deny(name) {
    var tmp = { name: name };
    var jsonPayload = JSON.stringify(tmp);
    var xhr = new XMLHttpRequest();
    var url = urlBase + '/deny.' + extension;
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var jsonObject = JSON.parse(xhr.responseText);
                if (jsonObject.error == "") {
                    // This can change just placeholder.
                    location.reload();
                } else {
                    document.getElementById("resultCreate").innerHTML = jsonObject.error;
                }
            }

        };
        xhr.send(jsonPayload);
    }
    catch (err) {
        document.getElementById("result").innerHTML = err.message;
    }
}