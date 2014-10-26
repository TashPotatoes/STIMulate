$(document).ready(function(){
    InitialiseDocumentControls();
});

/* This function simply listens for interactions with relevant text */
function InitialiseDocumentControls(){
    $(document).on('click', '.interactive-text', function(event){
        buttonClick = new ButtonControls(event);
        buttonClick.onClick();
    });
}
/* This class sets up the controls for the user to modify */
var ButtonControls = function(event){
    var event = event;
    var name = $(event.target).html();

    this.onClick = function(){ // onclick hand actions
        switch (name){
            // if + Add end date button clicked
            case "+ Add end date":
                // remove time input add datetime input
                $('#timeWrapper').remove();
                var inputDate = $('input[name = "timestamp"]').val();
                $(event.target).parent().append("<span class = \"small\">to</span><input type = \"date\" name = \"timestampEnd\" value = \""+inputDate+"\"class = \"entryFields no-margin\">");
                $(event.target).remove();
                break;
            default:
                break;
        }
    };

};