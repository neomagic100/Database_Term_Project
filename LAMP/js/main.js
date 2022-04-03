// Set up where the calls will be made to.
var urlBase = 'https://www.goldenknights.systems/API';
var extension = 'php';

function getName()
{
	// In a browser session, retrieve from the key 'Name' from local storage. 
	document.getElementById("Name").innerHTML=localStorage.getItem("Name");
}
// Defunct will delete later.
// function goToPublic()
// {
// 	// Redirects browser to the 
// 	window.location.href= "publicEvents.html";
// }
function doLogin()
{
	userId = 0;
	Name = "";
	// In the html get the value of whatever is in the fields. (In this case whatever is in the loginName/password input.)
	var login = document.getElementById("loginName").value;
	var password = document.getElementById("loginPassword").value;
	//"result" is like the error message you get (e.g. Invalid password!). This just sets it as blank. 
	document.getElementById("result").innerHTML = "";
	// This is what gets sent to the .php file (backend) just formatted as JSON.
	var tmp = {Login:login,Password:password};
	// Formats tmp from JSON into a string, since JSON is its own datatype here.
	var jsonPayload = JSON.stringify(tmp);
	// Where to call to? 
	var url = urlBase + '/login.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
  	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		// This function happens when we get a response.
		xhr.onreadystatechange = function() 
		{
			// This just tells us if the response was succesful 
			if (this.readyState == 4 && this.status == 200) 
			{
				// Response text is a string, parse it and format it into a JSON datatype/
				var jsonObject = JSON.parse( xhr.responseText );
				if(jsonObject.error == "")
				{
					user_id = jsonObject.id;
					user_name = jsonObject.Name;
					uid = jsonObject.uid;
					// Save the name and uid into local storage for future calls/
					localStorage.setItem("Name", user_name);
					localStorage.setItem("uid", uid);
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
	catch(err)
	{
		document.getElementById("result").innerHTML = err.message;
	}
 }
 function createAccount()
{
	// Similar to login, obtain values and send JSON to php.
	var login = document.getElementById("createName").value;
	var password = document.getElementById("createPassword").value;
	var email = document.getElementById("email").value;
	var name = document.getElementById("nameid").value;
	
	var tmp = {Login:login, Password:password, Email:email, Name:name};
	var jsonPayload = JSON.stringify( tmp );
	var xhr = new XMLHttpRequest();
	var url = urlBase + '/register.' + extension;
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
					window.location.href = "created.html";
				} else 
				{
					document.getElementById("resultCreate").innerHTML = jsonObject.error;
				}
			}
				
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("result").innerHTML = err.message;
	}
}

// Create a new University Profile
function createUniversity() {
	var uni_name = document.getElementById("createUniName").value;
	var numStudents = document.getElementById("createNumStudents").value;
	var addr = document.getElementById("createUniAddr").value;
	var descrip = document.getElementById("createUniDescription").value;

	var tmp = {University:uni_name, NumberStudents:numStudents, Address:addr, Description:descrip};
	var jsonPayload = JSON.stringify( tmp );
	var xhr = new XMLHttpRequest();
	var url = urlBase + '/createUniversity.' + extension;
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
					window.location.href = "created.html";
				} else 
				{
					document.getElementById("resultCreate").innerHTML = jsonObject.error;
				}
			}
				
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("result").innerHTML = err.message;
	}
}

function returnPublicEvent()
{
	var url = urlBase + '/publicView.' + extension;
	var list = "";
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
						list += `<td>${results[i].EventName}</td>`;
						list += `<td>${results[i].Description}</td>`;
						list += `<td>${results[i].EventType}</td>`;
						list += `<td>${results[i].EventDate}</td>`;
						list += `<td>${results[i].EventStart}</td>`;
						list += `<td>${results[i].EventEnd}</td>`;
						// Just setting up a button that when clicked retreives the index it's in to open up that information page with the location.
						list += `<td>
						<button type="button" id="${i+1}" class="viewButton" 
						onclick="openModal(document.getElementById('publicView').rows[${i}].cells[0].innerText);">View this Event
						</button>`;
						list += "</tr>";
						// Save results in local storage.
						localStorage.setItem(res, JSON.stringify(results[i]));
					}
					document.getElementById("publicView").innerHTML = list;
				} 
				else
				{
					document.getElementById("publicView").innerHTML = "list";
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
var modal; 
function openModal(row)
{
	// This is the black transparent background when you click View This Event, holds the white box inside of it with the location information.
	const modal_container = document.getElementById('container');
	// Save the current state of the html (it's empty), this is just used to clean out the container of the informatiopn.
	localStorage.setItem("currstate", modal_container.innerHTML);
	res = "results"+row;
	// Same results array as returnPublicEvent();
	results = JSON.parse(localStorage.getItem(res));
	//This enables the .css which allows us to see our focused Event View.
	modal_container.classList.add('show');
	modal = document.getElementById('left');
	// the create element is equivalent to adding <h1> EventName </h1 > basically, its just an easier way to update the HTML.
	// Essentially everything from here is forming the Event View you see (when you click View this event)
	var header = document.createElement("h1");
	var text = document.createTextNode(results.EventName);
	
	header.appendChild(text);
	var para = document.createElement("p");
	var ptext = document.createTextNode(results.Description);
	para.append(ptext);
	var date = document.createElement("p");
	var dateText = document.createTextNode(`${results.EventDate} - From ${results.EventStart} To ${results.EventEnd}`);
	date.appendChild(dateText);
	// Calls php which returns to us the location of the event. If it's empty, event page is empty.
	getLocation(results.Eventid);
	getComments(results.Eventid);
	// Why do we setTimeout? Because since js isn't sequential we can do all this work below and the location will not be in local storage
	// even though we called getLocation before it. This is why when you click View This Event it hands for a fraction of a second.
	setTimeout(function(){
		var locJ = localStorage.getItem(`location${results.Eventid}`);
		var loc = JSON.parse(locJ);
		if(loc.lname != "")
		{
			// Location
			var locHead = document.createElement("h1");
			var locT = document.createTextNode("The Location");
			var locP = document.createElement("p");
			var locName = document.createTextNode(loc.lname);
			var locationInformation = document.createElement("p");
			var locText = document.createTextNode(`Latitude: ${loc.latitude} Longitude: ${loc.longitude}`);
			var address = document.createElement("p");
			var addrT = document.createTextNode(loc.addr);
			locHead.appendChild(locT);
			locP.appendChild(locName);
			locationInformation.appendChild(locText);
			address.appendChild(addrT);
			
			modal.appendChild(header);
			modal.appendChild(para);
			modal.appendChild(date);
			
			modal.appendChild(locHead);
			modal.appendChild(locP);
			modal.appendChild(locationInformation);
			modal.appendChild(address);
			//Comments
			var comments = localStorage.getItem(`comments${results.Eventid}`);
			comments = JSON.parse(comments);
			var h = document.createElement('h1');
			var ht = document.createTextNode('Comments');
			h.appendChild(ht);
			modal.appendChild(h);
			for(var i = 0; i < comments.length; i++)
			{
				var commentdiv = document.createElement('div');
				commentdiv.classList.add('indcomment');
				var username = document.createTextNode(comments[i].user);
				var time = document.createTextNode(comments[i].time);
				var comment = document.createTextNode(comments[i].cmnt);
				var stars = "";
				for (var j = 0; j < comments[i].rating && j < 5; j++)  stars += '\u2605'
				var rating = document.createTextNode(stars);
				var p = document.createElement('p');
				p.appendChild(username);
				p.innerHTML += '<br>'
				p.appendChild(time);
				p.innerHTML += '<br>'
				p.appendChild(comment);
				p.innerHTML += '<br>'
				p.appendChild(rating);
				commentdiv.appendChild(p);
				modal.appendChild(commentdiv);
			}
			//comment form
			var bright = document.getElementById("bright");
			var area = document.createElement('textarea');
			area.setAttribute('id', 'cBox');
			area.setAttribute('name', 'cBox');
			area.setAttribute('placeholder', 'Add your comment here.');
			area.setAttribute('rows', 5);
			area.setAttribute('cols', 100);
			var button = document.createElement('button');
			button.setAttribute('id', 'submit');
			button.setAttribute('onClick', `submitComment(${results.Eventid});`);
			var buttonText = document.createTextNode("Submit!");
			button.appendChild(buttonText);
			button.classList.add('submit');
			bright.appendChild(area);
			bright.appendChild(button)

			initMap(results.Eventid);
		}
	}, 250);
	// localStorage.removeItem(`location${results.Eventid}`);
	// localStorage.removeItem(`comments${results.Eventid}`);
}
function submitComment(eid)
{
	var comment = document.getElementById('cBox').value;
	var rating = document.getElementById('rating').value;
	var tmp = { cmnt:comment, uid:parseInt(localStorage.getItem('uid')), eid:eid, rating:parseInt(rating)};
	console.log(tmp);
	var jsonPayload = JSON.stringify(tmp);
	var xhr = new XMLHttpRequest();
	var url = urlBase + '/submitcomment.' + extension;
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try {
		xhr.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var jsonObject = JSON.parse(xhr.responseText);
				if (jsonObject.error ==  " ") {
					//document.getElementById("result").innerHTML = "Success!";
					closeModal();
				} else {
					document.getElementById("result").innerHTML = jsonObject.error;
				}
			}
		};
		xhr.send(jsonPayload);
	}
	catch (err) {
		document.getElementById("result").innerHTML = err.message;
	}
}
function getComments(eid)
{
	var tmp = { event_id: eid };
	var jsonPayload = JSON.stringify(tmp);
	var xhr = new XMLHttpRequest();
	var url = urlBase + '/loadComments.' + extension;
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try {
		xhr.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var jsonobj = JSON.parse(xhr.responseText);
				var res = jsonobj.comments;
				if (jsonobj != null) {
					localStorage.setItem(`comments${eid}`, JSON.stringify(res));
				}
			}
		};
		xhr.send(jsonPayload);
	}
	catch (err) {
	}
}	
 function getLocation(eid)
{
	// calls locatedAt.php. 
	var tmp = {event_id:eid};
	var jsonPayload = JSON.stringify( tmp );
	var xhr = new XMLHttpRequest();
	var url = urlBase + '/locatedAt.' + extension;
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{ 
				var jsonobj = JSON.parse(xhr.responseText);
				if(jsonobj != null) 
				{
					localStorage.setItem(`location${eid}`, JSON.stringify(jsonobj));
				}
			}		
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
	}
}
// This is the Google API code. Not %100 sure on the technicals of it, but it works. 
// Any changes we would make are the map and marker const. 
var map;
function initMap(eid) {
	var loc = JSON.parse(localStorage.getItem(`location${eid}`));
    // The location of Uluru
	const location = { lat: parseFloat(loc.latitude), lng: parseFloat(loc.longitude)};
	// The map, centered at Uluru
	const map = new google.maps.Map(document.getElementById("map"), {
	  zoom: 18,
	  center: location,
	});
	// The marker, positioned at Uluru
	const marker = new google.maps.Marker({
	  position: location,
	  map: map,
	});
  
}
// when you click close on the events, it closes the modal_container, and resets the container back to that currstate from before.
function closeModal(){
	const modal_container = document.getElementById('container');
	modal_container.classList.remove('show');
	modal_container.innerHTML = localStorage.getItem("currstate");
}



