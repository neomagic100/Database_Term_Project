var urlBase = 'https://www.goldenknights.systems/API';
var extension = 'php';

function createRSOEvent() {
    var rsoInfo = JSON.parse(localStorage.getItem("CreateRSOev"));
    var lat = document.getElementById('cityLat').value;
    var long = document.getElementById('cityLng').value;
    var eventName = document.getElementById('eventName').value;
    var eventCat = document.getElementById("eventCat").value;
    var descrip = document.getElementById("description").value;
    var lname = document.getElementById('locationName').value;
    var addr = document.getElementById('location').value;
    var date = document.getElementById("date").value;
    var start = document.getElementById('startTime').value;
    var end = document.getElementById('endTime').value;
    var tmp = {
        eventname: eventName, uid: parseInt(localStorage.getItem('uid')),
        eventcat: eventCat, descrip: descrip, date: date, start: start,
        end: end, lname: lname, lat: lat, long: long, address: addr
    };
    tmp = union(tmp, rsoInfo);
    var jsonPayload = JSON.stringify(tmp);
    var xhr = new XMLHttpRequest();
    var url = urlBase + '/createRSOEvent.' + extension;
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var jsonObject = JSON.parse(xhr.responseText);
                if (jsonObject.error == "") {
                    //closeModal();
                    document.getElementById("resultCreate").innerHTML = "Added!";
                    //location.reload();
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

function addPublicEvent() {
    //var addr = document.getElementById('city2').value;
    var lat = document.getElementById('cityLat').value;
    var long = document.getElementById('cityLng').value;
    var eventName = document.getElementById('eventName').value;
    var eventCat = document.getElementById("eventCat").value;
    var descrip = document.getElementById("description").value;
    var lname = document.getElementById('locationName').value;
    var addr = document.getElementById('location').value;
    var date = document.getElementById("date").value;
    var start = document.getElementById('startTime').value;
    var end = document.getElementById('endTime').value;
    var tmp = { eventname: eventName, uid: parseInt(localStorage.getItem('uid')),
    eventcat: eventCat, descrip: descrip, date:date, start: start,
    end:end, lname:lname, lat:lat, long:long, address:addr  };
    console.log(tmp);
    var jsonPayload = JSON.stringify(tmp);
    var xhr = new XMLHttpRequest();
    var url = urlBase + '/createPublicEvent.' + extension;
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var jsonObject = JSON.parse(xhr.responseText);
                if (jsonObject.error == "") {
                    //closeModal();
                    document.getElementById("resultCreate").innerHTML = "Added!";
                    //location.reload();
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

function addPrivateEvent() {
    //var addr = document.getElementById('city2').value;
    var long = document.getElementById('cityLng').value;
    var lat = document.getElementById('cityLat').value;
    var eventName = document.getElementById('eventName').value;
    var eventCat = document.getElementById("eventCat").value;
    var descrip = document.getElementById("description").value;
    var lname = document.getElementById('locationName').value;
    var addr = document.getElementById('location').value;
    var date = document.getElementById("date").value;
    var start = document.getElementById('startTime').value;
    var end = document.getElementById('endTime').value;
    var tmp = {
        eventname: eventName, uid: parseInt(localStorage.getItem('uid')),
        eventcat: eventCat, descrip: descrip, date: date, start: start,
        end: end, lname: lname, lat: lat, long: long, address: addr,
        uniid:parseInt(localStorage.getItem("uniid"))
    };
    var jsonPayload = JSON.stringify(tmp);
    var xhr = new XMLHttpRequest();
    var url = urlBase + '/createPrivateEvent.' + extension;
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var jsonObject = JSON.parse(xhr.responseText);
                if (jsonObject.error == "") {
                    //closeModal();
                    document.getElementById("resultCreate").innerHTML = "Added!";
                    //location.reload();
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