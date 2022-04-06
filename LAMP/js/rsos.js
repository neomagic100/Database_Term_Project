// Set up where the calls will be made to.
var urlBase = 'https://www.goldenknights.systems/API';
var extension = 'php';

function returnRSOs_ActiveMember(){
    var tmp = { uid: parseInt(localStorage.getItem('uid')) };
	var jsonPayload = JSON.stringify(tmp);
    var url = urlBase + '/rsosActiveMember.' + extension;
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
					// Go through results and form a new row in the table view you see in the events page.
					for(var i = 0 ; i < results.length; i++)
					{
						// Everything here is building up the HTML that goes inside of the table body (in publicEvents look at the id publicView)
						var res = `results${i+1}`;
						// Start a row.
						list += "<tr>";
						//<td> is the columns.
						// We need the index here because its how I get the information from local storage.
						list += `<td>${i+1}</td>`;
						list += `<td>${results[i].RSOName}</td>`;
						list += `<td>${results[i].RSOType}</td>`;
						
                        // TODO CHANGE
						// Just setting up a button that when clicked retreives the index it's in to open up that information page with the location.
						list += `<td>                       
						<button type="button" id="${i+1}" class="viewButton" 
						onclick="">Leave RSO</button>`;
						list += "</tr>";
						
						// Save results in local storage.
						//localStorage.setItem(activerssos, JSON.stringify(results[i]));
					}
					document.getElementById("RSOActiveView").innerHTML = list;
				}
                else {
                    document.getElementById("RSOActiveView").innerHTML = list;
                } 
				
			}				
		};
		xhr.send(null);
	}
	catch(err)
	{
		document.getElementById("publicView").innerHTML = err.message;
	}
}

// Get a list for a dropdown menu of RSOs
function getRSOs() {
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
						list += `<option value="${name}">${name}</option>`;
						
						// Save results in local storage.
						//localStorage.setItem(rsoname, JSON.stringify(results[i]));
					}
					document.getElementById("rsos").innerHTML = list;
				} 
				
			}				
		};
		xhr.send(null);
	}
	catch(err)
	{
		document.getElementById("publicView").innerHTML = err.message;
	}

}

function joinRSO() {
    alert('joined');
}