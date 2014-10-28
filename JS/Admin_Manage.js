/*
The code on this page might be difficult to follow. That is because most of the code
is quite similar but has slight variations to the rules making it harder to refactor.
 */
function LoadUserInteractions(getVariable){
    // If an interactable button is clicked
    $(document).on('click', '.admin-controls', function(event) {
        var buttonName = $(event.target).html();

        // On click fetch these globally available variables
        checkedElements = $('#InformationTable input:checkbox:checked');
        checkedData = GetCheckedElements(checkedElements);

        // CSV request goes to CSV page, else append relevant action form
        if (buttonName != 'Add by CSV' && getVariable != 'manageSpecs') {
            $('main').append(GenerateHTML(getVariable, buttonName));
            OnChange(checkedData); // Largely legacy. Made to switch between rows, does not affect functionality.
        } else if(getVariable == 'manageSpecs'){
            // Anthony's unfinished spec
        } else {
            window.location.href = "Admin_Manage_CSV.php?action="+getVariable;
        }

    });

}

/*
    Generates relevant html based on the page get variable and action requested.
    PRE: getVariable - string Get variable sent to javascript from php
    PRE: action - button name that was clicked
    POST: html - string pure string html
 */
function GenerateHTML(getVariable, action){
    // Generic html features are static. Header and body dynamically generated.
    var html = '<div class = "background-wrapper">' +
        '<form method="post" action="" class = "popup-window">' +
        addHeader(action, 'calander.png') + // Adds header element
        '<div class = "formWrapper">' +
        returnBody(getVariable, action) + // Adds body
        '<input type = "hidden" value = "'+getVariable+'" type = "hidden">' +
        '</div>' +
        '</form>' +
        '</div>';
    return html;
}

/*
    Returns header html with the relevant variables concatenated.
    PRE: name - string name to be displayed on the header
    PRE: image - image name in the IMG/ folder to appear next to the name
    POST: Returns string - pure html string
 */
function addHeader(name, image){
    return '<div class = "headElement">' +
    '<img src="IMG/'+image+'" alt="Calander" class = "inline-image">' +
    '<h2 class = "inline-text">'+name+'</h2>' +
    '</div>';
}

/*
    Creates html for the popup body of the shift nature
    PRE: buttonHTML - String HTML that reprents the popup button controls
    PRE: checkedData - Array array of object type table representing the table row checked
    POST: Return string html of popup body
 */
function manageShiftHtml(buttonHtml) {
    // Array for day index
    var days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    var currentSelectIndex = 0;

    // Magic number constants for checkedData.
    var STUDENT_ID_INDEX = 0;
    var DAY_INDEX = 2;
    var TIME_INDEX = 3;
    var DURATION_INDEX = 4;
    var SHIFT_ID_INDEX = 5;


    // Generate, record select, ID input, stream checkbox, day, time
    // TODO stream input is text not checkbox in this page
    var html = '<label>Record:</label>' +
        '<select class = "popup-select">';

    // For each student checked add option to select their data
    for (var i = 0; i < checkedData.length; i++) {
        // input each of the student ids
        html += '<option>' + checkedData[i].FetchAllData()[STUDENT_ID_INDEX] + '</option>';
    }

    // Input student id, stream, and day data
    html += '</select><label>Student ID</label>' +
    '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[STUDENT_ID_INDEX] + '" name = "newId" placeholder="s827xxxx" REQUIRED>' +
    '<label>Stream:</label>' +
    '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[1] + '" name = "stream" placeholder="Stream of shift" REQUIRED>' +
    '<label>Day:</label>' +
    '<select name = "day">';

    // Generate day data as option. If the checked day select it as default option
    for (var i = 0; i < days.length; i++) {
        if (days[i] == checkedData[currentSelectIndex].FetchAllData()[DAY_INDEX]) {
            html += '<option selected>' + days[i] + '</option>';
        } else {
            html += '<option>' + days[i] + '</option>';
        }
    }
    html += '</select>' +
    '<label>Time:</label>';

    // Create and format table 09:00am to 09:00:00 or 1:00pm to 13:00:00
    var HOUR_INDEX = 0;
    var MINUTE_PERIOD_INDEX = 1;
    var TIME_PERIOD_INDEX = 1;

    // Split time data down into it's components
    var time;
    var hourMinute = checkedData[currentSelectIndex].FetchAllData()[TIME_INDEX].split(':');
    var hour = hourMinute[HOUR_INDEX];
    var minute = hourMinute[MINUTE_PERIOD_INDEX].substr(0, 2); // 09:00om -> 09:00
    var timePeriod = hourMinute[TIME_PERIOD_INDEX].substr(2); // 09:00pm -> pm

    // If the period is in the pm then add 12 hours
    if(timePeriod == 'pm'){
        hour = parseInt(hour) + 12; // 05:00pm -> 17:00
    }

    // Input time data into input
    html += '<input type="time" name = "time" REQUIRED value="'+hour+':'+minute+':00">' +
    '<label>Duration:</label>' +
    '<select name = "duration">';
    // Create an option for each of the duration options. Select number hours in shift
    for (var i = 1; i <= 3; i++) {
        if (i+' Hour(s)' == checkedData[currentSelectIndex].FetchAllData()[DURATION_INDEX]) {
            html += '<option selected>' + i + '</option>';
        } else {
            html += '<option>' + i + '</option>';
        }
    }

    // Add hidden data for student id and shift id to post to database
    html += '</select>' +
    '<input type="hidden" value = "' + checkedData[currentSelectIndex].FetchAllData()[STUDENT_ID_INDEX] + '" name = "id">' +
    '<input type="hidden" value = "' + $(checkedData[currentSelectIndex].FetchAllData()[SHIFT_ID_INDEX]).val() + '" name = "shiftID">';
    return html + buttonHtml;
}

/*
 Creates html for the popup body of the volunteer nature
 PRE: buttonHTML - String HTML that represents the popup button controls
 PRE: checkedData - Array array of object type table representing the table row checked
 POST: Return string html of popup body
 */
function manageVolunteerHtml(buttonHtml) {
    var STUDENT_ID_INDEX = 0;
    var STUDENT_NAME_INDEX = 1;
    var STREAM_DIV_INDEX = 2;

    var currentSelectIndex = 0;

    var html = '<label>Record:</label>' +
        '<select class = "popup-select">';

    // Display student ids of all checked boxs
    for (var i = 0; i < checkedData.length; i++) {
        html += '<option>' + checkedData[i].FetchAllData()[STUDENT_NAME_INDEX] + '</option>';
    }

    // Add ID, name, stream html
    html += '</select>' +
    '<label>ID Number:</label>' +
    '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[STUDENT_ID_INDEX] + '" name = "newId" placeholder="n827xxxx">' +
    '<label>Name:</label>' +
    '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[STUDENT_NAME_INDEX] + '" name = "name" placeholder="Your first and last name..">' +
    '<label>Stream:</label>' +
    '<input type="hidden" value = "' + checkedData[currentSelectIndex].FetchAllData()[STUDENT_ID_INDEX] + '" name = "id">' +
    '<table>';

    // Determine which streams the student is apart of
    for (var i = 0; i < checkedData.length; i++) {
        var streamArray = []; // For each of the checked stream information for the select student
        for (var j = 0; j < $(checkedData[i].FetchAllData()[STREAM_DIV_INDEX]).length; j++) {
            // If the element within the stream div is an input get the data
            if ($($(checkedData[i].FetchAllData()[STREAM_DIV_INDEX])[j]).is('input')) {
                streamArray.push($($(checkedData[i].FetchAllData()[STREAM_DIV_INDEX])[j]).attr('name'));
            }
        }
    }

    // Check the relevant stream checkbox
    html += '<tr><td>IT</td><td><input type="checkbox" name = "it" placeholder="Streams" '+checked(streamArray, 'IT')+'></td></tr>' +
    '<tr><td>Science</td><td><input type="checkbox" name = "science" placeholder="Streams" '+checked(streamArray, 'Science')+'></td></tr>' +
    '<tr><td>Math</td><td><input type="checkbox" name = "math" placeholder="Streams" '+checked(streamArray, 'Math')+'></td></tr>' +
    '<tr><td>Duty Host</td><td><input type="checkbox" name = "dutyHost" placeholder="Streams" '+checked(streamArray, 'Duty Host')+'></td></tr>' +
    '</table>';
    return html + buttonHtml;
}

/*
    Takes an array and a string to determine if the string
    is inside the array and returns 'Checked' or ''
    PRE: streamArray - Array a generic array of strings
    PRE: checkAgainst - string a generic string to compare to
    POST: return string 'Checked' if in array else ''
 */
function checked(streamArray, checkAgainst){
    // If checked return 'Checked'
    for(var i = 0; i < streamArray.length; i++){
        if(streamArray[i] == checkAgainst){
            return 'Checked';
        }
    }
    return '';
}

/*
    Generates staff based html manage body element
    PRE: buttonHTML - String HTML that represents the popup button controls
    PRE: checkedData - Array array of object type table representing the table row checked
    POST: Return string html of popup body
 */
function manageStaffHtml(buttonHtml) {
    var currentSelectIndex = 0;

    var STAFF_ID_INDEX = 0;
    var STAFF_NAME_INDEX =1;

    // staff id to select, staff id, staff name
    var html = '<label>Record:</label>' +
        '<select class = "popup-select">';

    // Generate a staff id option for each selected tr
    for (var i = 0; i < checkedData.length; i++) {
        html += '<option>' + checkedData[i].FetchAllData()[1] + '</option>';
    }

    html += '</select>' +
    '<label>ID Number:</label>' +
    '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[STAFF_ID_INDEX] + '" name = "newId" placeholder="s827xxxx" REQUIRED>' +
    '<label>Name:</label>' +
    '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[STAFF_NAME_INDEX] + '" name = "name" placeholder="Your first and last name.." REQUIRED>' +
    '<input type="hidden" value = "' + checkedData[currentSelectIndex].FetchAllData()[STAFF_ID_INDEX] + '" name = "id">';
    return html + buttonHtml;
}

/*
 Generates absent based html manage body element
 PRE: buttonHTML - String HTML that represents the popup button controls
 PRE: checkedData - Array array of object type table representing the table row checked
 POST: Return string html of popup body
 */
function manageAbsentHtml(buttonHtml){
    var currentSelectIndex = 0;

    var STUDENT_ID_INDEX = 0;
    var STUDENT_NAME_INDEX = 1;
    var START_TIME_INDEX = 2;
    var END_TIME_INDEX = 3;
    var REASON_INDEX = 4;
    var ABSENT_ID_INDEX = 5;


    // Generate an option for each ID of the students absent and checked
    var html = '<label>Record:</label>' +
        '<select class = "popup-select">';
    for (var i = 0; i < checkedData.length; i++) {
        html += '<option>' + checkedData[i].FetchAllData()[STUDENT_ID_INDEX] + '</option>';
    }

    // Input student ID, name
    html += '</select>' +
    '<label>ID Number:</label>' +
    '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[STUDENT_ID_INDEX] + '" name = "newId" placeholder="s827xxxx" REQUIRED>' +
    '<label>Name:</label>' +
    '<input type="text" value = "' + checkedData[currentSelectIndex].FetchAllData()[STUDENT_NAME_INDEX] + '" name = "name" placeholder="Your first and last name.." READONLY>' +
    '<input type="hidden" value = "' + $(checkedData[currentSelectIndex].FetchAllData()[ABSENT_ID_INDEX]).val() + '" name = "absentId">' +
    '<label>Start time:</label>';

    // Calculate start time
    var time = checkedData[currentSelectIndex].FetchAllData()[START_TIME_INDEX].split(' ');
    html += '<input type="datetime-local" name = "time" REQUIRED value="'+time[0]+'T'+time[1]+'">' +
    '<label>End time:</label>';

    // Calculate end time
    time = checkedData[currentSelectIndex].FetchAllData()[END_TIME_INDEX].split(' ');
    html += '<input type="datetime-local" name = "endTime" value="'+time[END_TIME_INDEX]+'T'+time[1]+'">' +
    '<label>Reason:</label>' +
    '<textarea name = "reason" placeholder="Why you cannot make it.." REQUIRED>'+checkedData[currentSelectIndex].FetchAllData()[REASON_INDEX]+'</textarea>';
    return html + buttonHtml;
}

/*
    Depending on the page requested, generate appropriate html
    and returns it.
    PRE: getVariable - string variable used to denote the action of the page. For example
                        manageVolunteer gets volunteer information.
    PRE: action - string the variable used to denote the button control action. ie. new, manage
                        delete, etc.
    POST: html - string the generated string of html relevant for a popup.
 */
function returnBody(getVariable, action){
    switch(action) {
        case 'New':
            // Generic new action button html
            var buttonHtml = '<input type="submit" value = "Add" class = "inline">' +
                '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
                '<input type="hidden" name="type" value = "new">';

            // return relevant new button action html
            switch(getVariable) {
                case 'manageVolunteer':
                    // Student ID, Name, Stream
                    return '<label>Student Number:</label>' +
                    '<input type="text" name = "id" placeholder="n827xxxx" REQUIRED>' +
                    '<label>Name:</label>' +
                    '<input type="text" name = "name" placeholder="Your first and last name.." REQUIRED>' +
                    '<label>Stream:</label>' +
                    '<table>' +
                    '<tr><td>IT</td><td><input type="checkbox" name = "it" placeholder="Streams"></td></tr>' +
                    '<tr><td>Science</td><td><input type="checkbox" name = "science" placeholder="Streams"></td></tr>' +
                    '<tr><td>Math</td><td><input type="checkbox" name = "math" placeholder="Streams"></td></tr>' +
                    '<tr><td>Duty Host</td><td><input type="checkbox" name = "dutyHost" placeholder="Streams"></td></tr>' +
                    '</table>' +
                    buttonHtml;
                    break;
                case 'manageStaff':
                    // Staff ID, Name, Password
                    // TODO Password validation
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
                    // Person ID, Stream, Day, Time, Duration
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
                    // Person ID, start time, end time, reason
                    return '<label>ID Number:</label>' +
                    '<input type="text" name = "id" placeholder="n827xxxx" REQUIRED>' +
                    '<label>Start:</label>' +
                    '<input type="datetime-local" name = "time" REQUIRED>' +
                    '<label>End Time:</label>' +
                    '<input type="datetime-local" name = "endTime">' +
                    '<label>Reason:</label>' +
                    '<textarea name = "reason" placeholder="Why you cannot make it.." REQUIRED></textarea>' + buttonHtml;
                    break;
                default:
                    break;
            }
            break;
        case 'Manage':
            // Need to select more then nothing thing, but only handles one record atm
            // TODO implement multiline editing
            if (checkedData.length > 0 && checkedData.length < 2) {

                // Generic manage button html
                var buttonHtml = '<input type="submit" value = "Confirm" class = "inline">' +
                    '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
                    '<input type="hidden" name="type" value = "manage">';

                // Handle manage popup generation based on page
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
            } else if(checkedData.length > 1) { // If more then one record selected
                return '<p>More then one record Selected. Please select only one to continue.</p><input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">';
            } else { // If less then one record selected
                return '<p>Please check which records you wish to edit.</p><input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">';
            }
            break;
        case 'Delete':
            var VOLUNTEER_ID_INDEX = 0;
            var SHIFT_ID_INDEX = 5;
            var ABSENT_ID_INDEX = 5;

            // Make sure more then one record selected.
            if(checkedData.length>0) {
                var buttonHtml = '<input type="submit" value = "Confirm" class = "inline">' +
                    '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
                    '<input type="hidden" name="type" value = "delete">';
                var html = '<p>Confirm you want to delete ' + checkedData.length + ' records?</p>';

                // Generate hidden box data for records to be deleted as required. Consider AJAX improvements
                switch (getVariable) {
                    case 'manageVolunteer':case 'manageStaff':
                        // foreach checked element append ID
                        for (var i = 0; i < checkedData.length; i++) {
                            html += '<input type="hidden" name="id[]" value = "' + checkedData[i].FetchAllData()[VOLUNTEER_ID_INDEX] + '">';
                        }
                        break;
                    case 'manageShift':
                        // foreach checked element append ID
                        for (var i = 0; i < checkedData.length; i++) {
                            html += '<input type="hidden" name="shift_id[]" value = "'+$(checkedData[i].FetchAllData()[SHIFT_ID_INDEX]).val()+'">';
                        }
                        break;
                    case 'manageAbsent':
                        // foreach checked element append ID
                        for (var i = 0; i < checkedData.length; i++) {
                            html += '<input type="hidden" name="absent_id[]" value = "'+$(checkedData[i].FetchAllData()[ABSENT_ID_INDEX]).val()+'">';
                        }
                        break;
                    default:
                        break;
                }
                // Return generated html plus button html
                return html+buttonHtml;
            } else { // Less then one record
                return '<p>Please check which records you wish to delete.</p><input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">';
            }
            break;
        case 'Reset Password':
            // Ensure at least one record selected
            if(checkedData.length>0) {

                var buttonHtml = '<input type="submit" value = "Confirm" class = "inline">' +
                    '<input type="button" value = "Cancel" class = "inline" onclick="RemoveAllPopups();">' +
                    '<input type="hidden" name="type" value = "resetPassword">';
                var html = '<p>Reset ' + checkedData.length + ' password(s)?</p>' +
                    '<label>Provide to staff to login and reset:</label>' +
                    '<input type = "text" value="password' + Math.floor(Math.random() * 100) + 1 + '" name="password">'; // Random int between 100 and 1000

                // foreach checked record append staff id
                for (var i = 0; i < checkedData.length; i++) {
                    html += '<input type="hidden" name="id[]" value = "' + checkedData[i].FetchAllData()[0] + '">';
                }

                // return popup
                return html + buttonHtml;
            }
            break;
        default:
            return '<div></div>';
            break;
    }
}

