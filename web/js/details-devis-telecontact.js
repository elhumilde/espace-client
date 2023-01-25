$(document).ready(function() {

	try {

		$("button.plus-details").on("click", function() {		

			$("p.plus-details").toggleClass("hide");

		});

		$("table#responses").DataTable();

	} catch(e) {

		console.log("error");
	
	}

});