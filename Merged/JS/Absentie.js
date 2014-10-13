/**
 * Created by Hayden on 13/10/2014.
 */

$(document).ready(function(){
    InitialiseDocumentControls();
});

function InitialiseDocumentControls(){
    $(document).on('click', '.interactive-text', function(event){
        buttonClick = new ButtonControls(event);
        buttonClick.onClick();
    });
}

var ButtonControls = function(event){
    var event = event;
    var name = $(event.target).html();

    this.onClick = function(){
        switch (name){
            case "+ Add end date":
                $('#timeWrapper').remove();
                var inputDate = $('input[name = "timestamp"]').val();
                console.log(inputDate);
                $(event.target).parent().append("<span class = \"small\">to</span><input type = \"date\" name = \"timestampEnd\" value = \""+inputDate+"\"class = \"entryFields no-margin\">");
                $(event.target).remove();
                break;
            default:
                break;
        }
    };

};