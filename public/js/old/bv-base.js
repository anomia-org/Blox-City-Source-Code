/*
-------------------------------------------------------------------------------
Buildaverse JS Core
Version: 0.0.1
https://buildaverse.com/
Copyright 2022. All rights reserved.
-------------------------------------------------------------------------------
*/


var buildaverse = {

}


window.addEventListener('toastDanger',function(e){
    toastDangerAlert(e.title, e.content);
});
window.addEventListener('toastSuccess',function(e){
    toastDangerAlert(e.title, e.content);
});

function toastDangerAlert(title, content) {
    halfmoon.initStickyAlert({
        content: content,
        title: title,
        alertType: "alert-danger",
        fillType: "filled"
    });
}

function toastSuccessAlert(title, content) {
    halfmoon.initStickyAlert({
        content: content,
        title: title,
        alertType: "alert-success",
        fillType: "filled"
    });
}
