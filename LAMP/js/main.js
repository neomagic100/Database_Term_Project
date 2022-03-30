var urlBase = 'https://www.goldenknights.systems/API';
var extension = 'php';

function getName()
{
	document.getElementById("Name").innerHTML=localStorage.getItem("Name");
}
function goToPublic()
{
	window.location.href= "publicEvents.html";
}
function doLogin()
{
	userId = 0;
	Name = "";
	var login = document.getElementById("loginName").value;
	var password = document.getElementById("loginPassword").value;
	document.getElementById("result").innerHTML = "";
	var tmp = {Login:login,Password:password};
	var jsonPayload = JSON.stringify(tmp);
	var url = urlBase + '/login.' + extension;

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
					user_id = jsonObject.id;
					user_name = jsonObject.Name;
					uid = jsonObject.uid;
					localStorage.setItem("Name", user_name);
					localStorage.setItem("uid", uid);
		
					window.location.href = "success.html";
				} else {
					document.getElementById("result").innerHTML = jsonObject.error;
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
 function createAccount()
{

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
					var results = jsonObject.results;
					for(var i = 0 ; i < results.length; i++)
					{
						var res = `results${i+1}`;
						list += "<tr>";
						list += `<td>${i+1}</td>`;
						list += `<td>${results[i].EventName}</td>`;
						list += `<td>${results[i].Description}</td>`;
						list += `<td>${results[i].EventType}</td>`;
						list += `<td>${results[i].EventDate}</td>`;
						list += `<td>${results[i].EventStart}</td>`;
						list += `<td>${results[i].EventEnd}</td>`;
						list += `<td>
						<button type="button" id="${i+1}" class="viewButton" 
						onclick="openModal(document.getElementById('publicView').rows[${i}].cells[0].innerText);">View this Event
						</button>`;
						list += "</tr>";
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
	
	const modal_container = document.getElementById('container');
	localStorage.setItem("currstate", modal_container.innerHTML);
	res = "results"+row;
	results = JSON.parse(localStorage.getItem(res));
	modal_container.classList.add('show');
	modal = document.getElementById('contain');
	var header = document.createElement("h1");
	var text = document.createTextNode(results.EventName);
	
	header.appendChild(text);
	var para = document.createElement("p");
	var ptext = document.createTextNode(results.Description);
	para.append(ptext);
	var date = document.createElement("p");
	var dateText = document.createTextNode(`${results.EventDate} - From ${results.EventStart} To ${results.EventEnd}`);
	date.appendChild(dateText);
	getLocation(results.Eventid);
	setTimeout(function(){
		var locJ = localStorage.getItem(`location${results.Eventid}`);
		console.log(locJ)
		var loc = JSON.parse(locJ);
		if(loc.lname != "")
		{
			initMap(results.Eventid);
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
		}
	}, 250);
}
 function getLocation(eid)
{
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

function closeModal(){
	const modal_container = document.getElementById('container');
	modal_container.classList.remove('show');
	modal_container.innerHTML = localStorage.getItem("currstate");
}



