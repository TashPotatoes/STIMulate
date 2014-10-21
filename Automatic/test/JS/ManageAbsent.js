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

    $(document).on('click', '.check-head', function(){
        $('input:checkbox').prop('checked', !$(this).is(":checked"));
    });
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

    function RemoveAllPopups(){
        $('.popup-window').remove();
    }

    function NewClick(){


        var html = '<form method="post" action="" class = "popup-window">' +
            '<h1>Add new</h1>' +
            '<label>ID Number:</label>' +
            '<input type="text" name = "id">' +
            '<label>Time:</label>' +
            '<input type="datetime-local" name = "time">' +
            '<label>Reason:</label>' +
            '<textarea name = "reason"></textarea>' +
            '<input type="submit" value = "Add" class = "inline">' +
            '<input type="button" value = "Cancel" class = "inline" onclick="$(\'.popup-window\').remove();">' +
            '<input type="hidden" name="type" value = "new">' +
            '</form>';
        $('main').append(html);
    }

    function DeleteClick(){
        var checkedData = GetCheckedElements();

        if(checkedData.length>0) {
            var html = '<form method="post" action="" class = "popup-window">' +
                '<h1>Delete Record</h1>' +
                '<p>Confirm you want to delete ' + checkedData.length + ' records?</p>' +
                '<input type="submit" value = "Confirm" class = "inline">' +
                '<input type="button" value = "Cancel" class = "inline" onclick="$(\'.popup-window\').remove();">' +
                '<input type="hidden" name="type" value = "Delete">';

            for(var i = 0; i < checkedData.length; i++){
                html += '<input type="hidden" name="absent_id[]" value = "'+checkedData[i].FetchAllData()[0]+'">';
            }
            html +='</form>';
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
    var studentName = "";
    var timeAbsent = "";
    var Reason = "";
    var absentID = "";
    GetSiblings();

    this.FetchAllData = function() {
        return [absentID, idNum, studentName, timeAbsent, Reason];
    };

    function GetSiblings(){
        var siblings = $(checkBoxElement).parent().siblings();
        var siblingData = [];

        for(var i = 0; i < siblings.length; i++){
            siblingData.push($(siblings[i]).html());
        }
        idNum = siblingData[0];
        studentName = siblingData[1];
        timeAbsent = siblingData[2];
        Reason = siblingData[3];
        absentID = $(siblingData[4]).val();
    }

};
