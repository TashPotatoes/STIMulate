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

    function NewClick(){
        var html = '<div class = "background-wrapper">' +
            '<form method="post" action="" class = "popup-window" autocomplete="off">' +
            '<div class = "headElement">' +
            '<img src="IMG/calander.png" alt="Calander" class = "inline-image">' +
            '<h2 class = "inline-text">Add new Staff</h2>' +
            '</div>' +
            '<div class = "formWrapper">' +
            '<label>ID Number:</label>' +
            '<input type="text" name = "id" placeholder="s827xxxx" REQUIRED>' +
            '<label>Name:</label>' +
            '<input type="text" name = "name" placeholder="Your first and last name.." REQUIRED>' +
            '<label>Password:</label>' +
            '<input type="password" name = "password" placeholder="Password" REQUIRED>' +
            '<input type="password" name = "confirmPassword" placeholder="Confirm Password" REQUIRED>' +
            '<input type="submit" value = "Add" class = "inline">' +
            '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
            '<input type="hidden" name="type" value = "new">' +
            '</div></form></div>';
        $('main').append(html);
    }

    function ManageClick(){
        var checkedData = GetCheckedElements();
        if(checkedData.length>0) {
            var currentSelectIndex = 0;

            var html = '<div class = "background-wrapper"><form method="post" class = "popup-window">' +
                '<div class = "headElement">' +
                '<img src="IMG/calander.png" alt="Calander" class = "inline-image">' +
                '<h2 class = "inline-text">Manage Shift</h2>' +
                '</div>' +
                '<div class = "formWrapper">' +
                '<label>Record:</label>' +
                '<select class = "popup-select">';

            for (var i = 0; i < checkedData.length; i++) {
                html += '<option>' + checkedData[i].FetchAllData()[1] + '</option>';
            }

            html += '</select>' +
            '<label>ID Number:</label>' +
            '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[0] + '" name = "newId" placeholder="s827xxxx" REQUIRED>' +
            '<label>Name:</label>' +
            '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[1] + '" name = "name" placeholder="Your first and last name.." REQUIRED>' +
            '<input type="submit" value = "Update" class = "inline">' +
            '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
            '<input type="hidden" name="type" value = "manage">' +
            '<input type="hidden" value = "' + checkedData[currentSelectIndex].FetchAllData()[0] + '" name = "id">' +
            '</div></form></div>';
            $('main').append(html);
            OnChange(checkedData);
        }
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
                html += '<input type="hidden" name="id[]" value = "'+checkedData[i].FetchAllData()[0]+'">';
            }
            html +='</div></form></div>';
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

    function OnChange(checkedData){
        $('.popup-select').on("change", function(){
            var currentIndex = $('.popup-select')[0].selectedIndex;
            var inputs = $('.popup-window').find('input[type = "text"]');

            inputs[0].value = (checkedData[currentIndex].FetchAllData()[0]);
            inputs[1].value = (checkedData[currentIndex].FetchAllData()[1]);
            inputs[2].value = (checkedData[currentIndex].FetchAllData()[2]);
        })
    }

};

var TableData = function(checkBoxElement){
    var checkBoxElement = checkBoxElement;
    var idNum = "";
    var name = "";
    GetSiblings();

    this.FetchAllData = function() {
        return [idNum, name];
    };

    function GetSiblings(){
        var siblings = $(checkBoxElement).parent().siblings();
        var siblingData = [];

        for(var i = 0; i < siblings.length; i++){
            siblingData.push($(siblings[i]).html());
        }

        idNum = siblingData[0];
        name = siblingData[1];
    }

};
