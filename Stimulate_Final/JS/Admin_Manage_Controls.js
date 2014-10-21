/**
 * Created by Hayden on 10/10/2014.
 */

checking = false;

$(document).ready(function(){
    LoadTableInteractions();
});

function LoadTableInteractions(){
    $(document).on('click', '.check-head', function(){
       //$('input[type="checkbox"]').prop('checked', !$(this).is(":checked"));

        CheckAll(checking);
    });

    $(document).on('change', 'input[type="checkbox"]', function(){
        if($(this).prop('checked')){
            $(this).closest('tr').css({'background-color':'rgb(235,235,235)'});
        } else {
            $(this).closest('tr').css({'background-color':'inherit'});
            $(this).closest('tr:nth-child(even)').css({'background-color':'rgb(245,245,245)'});
        }
    });
}

function CheckAll(check){

        if(!check) {
            $("input[type =\"checkbox\"]").each(function () {
                $(this).prop('checked', "checked");
                $(this).closest('tr').css({'background-color':'rgb(235,235,235)'});

            });
            checking = true;
        } else {
            $("input[type =\"checkbox\"]").each(function () {
                $(this).removeAttr("checked");
                $(this).closest('tr').css({'background-color':'inherit'});
                $(this).closest('tr:nth-child(even)').css({'background-color':'rgb(245,245,245)'});
            });
            checking = false;
        }
}

function GetCheckedElements(checkedElements){
    var checkedData = [];
    for(var i = 0; i < checkedElements.length; i++){
        if($(checkedElements[i]).parent().is('td')) {
            checkedData.push(new TableData(checkedElements[i]));
        }
    }
    return checkedData;
}

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

var TableData = function(checkBoxElement){
    var checkBoxElement = checkBoxElement;
    var siblingInfo;
    GetSiblings();

    this.FetchAllData = function() {
        return siblingInfo;
    };

    function GetSiblings(){
        var siblings = $(checkBoxElement).parent().siblings();
        var siblingData = [];

        for(var i = 0; i < siblings.length; i++){
            siblingData.push($(siblings[i]).html());
        }

        siblingInfo = siblingData;
    }
};

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


function OnChange(checkedData){
    $('.popup-select').on("change", function(){
        var currentIndex = $('.popup-select')[0].selectedIndex;
        var inputs = $('.popup-window').find(':input').not('input[type=button]').not('input[type=submit]').not('input[type=hidden]');
        inputs = inputs.slice(1);

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
                if(checkedData[currentIndex].FetchAllData()[i].split(' ')[1] == 'Hour(s)') {
                    $(inputs[i]).val(checkedData[currentIndex].FetchAllData()[i].split(' ')[0]).change();
                } else {
                    inputs[i].value = (checkedData[currentIndex].FetchAllData()[i]);
                }
            }
        }
    })
}

function RemoveAllPopups(){
    $('.background-wrapper').remove();
}
