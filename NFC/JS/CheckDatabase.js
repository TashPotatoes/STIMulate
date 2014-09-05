/**
 * Created by crazygravy89 on 31/07/14.
 */
$(document).ready(function() {
    AjaxCall();
    setInterval(AjaxCall, 2500);
});

function AjaxCall(){
    if(document.getElementsByName("studentID")[0].value == "" && $(".newStudent").length == 0){
        $.ajax({ url: 'PHP/DatabaseAPI.php',
            data: {
                "action": 'refresh',
                "readerID" : document.getElementsByName("readerID")[0].value
            },
            type: 'post',
            datatype: 'JSON',

            success: SuccessfulCall,
            error: UnsuccessfulCall
        });
    }
}

function SuccessfulCall(output){

    if(output != null && output != "" && output != "[]"){
        //$("#test").html(output);
        var readerInformation = $.parseJSON(output);
        var timeStamp = PhpDateToHTML(readerInformation[0]['TimeStamp']);
        document.getElementsByName("timestamp")[0].value = timeStamp;

        if(readerInformation[0]['newStudent']){
            $("#signInLegend").after("<p class = \"newStudent\">New Student!</p>");
            $("input:hidden[name = nfcTag]").val(readerInformation[0]["nfcTag"]);

        } else {
            document.getElementsByName("studentID")[0].value = readerInformation[0]['studentNumber'];
            document.getElementsByName("name")[0].value = readerInformation[0]['name'];
            document.getElementsByName("email")[0].value = readerInformation[0]['email'];

            ReadOnly(["studentID", "name", "email"]);
        }
    } else {
        console.log("Nothing in database. Continuing");
    }
}

function UnsuccessfulCall(){
    console.log("Ajax request failed.");
}

function PhpDateToHTML(dateTime) {
    var timeStamp = new Date(dateTime);
    var year = timeStamp.getFullYear();
    var month = TimeValidation(timeStamp.getMonth()+1);
    var day = TimeValidation(timeStamp.getDate());
    var hour = TimeValidation(timeStamp.getHours());
    var minutes = TimeValidation(timeStamp.getMinutes());

    return year+"-"+month+"-"+day+"T"+hour+":"+minutes;
}

function TimeValidation(generic){
    generic = generic.toString();
    if(generic.length < 2) {
        return "0"+generic;
    } else {
        return generic;
    }
}

function ReadOnly(element){
    for(var i = 0; i < element.length; i++){
        document.getElementsByName(element[i])[0].readOnly = true;
    }
}
