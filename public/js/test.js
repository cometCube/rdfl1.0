$(document).on('focus', '#testName', function() {
	$('#errTestName').html('');
	$('#testNameError ul li').html('');
});

$(document).on('focus', '#testDesc', function() {
	$('#errTestDesc').html('');
	$('#testDescError ul li').html('');
});

$(function() {

	$('#mulDeleteTest').on('click', function() {

		var testId = $('input[name="deleteall"]:checked').map(function() {

			return $(this).attr('value');

		}).get();

		if (testId.length != 0) {
			$.ajax({
				type : "GET",
				url : 'test/mulDeleteTest?id=' + testId, //the script to call to get data          
				dataType : 'html',

				success : function(data) {
					$('#myModal').html(data);
					$('#myModal').modal('show');
				},
			});

		} else {
			$.ajax({
				url : "/test/mulDeleteTestAlert",
				success : function(result) {
					$("#myModal").html(result);
					$('#myModal').modal('show');
				}
			});
		}
	});
});

/*add new test by submitting form*/
$(document).on('click', '#submitbutton', function(event) {
	var $form = $(this);
	var $target = $($form.attr('data-target'));
	event.preventDefault();
	if (validateTestname() == 1) {
		$('#myModal').modal('show');
		return 0;
	} else if (validateTestname() == 0) {

		var catname = $('#testname').val();
		var catid = 0;

		$.ajax({
			url : "/test/add",
			type : 'POST',
			data : $("#test").serialize(),
			// dataType : 'json',

			success : function(result) {
				try {
					result = $.parseJSON(result);
					if (result.status == 0) {

						$('#myModal').modal('hide');

						window.location.assign("test");
					} else if (result.status == 2) {
						// alert("test already taken");
						$('#errTestName').html('Test already exists');
					}
				} catch (e) {
					$('#myModal').html(result);
					if ($('#testNameError ul li').text() !== "") {
						$('#testNameError').css("display", "block");
					}
					if ($('#testDescError ul li').text() !== "") {
						$('#testDescError').css("display", "block");
					}
				}
			}
		});
	}

});

/* edit test by submitting form */
$(document).on('click', '#submitEdit', function(event) {
	event.preventDefault();
	if (validateTestname() == 1) {
		$('#myModal').modal('show');
		return 0;
	} else if (validateTestname() == 0) {
		$.ajax({
			url : "/test/edit",
			type : 'POST',
			data : $("#editTest").serialize(),
			// dataType : 'json',

			success : function(result) {
				try {
					result = $.parseJSON(result);
					if (result.status == 0) {

						$('#myModal').modal('hide');

						window.location.assign("test");
					} else if (result.status == 1) {
						// alert("test already taken");
						$('#errTestName').html('Test already exists');
					}
				} catch (e) {
					$('#myModal').html(result);
					if ($('#testNameError ul li').text() !== "") {
						$('#testNameError').css("display", "block");
					}
					if ($('#testDescError ul li').text() !== "") {
						$('#testDescError').css("display", "block");
					}
				}
			}
		});
	}
});

/* to view question details in popup in test */
$(document).on('click', '.ancrQuestion', function() {

	var data = this.id;

	var urlform = "../../question/view/" + data;
	$.ajax({
		url : urlform,
		success : function(data) {
			$('#modal-body2').html(data);
			$('.editQuestion').css('display','none');
		},
		error : function(data) {
			console.log(data);
		},
	});
});

// Shorthand for $( document ).ready()
$(function() {

	$("#regist").click(function() {
		$.ajax({
			url : 'student/registration',

			success : function(result) {
				$('#formdiv').html(result);
			}
		});
	});

	//load create test page in popup on "New test" button click
	$('#btnTestCreate').click(function() {
		$('#myModalLabel').html("Add New test");
		$.ajax({
			type : "GET",
			url : '/test/add', //the script to call to get data          
			data : "", //you can insert url argumnets here to pass to api.php for example "id=5&parent=6"
			dataType : 'html',

			success : function(data) {
				$('#myModal').html(data);
				$('#myModal').modal('show');
			},
		});
	});

	/*
	 $("#btnTestDetails").on('click', function() {
	 alert($(this).data("id"));
	 $.ajax({
	 url : 'test/testDetails/' + $("#btnTestDetails").data("id"),
	 beforeSend : function() {

	 },
	 success : function(data) {
	 alert(data);
	 // $('#myModal').html('');
	 //$('#testDetailsModal').html(data);
	 },

	 });
	 });
	 */

});

/*function editTest(testId){
 $.ajax({
 url : 'test/edit/' + testId,
 success : function(result) {
 $('#testEditModal').html(result);
 }
 });
 }*/

/*function deleteTest(testId){
 $.ajax({
 url : 'test/delete/' + testId,
 success : function(result) {
 $('#testDeleteModal').html(result);
 }
 });
 }*/

//load edit test page in popup on "edit Test" button click
function btnEditTest(testId) {
	$('#myModalLabel').html("Edit Test");
	//var testIdNameDesc = $(this).attr('id');
	$.ajax({
		type : "GET",
		url : 'test/edit/' + testId, //the script to call to get data          
		//data: {id:$(this).data('testid')}, //you can insert url argumnets here to pass to api.php for example "id=5&parent=6"
		dataType : 'html',

		success : function(data) {
			$('#myModal').html(data);
			$('#myModal').modal('show');
		},
	});
}

//used to send request to test controller to load delete test popup
function btnDeleteTest(testId) {
	//alert(testId);
	$('#myModalLabel').html("Delete Test");

	$.ajax({
		type : "GET",
		url : 'test/delete/' + testId, //the script to call to get data          
		//data: {id:testId}, //you can insert url argumnets here to pass to api.php for example "id=5&parent=6"
		dataType : 'html',

		success : function(data) {
			$('#myModal').html(data);
			$('#myModal').modal('show');
		},
	});
}

function testDetails(testId) {
	$.ajax({
		url : 'test/testDetails/' + testId,
		beforeSend : function() {
		},
		success : function(data) {
			// $('#myModal').html('');
			$('#testDetailsModal').html(data);
		},

	});
}

$(function() {
	$('#btnAssignques').click(
			function() {
				var questionIds = $('input[name="deleteall"]:checked').map(
						function() {
							return $(this).attr('value');
						}).get();

				//alert(questionIds[1]);
				if (questionIds.length != 0) {
					$.ajax({
						type : "POST",
						url : '/test/addSelectedQuesToTest/'
								+ $("#testId").val(), //the script to call to get data
						data : {
							"addQuesIds" : questionIds
						},
						dataType : 'json',

						success : function(response) {
							//$('#modal-body').html(data);
							if (response.flag == 1) {
								window.location.href = '/test/viewTest/'
										+ $("#testId").val();
							}
						},
					});

				} else {
					alert("Please Select Atleast One Question !");
				}
			});
});

//used to delete a question from test
function btndeleteTestQues(quesId) {
	//alert(testId);
	$('#myModalLabel').html("Delete Questions From Test");

	$.ajax({
		type : "GET",
		url : '/test/deleteTestQues/' + quesId, //the script to call to get data          
		//data: {id:testId}, //you can insert url argumnets here to pass to api.php for example "id=5&parent=6"
		dataType : 'html',

		success : function(data) {
			$('#myModal').html(data);
			$('#myModal').modal('show');
		},
	});
}

/*to multi-delete questions from test*/
$(function() {

	$('#mulDeleteTestQues').on('click', function() {

		var quesIds = $('input[name="deleteall"]:checked').map(function() {

			return $(this).attr('value');

		}).get();
		if (quesIds.length != 0) {
			$.ajax({
				type : "GET",
				url : '/test/muldeleteTestQues', //the script to call to get data
				data : 'id=' + quesIds,
				dataType : 'html',

				success : function(data) {
					$('#myModal').html(data);
					$('#myModal').modal('show');
				},
			});

		} else {
			alert("No Questions Selected For Deletion From Test ?");
		}
	});
});

function validateTestname() {
	//return 0;
	var testName = 'testName';
	var testdes = 'testDesc';
	//alert(isNull(catname));
	if (isNull(testName) === 0 && isNull(testdes) === 0) {
		$('#errTestName').html('Please Enter Test Name');

		$('#errTestDesc').html('Please Enter Test Description');

		return 1;
	}

	else if (isNull(testName) === 0 && isNull(testdes) === 1) {
		$('#errTestName').html('Please Enter Test Name');
		$('#errTestDesc').html('');
		return 1;

	}

	else if (isNull(testName) === 1 && isNull(testdes) === 0) {
		$('#errTestDesc').html('Please Enter Test Description');
		$('#errTestName').html('');
		return 1;

	}

	else if (validateCategoryName(testName) === 0) {
		$('#errCat').html('Enter valid Test Name');
		return 1;
	}

	return 0;
}

function validateedittestname() {

	var testName = 'txtCategoryName';
	//alert(isNull(catname));
	if (isNull(testName) === 0) {
		$('#errCatEdit').html('Please Enter Test Name');
		return 1;
	} else if (validateCategoryName(testName) === 0) {
		$('#errCatEdit').html('Enter valid Test Name');
		return 1;
	}
	return 0;
}
