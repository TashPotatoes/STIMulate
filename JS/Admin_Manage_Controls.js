
/**
 * Created by Hayden on 10/10/2014.
 */

checking = false;
onChangeIndex = 0;
priorIndex = 0;

// Load default interactions
$(document).ready(function(){
    checking = false;
    LoadTableInteractions();
});


function LoadTableInteractions(){
    // When the table head is clicked that isn't a checkbox, sort the column
    $(document).on('click', '.tableHead', function(event) {
        console.log($(event.target));
        if (!$(event.target).hasClass('.check-head')) {
            $('#InformationTable').tablesorter({
                headers: {
                    // disable sorting of the first column (we can use zero or the header class name)
                    0: {
                        // disable it by setting the property sorter to false
                        sorter: false
                    }
                }
            });
        }
    });

    // When the top checkbox is checked, check all
    $(document).on('click', '.check-head', function(){
        CheckAll(checking);
    });

    // When a checkbox is checked, check entire row
    $(document).on('change', 'input[type="checkbox"]', function(){
        if($(this).prop('checked')){
            $(this).closest('tr').css({'background-color':'rgb(235,235,235)'});
        } else {
            $(this).closest('tr').css({'background-color':'inherit'});
            $(this).closest('tr:nth-child(even)').css({'background-color':'rgb(245,245,245)'});
        }
    });
}

/*
    Checks all checkboxs and rows
    PRE: check - bool whether all boxs are check at the moment or not
    POST: checkboxes checked, and rows coloured
 */
function CheckAll(check){

        if(!check) { // if not checked check
            $("input[type =\"checkbox\"]").each(function () {
                $(this).prop('checked', "checked");
                $(this).closest('tr').css({'background-color':'rgb(235,235,235)'});

            });
            checking = true;
        } else { // If checked uncheck
            $("input[type =\"checkbox\"]").each(function () {
                $(this).removeAttr("checked");
                $(this).closest('tr').css({'background-color':'inherit'});
                $(this).closest('tr:nth-child(even)').css({'background-color':'rgb(245,245,245)'});
            });
            checking = false;
        }
}

// Gets the row data of all checked elements
function GetCheckedElements(checkedElements){
    var checkedData = [];
    // for each of the checked elements, initiate them into a table data object
    for(var i = 0; i < checkedElements.length; i++){
        if($(checkedElements[i]).parent().is('td')) {
            checkedData.push(new TableData(checkedElements[i]));
        }
    }
    return checkedData;
}

// Gets a rows child data. Tr -> td data
function GetRowChild(row){
    var rowData = [];
    for(var i = 0; i < row.length; i++){
        var rowChild = $(row[i]).children();
        if(rowChild.is('td')) {
            rowData.push(rowChild);
        }
    }

    return rowData;
}

// Object that gets and stores tr row data
var TableData = function(checkBoxElement){
    var checkBoxElement = checkBoxElement;
    var siblingInfo;
    GetSiblings();

    // Returns fetched data
    this.FetchAllData = function() {
        return siblingInfo;
    };

    // Gets ros td data
    function GetSiblings(){
        var siblings = $(checkBoxElement).parent().siblings();
        var siblingData = [];

        for(var i = 0; i < siblings.length; i++){
            siblingData.push($(siblings[i]).html());
        }

        siblingInfo = siblingData;
    }
};

// isset with similar functionality as php's isset.
function isset ()
{
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: FremyCompany
    // +   improved by: Onno Marsman
    // +   improved by: Rafał Kukawski
    // *     example 1: isset( undefined, true);
    // *     returns 1: false
    // *     example 2: isset( 'Kevin van Zonneveld' );
    // *     returns 2: true

    var a = arguments,
        l = a.length,
        i = 0,
        undef;

    if (l === 0)
    {
        throw new Error('Empty isset');
    }

    while (i !== l)
    {
        if (a[i] === undef || a[i] === null)
        {
            return false;
        }
        i++;
    }
    return true;
}

// When a manage popup select changes, change all data to relevant data.
// THIS WAS REMOVED FROM PRODUCTION VERSION AS OF 24/10/2014. FUNCTIONALITY DOES
// WORK HOWEVER.
function OnChange(checkedData){
    // On change
    $('.popup-select').on("change", function(){
        var currentIndex = $('.popup-select')[0].selectedIndex;
        var inputs = $('.popup-window').find(':input').not('input[type=button]').not('input[type=submit]').not('input[type=hidden]');
        inputs = inputs.slice(1);

        // Foreach of the inputs if the type is a time put into proper formate else
        // if set checked data table object
        for(var i = 0; i < inputs.length; i++) {
            if ($(inputs[i]).is('input[type=time')) {
                var time;
                var hourMinute = checkedData[currentIndex].FetchAllData()[3].split(':');
                var hour = hourMinute[0];
                var minute = hourMinute[1].substr(0, 2);

                if (hour < 10) {
                    if (hour < 8) {
                        hour = parseInt(hour) + 12;
                    } else {
                        hour = '0' + hour;
                    }
                }
                inputs[i].value = hour + ':' + minute + ':00';

            } else if(isset(checkedData[currentIndex].FetchAllData()[i])){
                // Split hours off and insert into input field if Hours(s) exists
                if(checkedData[currentIndex].FetchAllData()[i].split(' ')[1] == 'Hour(s)') {
                    $(inputs[i]).val(checkedData[currentIndex].FetchAllData()[i].split(' ')[0]).change();
                } else { // else input just equals fetched data
                    inputs[i].value = (checkedData[currentIndex].FetchAllData()[i]);
                }
            }
        }

        //$(inputs[0]).parent().append(appendHtml);
    });
}

// Simply removes all popups
function RemoveAllPopups(){
    $('.background-wrapper').remove();
}
