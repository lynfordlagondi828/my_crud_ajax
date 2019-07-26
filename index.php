<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AJAX | Create Read Update and Delete</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="DataTables/css/dataTables.bootstrap.min.css">
    </head>
<body>
<div class="container" style="margin-top: 40px;">
	<div id="tableManager" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header"><h3 class="modal-title">Add Comment</h3></div>
					
					<div class="modal-body">
						<div id="editContent">
							<input class="form-control" type="text" id="name" placeholder="Name"><br>
							<textarea class="form-control" type="text" rows="8" cols="40" id="comment" placeholder="Comment"></textarea><br>
                            <input class="form-control" type="hidden" id="editRowID" value="0">
						</div>

						<div id="showContent" style="display:none;">
							<label>Name</label>
								<p id="showName">
								</p>
								<hr>
							<label>Comment</label>
							<p id="showComment">
							</p>
							<hr>
						</div>
					</div>
						<div class="modal-footer">
	                        <input type="button" class="btn btn-primary" data-dismiss="modal" value="Close" id="closeBtn" style="display: none;">
	                        <input type="button" id="manageBtn" onclick="manageData('addNew')" value="Save" class="btn btn-success">
	                    </div>
				</div>
		</div>
	</div>

	<div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2>AJAX | CRUD</h2>

                <input type="button" class="btn btn-primary" id="addNew" value="Add New">
                <br><br>
                <table id="table" class="table table-hover table-bordered table-responsive">
                    <thead>
                        <tr>
                            <td>Name</td>
                            <td>Comment</td>
                            <td>Date</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
</div>

 <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="DataTables/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="DataTables/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<!--JavaScript-->
<script type="text/javascript">

	$(document).ready(function(){
		$("#addNew").on('click',function(){
			$("#tableManager").modal('show');
			$("#closeBtn").fadeIn();
		});

		$("#tableManager").on('hidden.bs.modal', function () {
            $("#editContent").fadeIn();
            $("#editRowID").val(0);
            $("#name").val("");
             $("#comment").val("");

             $("#closeBtn").fadeOut();
             $("#manageBtn").attr('value', 'Save').attr('onclick', "manageData('addNew')").fadeIn();
        });

		fetchComments(0,10);
	});

	//Fetchng Comments
	function fetchComments(start,limit){

		$.ajax({
			url:'ajax.php',
			method:'POST',
			dataType:'text',
			data:{
				key:'list_of_comment',
				start:start,
				limit:limit
			},success:function(response){
				if (response != "reachedMax") {

					$('tbody').append(response);

					start += limit;
					fetchComments(start,limit);

				} else {
					
					$(".table").DataTable();
				}
			}
		});
	}

	//Delete Record
	function deleteRow(rowID){

		if (confirm("Are you sure?")) {

			$.ajax({
				url:'ajax.php',
				method:'POST',
				dataType:'text',
				data:{
					key:'delete',
					rowID:rowID
				},success:function(response){
					$("#name"+rowID).parent().remove();
					alert(response);
					window.location="index.php";
				}
			});
		}
	}

	//Edit 
	function ViewOreditRow(rowID, type){

		$.ajax({
			url:'ajax.php',
			method:'POST',
			dataType:'json',
			data:{
				key:'get_single_row',
				rowID:rowID
			},success:function(response){

				if (type == "view") {

					$("#editContent").fadeOut();
					$("#manageBtn").fadeOut();
					$("#closeBtn").fadeIn();
					
					$("#showContent").fadeIn();

					$("#showName").html(response.name)
					$("#showComment").html(response.comment);


				} else {

					$("#editContent").fadeIn();
                    $("#showContent").fadeOut();
					$("#closeBtn").fadeIn();
					$("#manageBtn").fadeIn();

                    $("#editRowID").val(rowID);
                    $("#name").val(response.name);
                    $("#comment").val(response.comment);
                    $("#manageBtn").attr('value', 'Save Changes').attr('onclick', "manageData('updateRow')");
				}

				$(".modal-title").html(response.name);
                $("#tableManager").modal('show');
			}
		});
	}

	/**
        * Manage Data
        */
        function manageData(key){

            var name = $("#name");
            var comment = $("#comment");
            var editRowID = $("#editRowID");

            if(isNotEmpty(name) && isNotEmpty(comment)){

                        $.ajax({
                            url:'ajax.php',
                            method:'POST',
                            dataType: 'text',
                            data:{
                                key: key,
                                name: name.val(),
                                comment: comment.val(),
                                rowID: editRowID.val()
                            }, success:function(response){

                                if(response == "success"){
                                    alert(response);
                                    $("#tableManager").modal('hide');
                                    window.location="index.php";
                                } else {
                                    $("#name_"+editRowID.val()).html(name.val());
                                    name.val('');
                                    comment.val('');

                                   $("#tableManager").modal('hide');
                                   $("#manageBtn").attr('value', 'Save').attr('onclick', "manageData('addNew')");
                                }
                                
                            }
                        });
                    }

        }
        /**
        * Is not Empty
        */
        function isNotEmpty(caller){
            if(caller.val() ==''){
                
                caller.css('border','1px solid red');
                return false;
            } else 
                caller.css('border','');
            return true;
        }
</script>

</body>
</html>