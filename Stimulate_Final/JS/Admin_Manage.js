/*
The code on this page might be difficult to follow. That is because most of the code
is quite similar but has slight variations to the rules making it harder to refactor.
 */
function LoadUserInteractions(getVariable){
        // If an interactable button is clicked
    $(document).on('click', '.admin-controls', function(event) {
        var buttonName = $(event.target).html();

        checkedElements = $('#InformationTable input:checkbox:checked');
        checkedData = GetCheckedElements(checkedElements);

        if (buttonName != 'Add by CSV') {
            $('main').append(GenerateHTML(getVariable, buttonName));
            OnChange(checkedData);
        } else {
            window.location.href = "Admin_Manage_CSV.php?action="+getVariable;
        }
    });

}

function GenerateHTML(getVariable, action){
    var html = '<div class = "background-wrapper">' +
        '<form method="post" action="" class = "popup-window">' +
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

function manageShiftHtml(buttonHtml) {
    var days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    var currentSelectIndex = 0;

    var html = '<label>Record:</label>' +
        '<select class = "popup-select">';

    for (var i = 0; i < checkedData.length; i++) {
        html += '<option>' + checkedData[i].FetchAllData()[0] + '</option>';
    }
    html += '</select><label>Student ID</label>' +
    '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[0] + '" name = "newId" placeholder="s827xxxx" REQUIRED>' +
    '<label>Stream:</label>' +
    '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[1] + '" name = "name" placeholder="Your first and last name.." REQUIRED>' +
    '<label>Day:</label>' +
    '<select name = "day">';

    for (var i = 0; i < days.length; i++) {
        if (days[i] == checkedData[currentSelectIndex].FetchAllData()[2]) {
            html += '<option selected>' + days[i] + '</option>';
        } else {
            html += '<option>' + days[i] + '</option>';
        }
    }
    html += '</select>' +
    '<label>Time:</label>';
    var time;
    hourMinute = checkedData[currentSelectIndex].FetchAllData()[3].split(':');
    hour = hourMinute[0];
    minute = hourMinute[1].substr(0, 2);
    timeOfDate = hourMinute[1].substr(2);

    if(timeOfDate == 'pm'){
        hour += 12;
    } else {
        if(hour<10) {
            hour = '0' + hour;
        }
    }

    html += '<input type="time" name = "time" REQUIRED value="'+hour+':'+minute+':00">' +
    '<label>Duration:</label>' +
    '<select name = "duration">';
    for (var i = 1; i < 3; i++) {
        if (i+' Hour(s)' == checkedData[currentSelectIndex].FetchAllData()[4]) {
            html += '<option selected>' + i + '</option>';
        } else {
            html += '<option>' + i + '</option>';
        }
    }
    html += '</select>' +
    '<input type="hidden" value = "' + checkedData[currentSelectIndex].FetchAllData()[0] + '" name = "id">';
    return html + buttonHtml;
}
function manageVolunteerHtml(buttonHtml) {
    var currentSelectIndex = 0;

    var html = '<label>Record:</label>' +
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
    '<input type="hidden" value = "' + checkedData[currentSelectIndex].FetchAllData()[0] + '" name = "id">';
    return html + buttonHtml;
}

function manageStaffHtml(buttonHtml) {
    var currentSelectIndex = 0;
    var html = '<label>Record:</label>' +
        '<select class = "popup-select">';
    for (var i = 0; i < checkedData.length; i++) {
        html += '<option>' + checkedData[i].FetchAllData()[1] + '</option>';
    }
    html += '</select>' +
    '<label>ID Number:</label>' +
    '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[0] + '" name = "newId" placeholder="s827xxxx" REQUIRED>' +
    '<label>Name:</label>' +
    '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[1] + '" name = "name" placeholder="Your first and last name.." REQUIRED>' +
    '<input type="hidden" value = "' + checkedData[currentSelectIndex].FetchAllData()[0] + '" name = "id">';
    return html + buttonHtml;
}

function manageAbsentHtml(buttonHtml){
    var currentSelectIndex = 0;
    var html = '<label>Record:</label>' +
        '<select class = "popup-select">';
    for (var i = 0; i < checkedData.length; i++) {
        html += '<option>' + checkedData[i].FetchAllData()[0] + '</option>';
    }
    html += '</select>' +
    '<label>ID Number:</label>' +
    '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[0] + '" name = "newId" placeholder="s827xxxx" REQUIRED>' +
    '<label>Name:</label>' +
    '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[1] + '" name = "name" placeholder="Your first and last name.." REQUIRED>' +
    '<input type="hidden" value = "' + checkedData[currentSelectIndex].FetchAllData()[0] + '" name = "id">' +
    '<label>Start time:</label>';

    var time = checkedData[currentSelectIndex].FetchAllData()[2].split(' ');
    html += '<input type="datetime-local" name = "time" REQUIRED value="'+time[0]+'T'+time[1]+'">' +
    '<label>End time:</label>';
    time = checkedData[currentSelectIndex].FetchAllData()[3].split(' ');
    html += '<input type="datetime-local" name = "endTime" REQUIRED value="'+time[0]+'T'+time[1]+'">' +
    '<textarea name = "reason" placeholder="Why you cannot make it.." REQUIRED>'+checkedData[currentSelectIndex].FetchAllData()[4]+'</textarea>';
    return html + buttonHtml;
}

function returnBody(getVariable, action){
    switch(action) {
        case 'New':
            var buttonHtml = '<input type="submit" value = "Add" class = "inline">' +
                '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
                '<input type="hidden" name="type" value = "new">';
            switch(getVariable) {
                case 'manageVolunteer':
                    return '<label>Student Number:</label>' +
                    '<input type="text" name = "id" placeholder="n827xxxx" REQUIRED>' +
                    '<label>Name:</label>' +
                    '<input type="text" name = "name" placeholder="Your first and last name.." REQUIRED>' +
                    '<label>Stream:</label>' +
                    '<input type="text" name = "stream" placeholder="Streams">' + buttonHtml;
                    break;
                case 'manageStaff':
                    return '<label>Staff Number:</label>' +
                    '<input type="text" name = "id" placeholder="s827xxxx" REQUIRED>' +
                    '<label>Name:</label>' +
                    '<input type="text" name = "name" placeholder="Your first and last name.." autocomplete="off" REQUIRED>' +
                    '<label>Password:</label>' +
                    '<input style="display:none" type="text" name="fakeusernameremembered"/>' + // Workaround for chrome autofilling
                    '<input style="display:none" type="password" name="fakepasswordremembered"/>' +
                    '<input type="password" name = "password" placeholder="Password" autocomplete="off" REQUIRED>' +
                    '<input type="password" name = "confirmPassword" placeholder="Confirm Password" autocomplete="off" REQUIRED>' + buttonHtml;
                    break;
                case 'manageShift':
                    return '<label>ID Number:</label>' +
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
                    '</select>' + buttonHtml;
                    break;
                case 'manageAbsent':
                    return '<label>ID Number:</label>' +
                    '<input type="text" name = "id" placeholder="n827xxxx" REQUIRED>' +
                    '<label>Time:</label>' +
                    '<input type="datetime-local" name = "time" REQUIRED>' +
                    '<label>Reason:</label>' +
                    '<textarea name = "reason" placeholder="Why you cannot make it.." REQUIRED></textarea>' + buttonHtml;
                    break;
                default:
                    break;
            }
            break;
        case 'Manage':
            if (checkedData.length > 0) {
                var buttonHtml = '<input type="submit" value = "Confirm" class = "inline">' +
                    '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
                    '<input type="hidden" name="type" value = "manage">';
                switch (getVariable) {
                    case 'manageVolunteer':
                        return manageVolunteerHtml(buttonHtml);
                    case 'manageStaff':
                        return manageStaffHtml(buttonHtml);
                    case 'manageShift':
                        return manageShiftHtml(buttonHtml);
                        break;
                    case 'manageAbsent':
                        return manageAbsentHtml(buttonHtml);
                        break;
                default:
                    break;
                }
            } else {
                return '<p>Please check which records you wish to edit.</p><input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">';
            }
            break;
        case 'Delete':
            if(checkedData.length>0) {
                var buttonHtml = '<input type="submit" value = "Confirm" class = "inline">' +
                    '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
                    '<input type="hidden" name="type" value = "delete">';
                var html = '<p>Confirm you want to delete ' + checkedData.length + ' records?</p>';

                switch (getVariable) {
                    case 'manageVolunteer':case 'manageStaff':
                        for (var i = 0; i < checkedData.length; i++) {
                            html += '<input type="hidden" name="id[]" value = "' + checkedData[i].FetchAllData()[0] + '">';
                        }
                        break;
                    case 'manageShift':
                        for (var i = 0; i < checkedData.length; i++) {
                            html += '<input type="hidden" name="shift_id[]" value = "'+checkedData[i].FetchAllData()[0]+'">';
                        }
                        break;
                    case 'manageAbsent':
                        for (var i = 0; i < checkedData.length; i++) {
                            html += '<input type="hidden" name="absent_id[]" value = "'+checkedData[i].FetchAllData()[0]+'">';
                        }
                        break;
                    default:
                        break;
                }
                return html+buttonHtml;
            } else {
                return '<p>Please check which records you wish to delete.</p><input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">';
            }
            break;
        default:
            return '<div></div>';
            break;
        }
}

