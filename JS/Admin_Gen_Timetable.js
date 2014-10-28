/* 
 * Pass javascript variables to PHP (via sending as POST & reloading page) 
 */
function javascriptToPHP( jsvar, studentArray, pageURL) { 
	jsvar = JSON.stringify(jsvar);
	studentArray = JSON.stringify(studentArray);
	console.log("here");
	$.ajax({
		url: pageURL,
		type: "POST",
		data: {
			'variable': jsvar, 'studentArray': studentArray
		},
		//dataType: 'json',
		success: function (output) {
			console.log("This is a ajax success" + output);
		},
		error : function(jqXHR, textStatus, errorThrown) { 
			console.log("error "); 
			alert("Error: Status: "+textStatus+" Message: "+errorThrown);
		}
	});
}


/* 
 * Function for to run simplex algorithm upon pressing the "generate table"  
 */
function run(stream){
	start = new Date(); 
	logNode.innerText = document.getElementById("log");
    	var lp = glp_create_prob();
    	glp_read_lp_from_string(lp, null, document.getElementById("source").value);

    	glp_scale_prob(lp, GLP_SF_AUTO);

    	var smcp = new SMCP({presolve: GLP_ON});
    	glp_simplex(lp, smcp);

    	var iocp = new IOCP({presolve: GLP_ON});
    	glp_intopt(lp, iocp);

    	//log("obj: " + glp_mip_obj_val(lp));
    	for(var i = 1; i <= glp_get_num_cols(lp); i++){
        	//log(glp_get_col_name(lp, i)  + " = " + glp_mip_col_val(lp, i));
    	}
        
	var results = [];
			
        log("plf happiness index is : " + glp_mip_obj_val(lp));
		//var stream = document.getElementById("stream").value;
		
        for(var i = 1; i <= glp_get_num_cols(lp); i++){
                // log(glp_get_col_name(lp, i)  + " = " + glp_mip_col_val(lp, i));
			if (glp_mip_obj_val(lp) != 0){
				if ((glp_get_col_name(lp, i) [0])  == "x"){
					var person = parseInt(glp_get_col_name(lp, i) [1]);
					var shift = parseInt(glp_get_col_name(lp, i) [3]);
					var value = parseInt(glp_mip_col_val(lp, i));
					
					results.push([person, shift, value, stream]); //results[person][shift]  = value; 
				}
				
			
			} else {
				alert("There was no feasible solution");
			}
        }
		console.log("sending to php");
		javascriptToPHP(results, studentArrayObj, 'Admin_Gen_Timetable.php');		
}
