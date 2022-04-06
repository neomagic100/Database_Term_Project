var urlBase = 'https://www.goldenknights.systems/API';
var extension = 'php';
function doLogin() {
    userId = 0;
    Name = "";
    // In the html get the value of whatever is in the fields. (In this case whatever is in the loginName/password input.)
    var login = document.getElementById("loginName").value;
    var password = document.getElementById("loginPassword").value;
    //"result" is like the error message you get (e.g. Invalid password!). This just sets it as blank. 
    document.getElementById("result").innerHTML = "";
    // This is what gets sent to the .php file (backend) just formatted as JSON.
    var tmp = { Login: login, Password: password };
    // Formats tmp from JSON into a string, since JSON is its own datatype here.
    var jsonPayload = JSON.stringify(tmp);
    // Where to call to? 
    var url = urlBase + '/login.' + extension;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
    try {
        // This function happens when we get a response.
        xhr.onreadystatechange = function () {
            // This just tells us if the response was succesful 
            if (this.readyState == 4 && this.status == 200) {
                // Response text is a string, parse it and format it into a JSON datatype/
                var jsonObject = JSON.parse(xhr.responseText);
                if (jsonObject.error == "") {
                    user_id = jsonObject.id;
                    user_name = jsonObject.Name;
                    uid = jsonObject.uid;
                    var status = jsonObject.userStatus;
                    // Save the name and uid into local storage for future calls/
                    localStorage.setItem("Name", user_name);
                    localStorage.setItem("uid", uid);
                    localStorage.setItem("status", jsonObject.userStatus);
                    // Move us to the main events page.
                    window.location.href = "success.html";
                } else {
                    // If there was an error, display it (e.g. No user 'bop' exists)
                    document.getElementById("result").innerHTML = jsonObject.error;
                }
            }
        };
        // Sends the JSON to the php file and exectutes function above when a response is received.
        xhr.send(jsonPayload);
    }
    catch (err) {
        document.getElementById("result").innerHTML = err.message;
    }
}
function createAccount() {
    // Similar to login, obtain values and send JSON to php.
    var login = document.getElementById("createName").value;
    var password = document.getElementById("createPassword").value;
    var email = document.getElementById("email").value;
    var name = document.getElementById("nameid").value;
    var university = document.getElementById("unis").value;
    var uniId = JSON.parse(localStorage.getItem(university)).UniversityID;
    var tmp = { Login: login, Password: password, Email: email, Name: name, University: parseInt(uniId) };
    var jsonPayload = JSON.stringify(tmp);
    var xhr = new XMLHttpRequest();
    var url = urlBase + '/register.' + extension;
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

// Get a list for a dropdown menu of universities
function getUniversities() {
    var url = urlBase + '/unisList.' + extension;
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
                    list += `<option value="def">Choose a University</option>`
                    // Go through results and form a new row in the table view you see in the events page.
                    for (var i = 0; i < results.length; i++) {
                        var name = results[i].UniversityName;
                        list += `<option value="${name}">${name}</option>`;

                        // Save results in local storage.
                        localStorage.setItem(name, JSON.stringify(results[i]));
                    }
                    document.getElementById("unis").innerHTML = list;
                }

            }
        };
        xhr.send(null);
    }
    catch (err) {
        document.getElementById("publicView").innerHTML = err.message;
    }

}
