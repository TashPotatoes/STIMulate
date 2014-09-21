/**
 * Created by Hayden on 11/08/14.
 */

$(document).ready(function(){
    InitiateClickingEvents();
});

function InitiateClickingEvents(){
    $(document).on("click", ".namecard", function(){
        if($(".popup").length > 0){
            RemovePopUp();
        }

        $("" +
            "<div class = \"popup\">" +
            "<div class = \"popuphead\">" +
            "<img src='IMG/plf.png' class='inline-image popup-image'>" +
            "<h2 class = \"\">Options</h2>" +
            "</div>" +
            "<ul>" +
            "<li class = \"remove\">I can't make it</li>" +
            "</ul>"+
            "</div>").appendTo($(this));
    });


    $(document).on("click", ".remove", function(){
        var dateTime = $(this).closest(".namecard").parent().html();
        var dateTimeIDArray = FindDateTimeID(dateTime);
        console.log(dateTimeIDArray);
        //AjaxCall(dateTimeIDArray['date'], dateTimeIDArray['time'], dateTimeIDArray['id']);
        $(this).closest(".namecard").addClass("absent");
        RemovePopUp();

    });

    $(document.body).on("click", function(){
        if(!$(event.target).closest('.remove').length) {
            if($(".popup").length > 0){
                RemovePopUp();
            }
        }

    });
}
function AjaxCall(date, time, id){
    console.log(date);
    console.log(time);
    console.log(id);
    $.ajax({ url: 'PHP/DatabaseAPI.php',
        data: {
            "action": 'absent',
            "date" : date,
            "time" : time,
            "volunteerID" : id
        },
        type: 'post',
        datatype: 'JSON',

        success: SuccessfulCall,
        error: UnsuccessfulCall
    });

}

function SuccessfulCall(output){
    if(output != null && output != "" && output != "[]"){
        console.log("Return Value.\n" +output);
    } else {
        console.log("Nothing Returned. Continuing");
    }
}

function UnsuccessfulCall(){
    console.log("Ajax request failed.");
}

function RemovePopUp(){
    $(".popup").remove();
    $(".remove").remove();
}

function FindDateTimeID(html){
    var dayNumberPattern = "[0-9]";
    var timeNumberPattern = "[0-9]+:[0-9]+";
    var idPattern = "n[0-9]+";
    // Matching date and time
    var dayNumberPatternMatch = html.match(dayNumberPattern)[0];
    var timeNumberPatternMatch = html.match(timeNumberPattern)[0];
    var idPatternMatch = html.match(idPattern)[0];

    // Putting into assoc array and returnign
    var dateTimeID = {date:dayNumberPatternMatch, time:timeNumberPatternMatch, id:idPatternMatch};
    return dateTimeID;
}