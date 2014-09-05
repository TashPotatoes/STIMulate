/**
 * Created by crazygravy89 on 1/08/14.
 */
$(document).ready(function() {
    var suggestionArray;
    var ENTER_KEY = 13;
    var DOWN_KEY = 40;
    var UP_KEY = 38;

    $("#facilitator").on("keydown", function(e){
        var keyCode = (e.keyCode ? e.keyCode : e.which);

        if ($(".selectedSuggestion").length > 0) {
            if (keyCode == ENTER_KEY && $(".suggest").length > 0) {
                e.preventDefault();
                $("#facilitator").val($(".selectedSuggestion").text());
                $("#suggestions").empty();
            }
        }
    }).keyup(function(e){
            var keyCode = (e.keyCode ? e.keyCode : e.which);


            if($("#facilitator").val() == ""){
                $("#suggestions").empty();
            } else if (keyCode == DOWN_KEY || keyCode == UP_KEY) {
                if ($(".suggest").length != 0) {
                    scrollUpOrDown(keyCode);
                }
            } else if (keyCode != ENTER_KEY){
                if (suggestionArray == null || suggestionArray == "") {
                    $.ajax({ url: 'PHP/DatabaseAPI.php',
                        data: {
                            "action": 'facilitator'
                        },
                        type: 'post',
                        datatype: 'json',

                        success: successfulRetrieve,
                    error: function(){
                        window.log('request failed');
                    }
                });

                // If there's no array, or it's empty get a new array
                    // Otherwise if the field is empty clear the suggestions
            } else if(!this.value){
                    $("#suggestions").empty();

                    // Otherwise add some new suggestions
                } else if (keyCode != ENTER_KEY){
                    textSuggestions(suggestionArray);
                }
            }
        })
});

function successfulRetrieve(output){
    textSuggestions(JSON.parse(output));
}

function textSuggestions(suggestions){
    var MAX_SUGGESTIONS = 5;
    var matchArray = [];
    var currentInput = $("#facilitator").val();

    // Capitalising every word
    var tempCurrentInputArray = currentInput.split(" ");
    for (var i=0 ; i < tempCurrentInputArray.length ; i++){
        var word = tempCurrentInputArray[i];
        var firstLetter = word.substr(0,1);
        var remainingLetters = word.substr(1, word.length -1).toLowerCase();
        tempCurrentInputArray[i] = firstLetter.toUpperCase() + remainingLetters;
    }

    currentInput = tempCurrentInputArray.join(" ");
    // Creating regular expression
    var regularExpressionString = "^"+currentInput+"[A-z \\s*]*$";
    var regularExpression = new RegExp(regularExpressionString);

    // Matching regular expression and text, if matched assign to matchArray
    for (var i = 0; i < suggestions.length; i++) {
        if (regularExpression.test(suggestions[i]["name"])) {
            matchArray.push(suggestions[i]["name"]);
        };
    };

    // Refreshing the suggestions and adding up to the MAX_SUGGESTIONS worth of suggestions
    $("#suggestions").empty();
    for (var i = 0; i < MAX_SUGGESTIONS; i++) {
        if (i < matchArray.length) {
            $("#suggestions").append("<a class = \"suggest\"><p id = \"suggest"+i+"\">"+matchArray[i]+"</p></a>");
        };
    }

    // This needs to be set up after the suggestions have been appended.
    suggestionSelect();
}

function scrollUpOrDown(code){

    var indexValueOfSelected = null;
    var suggestions = $(".suggest");
    var FIRST_ELEMENT = 0;
    var DOWN_KEY = 40;
    var UP_KEY = 38;

    for (var i = 0; i < suggestions.length; i++) {
        if ($("#suggest"+i).hasClass("selectedSuggestion")) {
            indexValueOfSelected = i;
        };
    };

    if (code == DOWN_KEY){
        if (indexValueOfSelected == null) {
            $("#suggest"+FIRST_ELEMENT).addClass("selectedSuggestion");
        } else{
            var indexAdd = indexValueOfSelected + 1;
            if (indexAdd < suggestions.length) {

                $("#suggest"+indexAdd).addClass("selectedSuggestion");
                $("#suggest"+indexValueOfSelected).removeClass("selectedSuggestion");
            }
        }
    } else if (code == UP_KEY) {
        var indexSub = indexValueOfSelected -1;
        if (indexSub >= 0) {
            $("#suggest"+indexSub).addClass("selectedSuggestion");
            $("#suggest"+indexValueOfSelected).removeClass("selectedSuggestion");
        } else{
            $("#suggest"+indexValueOfSelected).removeClass("selectedSuggestion");
        }
    }
}

function suggestionSelect(){
    $("#suggestions").off().on("click", function (event) {
        var newValue = $("#"+event.target.id).text();

        $("#facilitator").val(newValue);
        $("#suggestions").empty();
    });
}