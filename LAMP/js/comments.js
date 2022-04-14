var urlBase = 'https://www.goldenknights.systems/API';
var extension = 'php';
function deleteComment(eid, row) {
    var tmp = { uid: parseInt(localStorage.getItem('uid')), eid: eid };
    var jsonPayload = JSON.stringify(tmp);
    var xhr = new XMLHttpRequest();
    var url = urlBase + '/deleteComment.' + extension;
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var jsonObject = JSON.parse(xhr.responseText);
                if (jsonObject.error == " ") {
                    closeModal();
                    setTimeout(function () { openModal(row); }, 1000);
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
function editComment(eid, row)
{
    var cmntP = document.getElementById('cmnt');
    document.getElementById('delBut').remove();
    var cmnt = cmntP.innerHTML;
    setTimeout(() => {cmntP.remove();}, 200);
    var cmntDiv = document.getElementById(`cmntdiv${localStorage.getItem("uid")}`);
    var area = document.createElement('textarea');
    area.setAttribute('id', 'editarea');
    area.innerText = cmnt;
    var button = document.createElement('button');
    button.innerText = "Submit!";
    button.setAttribute('onClick',`edit(${row}, ${eid})`);
    cmntDiv.appendChild(area);
    cmntDiv.appendChild(button);
}
function edit(row, eid)
{
    var cmnt = document.getElementById("editarea").value;
    var tmp = { cmnt: cmnt, uid: parseInt(localStorage.getItem('uid')), eid: eid};
    var jsonPayload = JSON.stringify(tmp);
    var xhr = new XMLHttpRequest();
    var url = urlBase + '/editComment.' + extension;
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var jsonObject = JSON.parse(xhr.responseText);
                if (jsonObject.error == " ") {
                    //closeModal();
                    closeModal();
                    setTimeout(function () { openModal(row); }, 1000);
                    //location.reload();
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
function submitComment(eid, row) {
    var comment = document.getElementById('cBox').value;
    var rating = document.getElementById('rating').value;
    var tmp = { cmnt: comment, uid: parseInt(localStorage.getItem('uid')), eid: eid, rating: parseInt(rating) };
    var jsonPayload = JSON.stringify(tmp);
    var xhr = new XMLHttpRequest();
    var url = urlBase + '/submitcomment.' + extension;
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var jsonObject = JSON.parse(xhr.responseText);
                if (jsonObject.error == " ") {
                    //closeModal();
                    closeModal();
                    setTimeout(function(){openModal(row);}, 1000);
                    //location.reload();
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
function getComments(eid) {
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