// Set up where the calls will be made to.
var urlBase = 'https://www.goldenknights.systems/API';
var extension = 'php';

function returnRSOs_ActiveMember(){
	var tmp = { uid: parseInt(localStorage.getItem('uid')) };
	var jsonPayload = JSON.stringify(tmp);
	var xhr = new XMLHttpRequest();
    var url = urlBase + '/rsosActiveMember.' + extension;
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				var jsonObject = JSON.parse( xhr.responseText );
				if(jsonObject.error == "")
				{
					// Results is a JSON Array {results:[Name, Desc, Type, ...]}
					var results = jsonObject.results;
					var list ="";
					// Go through results and form a new row in the table view you see in the events page.
					for(var i = 0 ; i < results.length; i++)
					{
						// Everything here is building up the HTML that goes inside of the table body (in publicEvents look at the id publicView)
						var currRSO = `RSO${i+1}`;
						// Start a row.
						list += "<tr>";
						//<td> is the columns.
						// We need the index here because its how I get the information from local storage.
						list += `<td>${i+1}</td>`;
						list += `<td>${results[i].RSOName}</td>`;
						list += `<td>${results[i].RSOType}</td>`;
						
						// Create an RSO event if user is admin and owner
						list += `<td>                       
						<button type="button" id="create${i+1}" class="viewButton" 
						onclick="setRSOCreate(document.getElementById('RSOActiveView').rows[${i}].cells[0].innerText);window.location.href='https://www.goldenknights.systems/createRSOEvent.html';">Create RSO Event</button>`;
                        
						// Leave the selected RSO
						list += `<td>                       
						<button type="button" id="${i+1}" class="viewButton" 
						onclick="leaveActiveRSO(document.getElementById('RSOActiveView').rows[${i}].cells[0].innerText);">Leave RSO</button>`;
						list += "</tr>";
						
						// Save results in local storage.
						localStorage.setItem(currRSO, JSON.stringify(results[i]));
					}
					//localStorage.setItem("rsoid", parseInt(jsonObject.rsoid));
					document.getElementById("RSOActiveView").innerHTML = list;
				}
                else {
                    document.getElementById("RSOActiveView").innerHTML = "list";
                } 
				
			}				
		};

		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("RSOActiveView").innerHTML = err.message;
	}
}

function returnRSOs_InactiveMember(){
	var tmp = { uid: parseInt(localStorage.getItem('uid')) };
	var jsonPayload = JSON.stringify(tmp);
	var xhr = new XMLHttpRequest();
    var url = urlBase + '/rsosInactiveMember.' + extension;
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				var jsonObject = JSON.parse( xhr.responseText );
				if(jsonObject.error == "")
				{
					// Results is a JSON Array {results:[Name, Desc, Type, ...]}
					var results = jsonObject.results;
					var list ="";
					// Go through results and form a new row in the table view you see in the events page.
					for(var i = 0 ; i < results.length; i++)
					{
						// Everything here is building up the HTML that goes inside of the table body (in publicEvents look at the id publicView)
						var currRSO = `x_RSO${i+1}`;
						// Start a row.
						list += "<tr>";
						//<td> is the columns.
						// We need the index here because its how I get the information from local storage.
						list += `<td>${i+1}</td>`;
						list += `<td>${results[i].RSOName}</td>`;
						list += `<td>${results[i].RSOType}</td>`;
						
                        // Leave the selected RSO
						list += `<td>                       
						<button type="button" id="${i+1}" class="viewButton" 
						onclick="leaveInactiveRSO(document.getElementById('RSOInactiveView').rows[${i}].cells[0].innerText);">Leave RSO</button>`;
						list += "</tr>";
						
						// Save results in local storage.
						localStorage.setItem(currRSO, JSON.stringify(results[i]));
					}
					//localStorage.setItem("rsoid", parseInt(jsonObject.rsoid));
					document.getElementById("RSOInactiveView").innerHTML = list;
				}
                else {
                    document.getElementById("RSOInactiveView").innerHTML = "list";
                } 
				
			}				
		};

		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("RSOInactiveView").innerHTML = err.message;
	}
}

// Leave clicked RSO
function leaveInactiveRSO(row) {
	var res = "x_RSO"+row;
	var rso = JSON.parse(localStorage.getItem(res));
	var jsonPayload = JSON.stringify(rso);
	var xhr = new XMLHttpRequest();
    var url = urlBase + '/leaveRSO.' + extension;
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				var jsonObject = JSON.parse( xhr.responseText );
				if(jsonObject.error == "")
				{
					// This can change just placeholder.
					window.location.href = "success.html";
				} else 
				{
					document.getElementById("RSOInactiveView").innerHTML = jsonObject.error;
				}
			}
				
		};
		xhr.send(jsonPayload);
	}
	catch(err) {

	}

	location.reload();
}

function setRSOCreate(row) {
	var res = "RSO"+row;
	var rso = JSON.parse(localStorage.getItem(res));
	var jsonPayload = JSON.stringify(rso);
	localStorage.setItem("CreateRSOev", jsonPayload);
}


// Leave clicked RSO
function leaveActiveRSO(row) {
	var res = "RSO"+row;
	var rso = JSON.parse(localStorage.getItem(res));
	var jsonPayload = JSON.stringify(rso);
	var xhr = new XMLHttpRequest();
    var url = urlBase + '/leaveRSO.' + extension;
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				var jsonObject = JSON.parse( xhr.responseText );
				if(jsonObject.error == "")
				{
					// This can change just placeholder.
					window.location.href = "success.html";
				} else 
				{
					document.getElementById("RSOActiveView").innerHTML = jsonObject.error;
				}
			}
				
		};
		xhr.send(jsonPayload);
	}
	catch(err) {

	}

	location.reload();

}

// Get a list for a dropdown menu of RSOs
function getRSOs() {
	var tmp = { uid: parseInt(localStorage.getItem('uid')) };
	var jsonPayload = JSON.stringify(tmp);
	var url = urlBase + '/rsosList.' + extension;
	var list ="";
	var xhr = new XMLHttpRequest();
	xhr.open("GET", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				var jsonObject = JSON.parse( xhr.responseText );
				if(jsonObject.error == "")
				{
					// Results is a JSON Array {results:[Name, Desc, Type, ...]}
					var results = jsonObject.results;
					list += `<option value="def">Choose an RSO</option>`
					// Go through results and form a new row in the table view you see in the events page.
					for(var i = 0 ; i < results.length; i++)
					{
						var name = results[i].RSOName;
						var id = results[i].RSOID;
						list += `<option value="${id}">${name}</option>`;
						
						//Save results in local storage.
						//localStorage.setItem(name, JSON.stringify(results[i]));
					}
					document.getElementById("rsoSelect").innerHTML = list;
				} 
				
			}				
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("rsoSelect").innerHTML = err.message;
	}

}

function joinRSO() {
    
	var select = document.getElementById('rsoSelect');
	var selectedRSO = select.value;
	var tmp = { uid: parseInt(localStorage.getItem('uid')) , rsoid: parseInt(selectedRSO)};
	console.log(tmp);
	var jsonPayload = JSON.stringify(tmp);
	var url = urlBase + '/joinRSO.' + extension;
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				var jsonObject = JSON.parse( xhr.responseText );
				if(jsonObject.error == "")
				{
					// Placeholder
					location.reload();
				}
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err) {
        document.getElementById("rsoSelect").innerHTML = err.message;
	}
}

function createRSO() {
	var rsoName = document.getElementById("rsoName").value;
	var rsoType = document.getElementById("rsoType").value;

	var tmp = { RSOName: rsoName, RSOType: rsoType, uid: parseInt(localStorage.getItem("uid")) };
	var jsonPayload = JSON.stringify(tmp);
	var xhr = new XMLHttpRequest();
	var url = urlBase + '/createRSO.' + extension;
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "Application/json, charset=UTF-8");
	try {
		xhr.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var jsonObject = JSON.parse(xhr.responseText);
				if (jsonObject.error == "") {
					location.reload();
				}
				else {
					location.reload();
                }
			}
		};
		xhr.send(jsonPayload);
	}
	catch (err) {

    }

	location.reload();
}