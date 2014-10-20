$(function() {
    initialiseControls();
});

function initialiseControls(){
    $(document).on('click', '.admin-controls', function(event){
        switch($(event.target).html()) {
            case 'Edit':
                GetChecked();
                break;
            default:
                break;
        }
    });
}

function GetChecked(){
    var checked = $('#InformationTable input:checkbox:checked');

    for(var i = 0; i < checked.length; i++){
        console.log($(checked[i]).prop("checked", false4));
    }
}