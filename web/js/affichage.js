

var limit = 1;
$('input.single-checkbox').on('change', function(evt) {
    if($(this).siblings(':checked').length >= limit) {
        this.checked = false;
    }
});


    $(document).ready(function() {

        cal();
        function cal(){
            var total=0;
            var thematique = +$("#thematique").val() || 0;
            var localite = +$("#localite").val() || 0;
            var pfjour = +$("#pfjour").val() || 0;
            var promo = +$("#promo").val() || 0;
            var vignette = +$("#vignette").val() || 0;
            var habillage = +$("#habillage").val() || 0;
            var banniere = +$("#banniere").val() || 0;

            total=thematique + localite+pfjour+promo+vignette+habillage+banniere;


            $("#total").val(total);
        }

        function cal2() {
            var localite = +$("#localite").val() || 0;
            var localite1 = +$("#localite1").val() || 0;
            $("#localite1").val(localite1 + localite);
        }

        /*  var rubrique = $(this);
         console.log(rubrique .val());*/
        // declanché lorsqu'on change le choix de la rub
        $(document).on('click', '.vignette_thematique_ville', function () {
            /* var current = this;
             var ville = $(this);
             var opt1 =$(this);*/
//                var region='R_gion_'+ ville.val();
            var ctar  = 'VA';

            $('#prestaAjax').css('display','');
            console.log("CHANGE rubrique AND prestation....................");
            var select = jQuery(this);
            var v = $('input[name=villes]:checked').val();
            var region = $(this).attr("value");
            console.log('ville : ', v, 'opt1 :',o);
            //     console.log('rubrique : ', r);
            var data_send = { 'ville': v,'opt1':o,'region':v,'ctar':ctar};
            var path = 'ajax_va';
            $.ajax({
                url: path,
                type: 'POST',
                data: data_send,
                statusCode: {
                    //traitement en cas de succès
                    200: function (response) {

                        var successMessage = response.nom.VAR;

                        $('input[id=thematique]').val(successMessage);
                        cal();


                        /*  $('#prestation').children("option").remove();*/
                    },
                    //traitement en cas d'erreur (on peut aussi traiter le cas erreur 500...)
                    412: function (response) {
                        var errorsForm = response.responseJSON.formErrors;
                        //on affiche les erreurs...
                        alert('error');

                    }
                }
            });
            //   $( "#select-rubrique" ).clone().appendTo("#test");
        });


        /////////////////////////

        $(document).on('change', '.vignette_thematique', function (){
            var ctar  = 'VA';
            console.log("CHANGE rubrique AND prestation....................");
            var select = jQuery(this);
            var v = $('input[name=villes]:checked').val();
            var o = $(this).attr("value");
            var region = $(this).attr("value");
            console.log('ville : ', v, 'opt1 :',o);
            //     console.log('rubrique : ', r);
            var data_send = { 'ville': v,'opt1':o,'region':v,'ctar':ctar};
            var $input = $( this );
            if ( $input.is( ":checked" ) )
            {
                $.ajax({
                    url: 'ajax_va',
                    type: 'POST',
                    data: data_send,
                    statusCode: {
                        //traitement en cas de succès
                        200: function (response) {

                            var successMessage = response.nom.VAR;

                            $('input[id=thematique]').val(successMessage);
                            cal();

                            /*  $('#prestation').children("option").remove();*/
                        },
                        //traitement en cas d'erreur (on peut aussi traiter le cas erreur 500...)
                        412: function (response) {
                            var errorsForm = response.responseJSON.formErrors;
                            //on affiche les erreurs...
                            alert('error');

                        }
                    }
                });
            }
            else
            {

                $('input[id=thematique]').val(0);


            }
            //   $( "#select-rubrique" ).clone().appendTo("#test");
        });

        //vignette Localité
        /*$(document).on('click', '.vignette_localite', function ()
         {
         var ctar = 'VL';
         console.log("CHANGE rubrique AND prestation....................");
         var opt1 = $(this).attr("value");
         console.log('ctar : ', ctar, 'opt1 :', opt1);
         var data_send = {'ctar': ctar,'opt1':opt1};

         var $input = $( this );
         if ( $input.is( ":checked" ) )

         {
         $.ajax({
         url:'{{ path('ajax_t') }}',
         type: 'POST',
         data: data_send,
         statusCode: {
         //traitement en cas de succès
         200: function (response) {
         var successMessage = response.nom.VL;

         var t1= $('input[id=localite]').val(successMessage);
         console.log('localite ::' ,($('input[id=localite]').val(successMessage)).val());
         cal2();
         var t= $('input[id=localite1]').val();
         console.log('localite1 ::',t);
         cal();
         },
         412: function (response) {
         var errorsForm = response.responseJSON.formErrors;
         //on affiche les erreurs...
         alert('error');
         }
         }
         });}
         else
         {
         var bn = $("#localite").val();
         var bf = $("#localite1").val();
         var n= (bf-bn);

         $('input[id=localite]').val(0);
         $('input[id=localite1]').val(n);
         cal2();
         cal();


         }

         });
         */
        //solution 2




        $(".vignette_localite").click(function(event) {
            var total = 0;
            $(".vignette_localite:checked").each(function() {
                total += (parseInt($(this).val()));
            });

            if (total == 0) {
                $('#localite').val('');
            } else {
                $('#localite').val(total);
                cal();
            }
        });


        //professionnels du jour
        $(document).on('click', '.professionnels_du_jour', function () {
            var ctar = 'PJ';
            console.log("CHANGE rubrique AND prestation....................");
            var opt1 = $(this).attr("value");
            console.log('ctar : ', ctar, 'opt1 :', opt1);
            var data_send = {'ctar': ctar,'opt1':opt1};

            $.ajax({
                url:'ajax_t',
                type: 'POST',
                data: data_send,
                statusCode: {
                    //traitement en cas de succès
                    200: function (response) {
                        var successMessage = response.nom.T;
                        $('input[id=pfjour]').val(successMessage);
                        cal();
                    },
                    412: function (response) {
                        var errorsForm = response.responseJSON.formErrors;
                        //on affiche les erreurs...
                        alert('error');
                    }
                }
            });


        });
        //espace promo
        $(document).on('click', '.espace_promo', function () {
            var ctar = 'EP';
            console.log("CHANGE rubrique AND prestation....................");
            var opt1 = $(this).attr("value");
            console.log('ctar : ', ctar, 'opt1 :', opt1);
            var data_send = {'ctar': ctar,'opt1':opt1};
            $.ajax({
                url:'ajax_t',
                type: 'POST',
                data: data_send,
                statusCode: {
                    //traitement en cas de succès
                    200: function (response) {
                        var successMessage = response.nom.T;
                        $('input[id=promo]').val(successMessage);
                        cal();
                    },
                    412: function (response) {
                        var errorsForm = response.responseJSON.formErrors;
                        //on affiche les erreurs...
                        alert('error');
                    }
                }
            });

        });

        //habillage

        $(".habillage ").on('click'  ,function (event) {
            var ctar = 'HB';
            console.log("CHANGE rubrique AND prestation....................");
            var opt1 = $(this).attr("value");
            console.log('ctar : ', ctar, 'opt1 :', opt1);
            var data_send = {'ctar': ctar,'opt1':opt1};

            $.ajax({
                url:'ajax_t',
                type: 'POST',
                data: data_send,
                statusCode: {
                    //traitement en cas de succès

                    200: function (response) {



                        var successMessage =  successMessage = response.nom.T;
                        cal();
                        $('input[id=habillage]').val(successMessage);




                    },
                    412: function (response) {
                        var errorsForm = response.responseJSON.formErrors;
                        //on affiche les erreurs...
                        alert('error');
                    }
                }


            });

        });



        //vignette acceuil video
        $(".vignette_acc_video").click(function(event) {
            var total = 0;
            var v = $('input[name=nbr]').val();
            $(".vignette_acc_video:checked").each(function() {
                total += (parseInt($(this).val())* v )/1000;
            });

            if (total == 0) {
                $('#vignette').val('');
            } else {
                $('#vignette').val(total);
                cal();
            }
        });

        //vignette banniere
        $(".banniere").click(function(event) {
            var total = 0;
            var nbr = $('input[name=nombre]').val();
            $(".banniere:checked").each(function() {
                total += (parseInt($(this).val())* nbr )/1000;
            });

            if (total == 0) {
                $('#banniere').val('');
            } else {
                $('#banniere').val(total);
                cal();
            }
        });
        //totale estime

    });

function verifChk(id){
    nbchk = document.getElementById('divchk').getElementsByTagName('input').length;
    var i;
    var nbcochee = 0;
    for(i=1;i<=nbchk;i++){
        if(document.getElementById('chk'+i).checked==true){
            nbcochee++;
            if(nbcochee>1){
                alert('Vous ne pouvez pas en choisir plus d\'un.');
                document.getElementById(id).checked = false;
            }
        }
    }
}






    $(document).ready(function() {
        $(".my-activity").click(function(event) {
            var total = 0;
            $(".my-activity:checked").each(function() {
                total = parseInt($(this).val());
            });

            if (total == 0) {
                $('#total').val('');
            } else {
                $('#total').val(total);
            }
        });
    });

