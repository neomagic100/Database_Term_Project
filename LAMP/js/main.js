var urlBase = 'http://https://www.goldenknights.systems/API';
var extension = 'php';
const { createHash } = require('crypto');
var userId = 0;
var Name = "";
var b = "";
var tmp_id = -100;

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
				user_id = jsonObject.user_id;
		
				if( userId < 1 )
				{		
					document.getElementById("result").innerHTML = "User/Password combination incorrect";
					return;
				}
		
				user_name = jsonObject.user_name;

				saveCookie();
	
				window.location.href = "success.html";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("result").innerHTML = err.message;
	}
 }