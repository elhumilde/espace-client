$(document).ready(function() {

	"use strict";

	$("#demandeDevisTelecontact").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": URL_DEMANDE_DEVIS,
                "method": "POST",
                "data": function(data) {
                    data.isDataTable = true;
                    return data;
                },
                "dataFilter": function(response) {
                    return response;
                },
            },
            "columns": [
                {"data": "titre"},
                {"data": "name"},
                {"data": "date_creation" , render: function(date_creation) {
                    return date_creation;
                }},
                {"data": "etat" , "render": function(etat) {
                    etat = etat == 1 ? `<span style="background-color:green;padding:4px 8px">Publié</span>` : etat == 2 ? `<span style="background-color:red;padding:4px 8px">Non publié</span>` : `<span style="background-color:gray;padding:4px 8px">En attente</span>`;
                    return etat;
                }},
                {"data": "countReponse", render: function(countReponse) {
                    return $.isNumeric(countReponse) ? countReponse : 0;
                }},
                {"data": "id" , render: function(id) {
                	const url_response = URL_RESPONSES_DEVIS.replace("%20" , "") + id;
                    return `
                    <a style="margin-top:0px" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" href="${url_response}" data-original-title="afficher">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a style="margin-top:0px" id="rechercheBtnf" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="" href="/back-modifier-devis/${id}" data-original-title="supprimer">
                        <i class="fas fa-edit"></i>
                    </a>
                    `;
                }}
            ]
        });

});