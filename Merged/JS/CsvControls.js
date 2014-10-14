/**
 * Created by Hayden on 13/10/2014.
 */
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
            case "View CSV":
                AddCSV();
                break;
            case "Add all to Database":
                break;
            case "Edit":
                console.log('edit');
                ManageClick();
                break;
            case "Delete":
                DeleteClick();
                break;
            default:
                break;
        }
    };

    function AddCSV(){
        var html = '<div class = "background-wrapper">' +
            '<form method="post" action="" class = "popup-window" enctype="multipart/form-data">' +
            '<div class = "headElement">' +
            '<img src="IMG/calander.png" alt="Calander" class = "inline-image">' +
            '<h2 class = "inline-text">Add new Absentie</h2>' +
            '</div>' +
            '<div class = "formWrapper">' +
            '<label>File:</label>' +
            '<input type="file" name = "file" REQUIRED>' +
            '<input type="submit" value = "View File" class = "inline">' +
            '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
            '<input type="hidden" name="type" value = "AddCSV">' +
            '</div></form></div>';
        $('main').append(html);
    }

    function ManageClick(){
        var checkedData = GetCheckedElements();
        if(checkedData.length>0) {
            var currentSelectIndex = 0;

            var html = '<div class = "background-wrapper">' +
                '<form method="post" class = "popup-window">' +
                '<div class = "headElement">' +
                '<img src="IMG/calander.png" alt="Calander" class = "inline-image">' +
                '<h2 class = "inline-text">Manage Students</h2>' +
                '</div>' +
                '<div class = "formWrapper">' +
                '<label>Record:</label>' +
                '<select class = "popup-select">';

            for (var i = 0; i < checkedData.length; i++) {
                html += '<option>' + checkedData[i].FetchAllData()[1] + '</option>';
            }
            html += '</select>' +
            '<label>ID Number:</label>' +
            '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[0] + '" name = "newId" placeholder="n827xxxx">' +
            '<label>Name:</label>' +
            '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[1] + '" name = "name" placeholder="Your first and last name..">' +
            '<label>Stream:</label>' +
            '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[2] + '" name = "stream" placeholder="Streams">' +
            '<input type="submit" value = "Update" class = "inline">' +
            '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
            '<input type="hidden" name="type" value = "manage">' +
            '<input type="hidden" value = "' + checkedData[currentSelectIndex].FetchAllData()[0] + '" name = "id">' +
            '</div>' +
            '</form>' +
            '</div>';

            $('main').append(html);
            OnChange(checkedData);
        }
    }

    function DeleteClick(){
        var checkedData = GetCheckedElements();

        if(checkedData.length>0) {
            var html = '<div class = "background-wrapper">' +
                '<form method="post" action="" class = "popup-window">' +
                '<div class = "headElement">' +
                '<img src="IMG/calander.png" alt="Calander" class = "inline-image">' +
                '<h2 class = "inline-text">Delete Students</h2>' +
                '</div>' +
                '<div class = "formWrapper">' +
                '<p>Confirm you want to delete ' + checkedData.length + ' records?</p>' +
                '<input type="submit" value = "Confirm" class = "inline">' +
                '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
                '<input type="hidden" name="type" value = "Delete">';

            for(var i = 0; i < checkedData.length; i++){
                html += '<input type="hidden" name="id[]" value = "'+checkedData[i].FetchAllData()[0]+'">';
            }
            html +='</div></form>' +
            '</div>' +
            '';
            $('main').append(html);
            OnChange(checkedData);
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
