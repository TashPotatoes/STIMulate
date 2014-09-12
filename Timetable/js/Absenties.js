/**
 * Created by Hayden on 11/08/14.
 */

$(document).ready(function(){
    $(document).on("click", ".namecard", function(){

        if($(".popup").length > 0){
            RemovePopUp();
        }

        $("" +
            "<div class = \"popup\">" +
            "<fieldset>" +
            "<p class = \"remove\">I can't make it</p>" +
            "</fieldset>" +
            "</div>").appendTo($(this));
    });


    $(document).on("click", ".remove", function(){
        //var dateTime = $(this).closest("a").find("input").val().split('d');
        //AjaxCall(dateTime[1], dateTime[0]);
        $(this).closest("span").remove();
    });

    $(document.body).on("click", function(){
        if(!$(event.target).closest('.remove').length) {
            if($(".popup").length > 0){
                RemovePopUp();
            }
        }

    });
});

function AjaxCall(date, time){
    $.ajax({ url: 'PHP/DatabaseAPI.php',
        data: {
            "action": 'absent',
            "date" : date,
            "time" : time,
            "volunteerID" : document.getElementsByName("volID")[0].value
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