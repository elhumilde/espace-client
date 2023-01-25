$(document).ready(function() {

	"use strict";

	const formUpdatePublication = $("form#form-update-publication");

	$('[name="categorie"]').select2({
		placeholder: "Choisir la categorie ..."
	});
	$('[name="ville"]').select2({
		placeholder: "Choisir la ville ..."
	});

	$('[name="categorie"]').val(window.ID_CATEGORIE).trigger("change");

	$('[name="ville"]').val(window.ID_VILLE).trigger("change");

	ClassicEditor
        .create(document.querySelector('[name="plusDetails"]'))
        .catch(error => { 
        	console.error( error );
        });

	const updateAjaxPublication = function(publication) {

		return new Promise(function(resolve, reject) {

			$.ajax({
				url: window.location.href,
				method: "POST",
				data: publication
			}).done(function(response) {
				resolve(response);
			}).fail(function(error) {
				reject(error);
			});

		});

	}


	formUpdatePublication.on("submit" , function(event) {

		event.preventDefault();

		const fields = $(this).serializeArray();

		const publication = {};

		fields.forEach(function(field) {
			
			publication[field.name] = field.value;

		});

		console.log(publication)

		publication.act = "publicationUpdate";

		if (publication) {

			console.log("publication data : " , publication);

			updateAjaxPublication(publication).then(function(response) {

				if (response.isUpdated) {
					$("#alert-modification").show();

					setTimeout(function() {
						$("#alert-modification").hide();
					} , 4000);
				}
			
			}).catch(function(error) {
			
				console.log(error);
			
			});

		}

	});

});