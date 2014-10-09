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


var ButtonControls = function(event){
    var event = event;
    var name = $(event.target).html();

    this.onClick = function(){
        RemoveAllPopups();
        switch (name){
            case "New":
                NewClick();
                break;
            case "Manage":
                ManageClick();
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
        var html = '<div class = "popup-window">' +
            '<h1>Add new Student</h1>' +
            '<label>Student Number:</label>' +
            '<input type="text">' +
            '<label>Name:</label>' +
            '<input type="text">' +
            '<label>Stream:</label>' +
            '<input type="text">' +
            '<input type="submit" value = "Add" class = "inline">' +
            '<input type="button" value = "Cancel" class = "inline" onclick="$(\'.popup-window\').remove();">' +
            '</div>';
        $('main').append(html);
    }

    function ManageClick(){
        var html = '<div class = "popup-window">' +
            '<h1>Manage Students</h1>' +
            '<label>Student:</label>' +
            '<select>';


            html += '</select>' +
            '<label>Student Number:</label>' +
            '<input type="text">' +
            '<label>Name:</label>' +
            '<input type="text">' +
            '<label>Stream:</label>' +
            '<input type="text">' +
            '<input type="submit" value = "Add" class = "inline">' +
            '<input type="button" value = "Cancel" class = "inline" onclick="$(\'.popup-window\').remove();">' +
            '</div>';
        $('main').append(html);
    }

    function DeleteClick(){
        console.log("test3");
    }


};