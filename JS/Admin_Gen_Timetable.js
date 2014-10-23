/* 
 * Pass javascript variables to PHP (via sending as POST & reloading page) 
 */
function javascriptToPHP( jsvar, pageURL) { 
	console.log("here");
	 $.ajax({
			url: pageURL,
			type: "POST",
			data: {
				'variable[]': jsvar
			},
			success: function (output) {
				console.log("This is a ajax succes" + output);
			},
			error: function () {
				console.log("error");
			}
	});
}


/* 
 * Function for to run simplex algorithm upon pressing the "generate table"  
 */
function run(){
	start = new Date(); 
	logNode.innerText = "";
    	var lp = glp_create_prob();
    	glp_read_lp_from_string(lp, null, document.getElementById("source").value);

    	glp_scale_prob(lp, GLP_SF_AUTO);

    	var smcp = new SMCP({presolve: GLP_ON});
    	glp_simplex(lp, smcp);

    	var iocp = new IOCP({presolve: GLP_ON});
    	glp_intopt(lp, iocp);

    	log("obj: " + glp_mip_obj_val(lp));
    	for(var i = 1; i <= glp_get_num_cols(lp); i++){
        	log(glp_get_col_name(lp, i)  + " = " + glp_mip_col_val(lp, i));
    	}
        
	var results;
			
        log("obj: " + glp_mip_obj_val(lp));
			
        for(var i = 1; i <= glp_get_num_cols(lp); i++){
                // log(glp_get_col_name(lp, i)  + " = " + glp_mip_col_val(lp, i));
		if (glp_mip_obj_val(lp) != 0){
			if (Integer.parseInt(glp_get_col_name(lp, i) [0])  == "x"){
				var person = Integer.parseInt(glp_get_col_name(lp, i) [1]);
				var shift = Integer.parseInt(glp_get_col_name(lp, i) [3]);
				var value = Integer.parseInt(glp_mip_col_val(lp, i));
				results.push([person, shift, value]); //results[person][shift]  = value; 
			}
			console.log(results);
			//javascriptToPHP(results, '../Admin_Gen_Timetable.php');
		
		} else {
			alert("There was no feasible solution");
		}
        }
				
}
