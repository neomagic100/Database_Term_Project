var urlBase = 'https://www.goldenknights.systems/API';
var extension = 'php';
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
				if(jsonObject.error == "")
				{
					user_id = jsonObject.user_id;
					user_name = jsonObject.user_name;
	
					// saveCookie();
		
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

	var login = document.getElementById("loginName").value;
	var password = document.getElementById("loginPassword").value;
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

