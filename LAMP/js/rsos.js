// Set up where the calls will be made to.
var urlBase = 'https://www.goldenknights.systems/API';
var extension = 'php';

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
						localStorage.setItem(rsoname, JSON.stringify(results[i]));
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