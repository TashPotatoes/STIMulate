/**
 * Created by Hayden on 9/10/2014.
 */

$(document).ready(function(){
    LoadUserInteractions();
});

function LoadUserInteractions(){
    $(document).on('click', 'li', function(event){
        buttonClick = new ButtonControls(event);
        buttonClick.onClick();
    });
}

function RemoveAllPopups(){
    $('.background-wrapper').remove();
}

var ButtonControls = function(event){
    var event = event;
    var name = $(event.target).html();
    var checkedElements = $('#InformationTable input:checkbox:checked');

    this.onClick = function(){
        RemoveAllPopups();
        switch (name){
            case "New":
                NewClick();
                break;
            case "Delete":
                DeleteClick();
                break;
            default:
                break;
        }
    };

    function NewClick(){
        var html = '<div class = "background-wrapper"><form method="post" action="" class = "popup-window">' +
            '<div class = "headElement">' +
            '<img src="IMG/calander.png" alt="Calander" class = "inline-image">' +
            '<h2 class = "inline-text">Add new Shift</h2>' +
            '</div>' +
            '<div class = "formWrapper">' +
            '<label>ID Number:</label>' +
            '<input type="text" name = "id" placeholder="n827xxxx" REQUIRED>' +
            '<label>Stream:</label>' +
            '<select name = "stream" REQUIRED>' +
            '<option>IT</option>' +
            '<option>Science</option>' +
            '<option>Math</option>' +
            '<option>Duty Host</option>' +
            '</select>' +
            '<label>Day:</label>' +
            '<select name = "day">' +
            '<option>Monday</option>' +
            '<option>Tuesday</option>' +
            '<option>Wednesday</option>' +
            '<option>Thursday</option>' +
            '<option>Friday</option>' +
            '</select>' +
            '<label>Time:</label>' +
            '<input type="time" name = "time" REQUIRED>' +
            '<label>Duration:</label>' +
            '<select name = "duration" REQUIRED>' +
            '<option>1</option>' +
            '<option>2</option>' +
            '<option>3</option>' +
            '</select>' +
            '<input type="submit" value = "Add" class = "inline">' +
            '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
            '<input type="hidden" name="type" value = "new">' +
            '</div></form></div>';

        $('main').append(html);
    }

    function DeleteClick(){
        var checkedData = GetCheckedElements();

        if(checkedData.length>0) {
            var html = '<div class = "background-wrapper"><form method="post" action="" class = "popup-window">' +
                '<div class = "headElement">' +
                '<img src="IMG/calander.png" alt="Calander" class = "inline-image">' +
                '<h2 class = "inline-text">Delete Staff</h2>' +
                '</div>' +
                '<div class = "formWrapper">' +
                '<p>Confirm you want to delete ' + checkedData.length + ' records?</p>' +
                '<input type="submit" value = "Confirm" class = "inline">' +
                '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
                '<input type="hidden" name="type" value = "Delete">';

            for(var i = 0; i < checkedData.length; i++){
                html += '<input type="hidden" name="shift_id[]" value = "'+checkedData[i].FetchAllData()[0]+'">';
            }
            html +='</div></form></div>';
            $('main').append(html);
        }
    }

    function GetCheckedElements(){
        var checkedData = [];
        for(var i = 0; i < checkedElements.length; i++){
            checkedData.push(new TableData(checkedElements[i]));
        }
        return checkedData;
    }
};

var TableData = function(checkBoxElement){
    var checkBoxElement = checkBoxElement;
    var idNum = "";
    var stream = "";
    var day = "";
    var time = "";
    var duration = "";
    var shift_id = "";
    GetSiblings();

    this.FetchAllData = function() {
        return [shift_id, idNum, stream, day, time, duration];
    };

    function GetSiblings(){
        var siblings = $(checkBoxElement).parent().siblings();
        var siblingData = [];

        for(var i = 0; i < siblings.length; i++){
            siblingData.push($(siblings[i]).html());
        }

        idNum = siblingData[0];
        stream = siblingData[1];
        day = siblingData[2];
        time = siblingData[3];
        duration = siblingData[4];
        shift_id = $(siblingData[5]).val();
    }

};
