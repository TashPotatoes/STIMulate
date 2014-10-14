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