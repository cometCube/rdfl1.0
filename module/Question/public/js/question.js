/*   
***************Function for category validation ***************
if category is not selected give warning 
to select atleast one category from dropdown!!.
*/
$(document).on('blur', '#category', function() 
{	
	var category_data = $('#category').val();
		if (category_data == 0) {
			$("#option_select_err").html("Please enter category !!");
		}
		else {
			$("#option_select_err").html(" ");	
	    }
});

/*    
*****************Function for question area*******************
The submit button will enable if cursor will focus on question field
*/
$(document).on('focus', '#description_id', function() 
{
	$("#submitbuttonQuestion").prop("disabled", false);
});
/*   
*****************Function for all field *******************
The error message will show for empty field or wrong input.!
*/
$(document).on('click', '#submitbuttonQuestion', function() 
{
	//*************Category validation*********************
	var category = $('#category').val();
	if (category == 0) {
		$("#option_select_err").html("please select category!!");
		return false;
	} 
	else {
		$("#option_select_err").html("");
	}
	//*************Question description validation*********************
	var description = $('#description_id').val();
	
	if (description == "") {
		$("#option_select_err").html("please enter question!!");
		return false;
	} 
	else {
		$("#option_select_err").html("");
	}

	var question_data = $('#description_id').val();
	str=question_data.replace(/ +(?= )/g,'');
	$('#description_id').val(str);

	if(str < 2){
		$("#option_select_err").html("Please enter valid question !!");
		return false;
	}
	//*************Qestion marks validation*********************
	var points = $('#point').val();
	
	if ((points == "")) {
		$("#option_select_err").html("please enter question marks!!");
		return false;
	} 
	else {
		$("#option_select_err").html(" ");
	}
	//*************Question marks should be interger only validation*********
	var points1 = $('#point').val();	
	var re = /^[0-9]*$/;
	if (!re.test(points1)) {
		$("#option_select_err").html("please enter valid marks!!");
		return false;
	} 
	else {
		$("#option_select_err").html(" ");
	}
	//*************Question marks should be 1 to 10 validation*********************
	var point_data = $('#point').val();
	if (point_data < 1) {
		$("#option_select_err").html("Marks should greater than 0 ");
		return false;
	} 
	else if (point_data > 10) {
		$("#option_select_err").html("Marks should less 10");
		return false;
	} 
	else {
		$("#option_select_err").html(" ");	
	}

	//**************Correct option should be makred validation**************
	//**************data_type = 1 for Multiple choice question***************
	//**************data_type = 0 for True False question********************
	var type_data = $('#type').val();
	if (type_data == 1) {

		var testchecked = jQuery(':checkbox:checked').length;
		
		if (testchecked == 0) {
			$("#option_select_err").html("Please select correct options");
			return false;
		} else {
			$("#option_select_err").html(" ");
		}
	} 
	else {
		if ($("#optionAns1").prop("checked")) {
			return true;
	    }
	    else if($("#optionAns0").prop("checked")){
			return true;
		}
		else{
			$("#option_select_err").html("Please select correct options");
			return false;
		}
	}
	//*******Answer should be written in each answer filed*********************
	var countansweroptions=$('.question_option_div').size();
   
    for(var x = 0; x <= countansweroptions; x++) {
        var question_option = $("#optionDescription" + x).val();
        if(question_option=='')
            {
            $("#option_select_err").html("Please enter answer options ");
            return false;
            }
    }

    var countansweroptions=$('.question_option_div').size();
	for(var x = 0; x <= countansweroptions; x++)
	{
	    var answer_data = $("#optionDescription" + x).val();	
		str=answer_data.replace(/ +(?= )/g,'');
		$('#optionDescription'+ x).val(str);
		if(str < 2){
			$("#option_select_err").html("Please enter valid answer !!");
			return false;
		}
	}
}); //End of all validation field function.
/*   
*****************Function for question marks validsation *******************
Error message will show if question marks filed will empty.
*/
$(document).on('click', '#point', function() 
{
	var point_data = $('#point').val();
	if (point_data == '') {
		//$("#submitbuttonQuestion").prop("disabled", true);
		$("#option_select_err").html("please enter question marks");
		return false;
	} else {
		$("#submitbuttonQuestion").prop("disabled", false);
		return true;
	}
});
/*   
*****************Function for question marks validsation *******************
Error message will show if question marks will empty and will start fililing answrs.
*/
$(document).on('click', '.question_option_div', function() 
{
	var points = $('#point').val();
	if ((points == "")) {
		$("#option_select_err").html("please enter question marks");
		return false;
	} 
	else {	
		$("#option_select_err").html(" ");
	}
});
/*   
*****************Function for question marks*******************
Error message will show if question marks will not enter.
*/
$(document).on('click', '#editsubmitbutton', function() 
{
	var description = $('#description_id').val();
	
	if (description == "") {
		
		$("#T_F_option_select_err").html("please enter question!!");
		return false;
	} 
	else {
		$("#T_F_option_select_err").html("");
		
	}

	var question_data = $('#description_id').val();
	str=question_data.replace(/ +(?= )/g,'');
	$('#description_id').val(str);

	if(str < 2){
		$("#T_F_option_select_err").html("Please enter valid question !!");
		return false;
	}


	var point_data = $('#point').val();
	if (point_data == '') {
		$("#T_F_option_select_err").html("please enter points!!");
		return false;
	} 

	var points1 = $('#point').val();	
	var re = /^[0-9]*$/;
	if (!re.test(points1)) {
		$("#T_F_option_select_err").html("please enter valid marks!!");
		$('#point').val("");
		return false;
	} 
	else {
		$("#T_F_option_select_err").html(" ");
	}

	var point_data = $('#point').val();
	if (point_data < 1) {
		$("#T_F_option_select_err").html("Marks should greater than 0 ");
		return false;
	} 
	else if (point_data > 10) {
		$("#T_F_option_select_err").html("Marks should less 10");
		return false;
	} 
	else {
		$("#T_F_option_select_err").html(" ");	
	}

	 var type_data = $('#type').val();
	if (type_data == 1) {
		var testchecked_multiple = jQuery(':checkbox:checked').length;

		if (testchecked_multiple == 0) {

			$("#T_F_option_select_err").html("Please select correct options");
			return false;
		} 
		else {
			$("#T_F_option_select_err").html("");
		}
	} 

	var countansweroptions=$('.question_option_div').size();

    for(var x = 0; x <= countansweroptions; x++) {
        var question_option = $("#optionDescription" + x).val();
        if(question_option=='')
        {
            $("#T_F_option_select_err").html("Please enter answer options ");
            return false;
        }
    }

    var countansweroptions=$('.question_option_div').size();
	for(var x = 0; x <= countansweroptions; x++)
	{
	    var answer_data = $("#optionDescription" + x).val();	
		str=answer_data.replace(/ +(?= )/g,'');
		$('#optionDescription'+ x).val(str);
		if(str < 2){
			$("#T_F_option_select_err").html("Please enter valid answer !!");
			return false;
		}
	}


   
});
/*   
*****************Function for open edit page*******************
Edit page will open with data from the database.
*/
$(document).on('click', '.editQuestion', function() 
{
	var data = this.id;
	var urlform = "question/edit/" + data;
	$.ajax({
		url : urlform,
		success : function(data) {
		$('#editForm').html(data);
	},
		error : function(data) {
		console.log(data);
	},
	});
});
/*   
*****************Function for change category *******************
Category will show in dropdown from database.
*/
$(document).on('change','#cat_id',function() 
{	
	var ques_type = $('#question_type').val();
	if ($('#question_type').val() == '2') {
		var questiontype = $('#question_type').val();
		var data = this.value;
		var urlform = "question/list/" + data;
		$.ajax({
			url : urlform,
			success : function(data) {
			$('#listQuestions').html(data);
		},
			error : function(data) {
			console.log(data);
		},
		});
	} 
	else {
		$.ajax({
			url : "question/listtypeandcategory",
			data : "categorytype=" + $('#cat_id').val()+ "&questiontype=" + ques_type,
			success : function(data) {
			$('#listQuestions').html(data);
		},
			error : function(data) {
			console.log(data);
		},
		});
	}
});
/*   
*****************Function for change question type *******************
Question will display after filtering the category and question type.
*/
$(document).on('change','#question_type',function() 
{	
	var ques_type = $('#question_type').val();
	if ($('#cat_id').val() == '0') {
		var cat_id = $('#cat_id').val();
		var data = this.value;
		var urlform = "question/listtype/" + data;
		$.ajax({
			url : urlform,
			success : function(data) {
			$('#listQuestions').html(data);
		},
			error : function(data) {
			console.log(data);
		},
		});
	} 
	else {
		
		$.ajax({
			url : "question/listtypeandcategory",
			data : "categorytype=" + $('#cat_id').val()	+ "&questiontype=" + ques_type,
			success : function(data) {
		$('#listQuestions').html(data);
		},
			error : function(data) {
			console.log(data);
		},
		});
	}
});
/*   
*****************Function for change question type *******************
When question type will change on add question page then all
the error message will display off.
*/
$(document).on('change','#type',function() 
{
	$("#option_select_err").html(" ");
	$("#T_F_option_select_err").html("");
});
  

/*   
*****************Function for delete question*******************
*/
$(document).on('click', '.deleteQuestion', function() 
{
	var questionid=this.id;
	
	
	$.ajax({
		url : "/question/delete",
		data : "categoryselected=" + $('#cat_id').val()+ "&questiontype=" + 
				$('#question_type').val()+"&questionid=" + questionid,
		success : function(result) {
		$("#modal-body").html(result);
	}
	});
});

/*   
*****************Function for multiple delete question*******************
*/
function deletingSingleQuestion($categoryid,$questiontype,$questionid) 
{
	
		$.ajax({
			url : "/question/deleteSingleSelectedQuestion/"+$questionid,
		 
			success : function(data) {
			
			var str="../question?catques="+ $categoryid+"&questiontype="+$questiontype;
			
		    window.location.href=str;
			
	
		}
	});
}
/*   
*****************Function for disable editquestion class*******************
*/
$(document).on('click', '.editQuestion', function() 
{
	$("#type").prop("disabled", true);
});
/*   
*****************Function for delete multiple question*******************
This function will selected id of all selected question.
*/
$(document).on('click', '.deletebtnQuestion', function() 
{
	
	var testchecked = jQuery(':checkbox:checked').length;
	if (testchecked == 0) {
		$.ajax({
			url : "/question/muldeletequestionalert",
			success : function(result) {
			$("#modal-body").html(result);
		}
		});
	} 
	else {
		$.ajax({
			url : "/question/muldeletequestion",
			data : "categoryselected=" + $('#cat_id').val()+ "&questiontype=" + $('#question_type').val()	,
			success : function(result) {
			$("#modal-body").html(result);
		}
		});
	}
});
/*   
*****************Function for delete mupltiple question*******************
*/
function deletingQuestion($categoryid,$questiontype) 
{
	
	var j=0;
	var boxes = document.getElementsByClassName('deleteCheckQuestion');
	var box=new Array();
	for(var i = 0; i<boxes.length; i++){
		if(boxes[i].checked){
			    box[j]=boxes[i].value;
			    j++;
		}
	}
	var str = box.join();
	$.ajax({
		url : "/question/deleteSelectedQuestion",
		data : {
			id : str
		},
		success : function(data) {		
			var str="../question?catques="+ $categoryid+"&questiontype="+$questiontype;	
		    window.location.href=str;
		}
	});
}
/*   
 
*****************Function for view question*******************
*/
$(document).on('click', '.viewQuestion', function() 
{
	var data = this.id;
	var urlform = "question/view/" + data;
	$.ajax({
		url : urlform,
		success : function(data) {
			$('#myModalLabel').html("Question Details");
			$('#modal-body').html(data);
		},
			error : function(data) {
			console.log(data);
		},
	});
});
/*   
*****************Function for restrict enter space in ***********
*****************starting of question input*******************
*/
$(document).ready(function() 
{	
	$("#description_id").on("keypress", function(e) {
	if (e.which === 32 && !this.value.length)
		e.preventDefault();
	});
	$("#point").on("keypress", function(e) {
	if (e.which === 32 && !this.value.length)
		e.preventDefault();
	});
});
/*   
*****************Function for disable submit button ************
******************when none category is selected ***********
*/
$(document).on('change','#category',function() 
{
	var category = $('#category').val();
	if (category == 0) {
		$("#option_select_err").html("please select category!!");
		
	}
	else {
		$("#option_select_err").html(" ");
		
	}
});
/*   
*****************Function for disable submit button ************
******************when none type is selected ***********
*/
$(document).on('change','#type',function() 
{
	var question_type = $('#type').val();
	if (question_type == 0) {
		
		$("#option_select_err").html(" ");
	}
	else if (question_type == 1) {
		
		$("#option_select_err").html(" ");
	}
	else {
		
		$("#option_select_err").html("please select question type!!");
	}
});
//csv checking file extension
$(document).on('click', '#csvuploadfile', function() {
	
	$("#csv_import_err").html(" ");
});


$(document).on('click','.content',function() 
{
	$("#option_select_err").html(" ");
	$("#T_F_option_select_err").html(" ");
});
$(document).on('click', '#back_to_question', function() {
   
    var category_selected=$('#cat_id_add').val();
  
    var str="../../question?catques="+ category_selected+"&questiontype="+$('#type').val()+"&flag="+0;
           
     window.location.href=str;
           

});
 $(document).on('keypress keyup blur', '.points', function() 
 {
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
     if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
         event.preventDefault();
    }
 });
