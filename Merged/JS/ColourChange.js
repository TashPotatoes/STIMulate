/**
Author: Pearl Gariano
**/

$(document).ready(function(){
    clickColorEvent(obj);
});

var colors = ["green", "yellow", "red", "white"];
function clickColorEvent(obj){
    obj.colorIndex = obj.colorIndex || 0;
    obj.style.backgroundColor = colors[obj.colorIndex++ % colors.length];
    console.log(obj.style.backgroundColor);
}