
function LoadUserInteractions(getVariable){
    // If an interactable button is clicked
    $(document).on('click', '.admin-controls', function(event) {
        var buttonName = $(event.target).html();

        checkedElements = $('#InformationTable input:checkbox:checked');
        checkedData = GetCheckedElements(checkedElements);

        $('main').append(GenerateHTML(getVariable, buttonName));
        OnChange(checkedData);
    });

}

function GenerateHTML(getVariable, action){
    var html = '<div class = "background-wrapper">' +
        '<form method="post" action="" class = "popup-window" enctype="multipart/form-data">' +
        addHeader(action, 'calander.png') +
        '<div class = "formWrapper">' +
        returnBody(getVariable, action) +
        '<input type = "hidden" value = "'+getVariable+'" type = "hidden">' +
        '</div>' +
        '</form>' +
        '</div>';
    return html;
}

function addHeader(name, image){
    return '<div class = "headElement">' +
    '<img src="IMG/'+image+'" alt="Calander" class = "inline-image">' +
    '<h2 class = "inline-text">'+name+'</h2>' +
    '</div>';
}

function returnBody(getVariable, action) {
    switch (action) {
        case 'Add CSV':
            return '<label>File:</label>' +
                '<input type="file" name = "file" REQUIRED>' +
                '<input type="submit" value = "View File" class = "inline">'+
                '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
                '<input type="hidden" name="type" value = "new">';

            break;
        default:
            break;
    }
}