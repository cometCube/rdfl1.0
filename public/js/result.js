	

	//used to send ajax request to result controller to load delete result popup
	function btnDeleteResult(resId) {
		$('#myModalLabel').html("Delete Result");

		$.ajax({
			type : "GET",
			url : 'result/delete/' + resId, //the script to call to get data          
			dataType : 'html',

			success : function(data) {
				$('#myModal').html(data);
				$('#myModal').modal('show');
			},
		});
	}
	
	$(document).on('click','#btnDeleteAllbtnResult',
	        function() {
		
		$('#myModalLabel').html("Delete Result");
		
		var atLeastOneIsChecked = $('input[name="deleteall"]:checked').length > 0;

		if(atLeastOneIsChecked)
		{
			$.ajax({
				type : "GET",
				url : 'result/deleteall', //the script to call to get data          
				dataType : 'html',
	
				success : function(data) {
					$('#modal-body').html(data);
					$('#myModal').modal('show');
				},
			});
		}
		else{
			$('#modal-body').html('You havent selected any checkbox');
		}
	});
	
	$(document).on('click','.btnDeletebtnResult',
	        function() {
		resultId = $(this).attr('id');
		$('#myModalLabel').html("Delete Result");

		$.ajax({
			type : "POST",
			url : 'result/delete/' + resultId, //the script to call to get data          
			dataType : 'json',

			success : function(data) {
				if(data.status == 1){
					window.location.assign("/result");
				}
			},
		});
	});
	
	$(document).on('click','#btnMulDelResult',
			function() {
			var testId = $('input[name="deleteall"]:checked').map(function() {
				return $(this).attr('value');

			}).get();
			if (testId.length != 0) {
				$.ajax({
					type : "POST",
					url : 'result/deleteall', //the script to call to get data          
					dataType : 'json',
					data : {'id':testId},
					success : function(data) {
						if(data.status == 1){
							window.location.assign("/result");
						}
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
