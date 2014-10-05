/**
 * Created by Hayden on 11/08/14.
 */

$(document).ready(function(){
    InitiateClickingEvents();
});

function InitiateClickingEvents(){
    var isRemoving = false;

    $('.namecard').hover(function (event) {
        var element = $(event.target);
        $("" +
            "<div class = \"popup\">" +
            "<div class = \"popuphead\">" +
            "<img src='IMG/crown.png' class='inline-image popup-image'>" +
            "<h2 class = \"\">Options</h2>" +
            "</div>" +
            "<ul>" +
            "<li class = \"remove\">I can't make it</li>" +
            "</ul>"+
            "</div>").appendTo(element);
    }, function(event){
        // Remove if popup already exists
        if($(".popup").length > 0){
            RemovePopUp();
        }
    });
    $(document).on("click", 'td', function (event) {
        var element = $(event.target);
        // If empty table selected
        if(element.is('span')) {
            element = element.parent();
        }
        // Remove if popup already exists
        if($(".popup").length > 0){
            RemovePopUp();
        }

        // Doesn't support adding shifts anymore
        if(!element.is('td')){
            // If the element hasn't just been removed
            if(!isRemoving){
                // If it's not absent already
                if(!element.hasClass('absent')){
                    $("" +
                        "<div class = \"popup\">" +
                        "<div class = \"popuphead\">" +
                        "<img src='IMG/crown.png' class='inline-image popup-image'>" +
                        "<h2 class = \"\">Options</h2>" +
                        "</div>" +
                        "<ul>" +
                        "<li class = \"remove\">I can't make it</li>" +
                        "</ul>"+
                        "</div>").appendTo(element);
                // If absent
                } else {
                    $("" +
                        "<div class = \"popup\">" +
                        "<div class = \"popuphead\">" +
                        "<img src='IMG/crown.png' class='inline-image popup-image'>" +
                        "<h2 class = \"\">Options</h2>" +
                        "</div>" +
                        "<ul>" +
                        "<li class = \"remove\">I can make it</li>" +
                        "</ul>"+
                        "</div>").appendTo(element);
                }
            }
            isRemoving = false;
        }
    });

    // Click anywhere after popup has been raised
    $(document.body).on("click", function(){
        if(!$(event.target).closest('.remove').length) {
            if($(".popup").length > 0){
                RemovePopUp();
            }
        }
    });

    // If the remove button is clicked
    RecordAbsence();
}

function RecordAbsence(){
    $(document).on("click", ".remove", function(){
        // Adding absent to namecard
        var namecard = $(this).closest('.namecard');
        namecard.addClass("absent");

        // Removing the popup
        var removeDiv = $(this).parent().parent();
        removeDiv.remove();

        // Retrieving the date time and student id
        var dateTimeIDArray = FindDateTimeID(namecard.attr('class'));
        console.log(dateTimeIDArray);

        // Appending to database
        AjaxCall(dateTimeIDArray['date'], dateTimeIDArray['time'], dateTimeIDArray['id']);

        // Ensuring that the popup doesn't reappear
        isRemoving = true;
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