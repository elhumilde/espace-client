$("select").select2({
            theme: "bootstrap-5",
            selectionCssClass: "select2--small", // For Select2 v4.1
            dropdownCssClass: "select2--small",
        });

        $(document).ready(function () {
            //function initialize() {

            var x = "33.5720635";
            var y = "-7.6574303";

            // x=$('[tts_text=latitude]').text();
            // y=$('[tts_text=longitude]').text();

            var myLatlng = new google.maps.LatLng(x, y);

            var myOptions = {
                zoom: 15,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

            addMarker(myLatlng, 'Default Marker', map);
            //Désactiver l'affichage des frontières
            var marocStyles = [{featureType: "administrative.country", stylers: [{visibility: "off"}]}];
            var marocMapType = new google.maps.StyledMapType(marocStyles, {name: "Maroc"});

            // Associer la carte de style avec le MapTyped
            map.mapTypes.set('maroc', marocMapType);
            map.setMapTypeId('maroc');
            layer = new google.maps.FusionTablesLayer({
                query: {
                    select: 'geometry',
                    from: '1S4aLkBE5u_WS0WMVSchhBgMLdAARuPEjyW4rs20',
                    where: "col1 contains 'MAR'"
                },
                styles: [{
                    polylineOptions: {
                        strokeColor: "#6E6E6E",
                        strokeWeight: 1
                    }
                }]
            });
            layer.setMap(map);

            //}

            function addMarker(latlng, title, map) {
                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    title: title,

                    draggable: true

                });


                google.maps.event.addListener(marker, 'drag', function (event) {


                    $('#entreprise_longitude').val(event.latLng.lng());
                    $('#entreprise_latitude').val(event.latLng.lat());

                    $('.checkbox-68').css('display','block');
                    $('.checkbox-69').css('display','block');
                    $(".label-68").css('display','block');
                    $(".label-69").css('display','block');

                });

                google.maps.event.addListener(marker, 'dragend', function (event) {


                    $('#entreprise_longitude').val(event.latLng.lng());
                    $('#entreprise_latitude').val(event.latLng.lat());

                    $('.checkbox-68').css('display','block');
                    $('.checkbox-69').css('display','block');
                    $(".label-68").css('display','block');
                    $(".label-69").css('display','block');

                });
            }

        });

        function callCheckbox(id){
            /*alert(id);
            var ok = $(this).attr("id");
            alert(ok);*/
            $(".label-"+id).css('display','block');
            $(".checkbox-"+id).css('display','block');
            $('.input-'+id).css('background-color', 'rgb(242 242 242 / 70%)');
            $('.input-'+id).css('border', '1px solid #e9ecef');
            $('.select-'+id+' .select2-container--bootstrap-5 .select2-selection').css('background-color', 'rgb(242 242 242 / 70%)');
            $('.select-'+id+' .select2-container--bootstrap-5 .select2-selection').css('border', '1px solid #e9ecef');
        }

        function checkPhone(id){
            var telephone = $(".input-"+id).val();
            var reg   = new RegExp("^0|[1-9][0-9]*$");
            if(reg.test(telephone) && telephone.length >= 10 && telephone.length <= 20 ){
                $(".checkbox-"+id).css('display','block');  
                $('.span_telephone-'+id).css('display','none');
                $(".label-"+id).css('display','block');
            }else{
                $(".checkbox-"+id).css('display','none');  
                $('.span_telephone-'+id).css('display','block'); 
            }
        }

        function checkEmail(id){
            var email = $(".input-"+id).val();
            var reg = new RegExp("(.+)@(.+){2,}\.(.+){2,}");
            if(reg.test(email)){
                $(".checkbox-"+id).css('display','block');  
                $(".label-"+id).css('display','block');
                $('.span_email-'+id).css('display','none');
            }else{
                $(".checkbox-"+id).css('display','none');  
                $('.span_email-'+id).css('display','block'); 
            }
        }


        function checkYear(id){
            var year = $(".input-"+id).val();
            if(year.length == 4){
                $(".checkbox-"+id).css('display','block');  
                $('.span_year-'+id).css('display','none');
                $(".label-"+id).css('display','block');
            }else{
                $(".checkbox-"+id).css('display','none');  
                $('.span_year-'+id).css('display','block'); 
            }
        }

        function checkIce(id){
            var ice = $(".input-"+id).val();
            if(ice.length == 15){
                $(".checkbox-"+id).css('display','block');  
                $('.span_ice-'+id).css('display','none');
                $(".label-"+id).css('display','block');
            }else{
                $(".checkbox-"+id).css('display','none');  
                $('.span_ice-'+id).css('display','block'); 
            }
        }

        



        function add_portable(){
            var lien_portable_count = $('#lien_portable_count').val();
            lien_portable_count     = ++lien_portable_count;
            $('#lien_portable_count').val(lien_portable_count);
            $(".tts_portable").append('<div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12"><label class="col-md-12 p-0">Portable '+ lien_portable_count +' :</label><div class="col-md-12 change-box-group"><input type="text" class="input-7'+ lien_portable_count +' lien_portable portable portable add form-control" value="" onchange="checkPhone(7'+ lien_portable_count +');"><input class="checkbox checkbox-7'+ lien_portable_count +' image-clignote" type="checkbox" value="" onclick="validate(7'+ lien_portable_count +');"></div><span class="validation_span span_telephone-7'+ lien_portable_count +'">Le champ portable est invalid</span><label class=" label_validation label-7'+ lien_portable_count +'">cocher la case pour valider ce changement</label></div>');
        }

        function add_tel(){
            var lien_telephone_count = $('#lien_telephone_count').val();
            lien_telephone_count     = ++lien_telephone_count;
            $('#lien_telephone_count').val(lien_telephone_count);
            $(".tts_telephone").append('<div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12"><label class="col-md-12 p-0">Téléphone '+ lien_telephone_count +' :</label><div class="col-md-12 change-box-group"><input type="text" class="input-4'+ lien_telephone_count +' lien_telephone tel téléphone add form-control" value="" onchange="checkPhone(4'+ lien_telephone_count +');"><input class="checkbox checkbox-4'+ lien_telephone_count +' image-clignote" type="checkbox" value="" onclick="validate(4'+ lien_telephone_count +');"></div><span class="validation_span span_telephone-4'+ lien_telephone_count +'">Le champ téléphone est invalid</span><label class=" label_validation label-4'+ lien_telephone_count +'">cocher la case pour valider ce changement</label></div>');
        }

        function add_fax(){
            var lien_fax_count = $('#lien_fax_count').val();
            lien_fax_count     = ++lien_fax_count;
            $('#lien_fax_count').val(lien_fax_count);
            $(".tts_fax").append('<div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12"><label class="col-md-12 p-0">Fax '+ lien_fax_count +' :</label><div class="col-md-12 change-box-group"><input type="text" class="input-8'+ lien_fax_count +' lien_telephone fax fax add form-control" value="" onchange="checkPhone(8'+ lien_fax_count +');"><input class="checkbox checkbox-8'+ lien_fax_count +' image-clignote" type="checkbox" value="" onclick="validate(8'+ lien_fax_count +');"></div><span class="validation_span span_telephone-8'+ lien_fax_count +'">Le champ fax est invalid</span><label class=" label_validation label-8'+ lien_fax_count +'">cocher la case pour valider ce changement</label></div>');
        }

        function add_email(){
            var lien_email_count = $('#lien_email_count').val();
            lien_email_count     = ++lien_email_count;
            $('#lien_email_count').val(lien_email_count);
            $(".tts_email").append('<div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12"><label class="col-md-12 p-0">Email '+ lien_email_count +':</label><div class="col-md-12 change-box-group"><input type="text" class="input-5'+ lien_email_count +' lien_email email email add form-control" value="" onchange="checkEmail(5'+ lien_email_count +');"><input class="checkbox checkbox-5'+ lien_email_count +' image-clignote" type="checkbox" value="" onclick="validate(5'+ lien_email_count +');"></div><span class="validation_span span_email-5'+ lien_email_count +'">Le champ email est invalid</span><label class=" label_validation label-5'+ lien_email_count +'">cocher la case pour valider ce changement</label></div>');
        }

        function add_site(){
            var lien_web_count = $('#lien_web_count').val();
            lien_web_count     = ++lien_web_count;
            $('#lien_web_count').val(lien_web_count);
            $(".tts_web").append('<div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12"><label class="col-md-12 p-0">Site web '+ lien_web_count +':</label><div class="col-md-12 change-box-group"><input type="text" class="input-6'+ lien_web_count +' lien_web web site_web add form-control" value="" onchange="callCheckbox(6'+ lien_web_count +');"><input class="checkbox checkbox-6'+ lien_web_count +' image-clignote" type="checkbox" value="" onclick="validate(6'+ lien_web_count +');"> </div><label class=" label_validation label-6'+ lien_web_count +'">cocher la case pour valider ce changement</label></div>');
        }
        

        function add_dirigeant(){
            var lien_dirigeant_count = $('#lien_dirigeant_count').val();
            lien_dirigeant_count     = ++lien_dirigeant_count;
            $('#lien_dirigeant_count').val(lien_dirigeant_count);

            $('.tts_dirigeant').append(`
                <div class="box_dirigeant_${lien_dirigeant_count} appended_dir row" >
                    <h5>Dirigeant ${lien_dirigeant_count}</h5>

                    <input type="hidden" class="code-personne-${lien_dirigeant_count}" value="" />

                    <div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12">
                        <label class="col-md-12 p-0">Sexe :</label>
                        <div class="col-md-12 change-box-group">
                            <select class="input-dir_sex_9${lien_dirigeant_count} personne sex sexe form-control" >
                                <option value=""></option>
                                <option value="M">M</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12">
                        <label class="col-md-12 p-0">Civilité :</label>
                        <div class="col-md-12 change-box-group">
                            <select class="input-dir_civilite_9${lien_dirigeant_count} personne civilite civilité form-control" >
                                <option value=""></option>
                                {% for c in lien_civilite %}
                                    <option value="{{ c.code }}">{{ c.civilite }}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12">
                        <label class="col-md-12 p-0">Fonction :</label>
                        <div class="col-md-12 change-box-group">
                            <select class="input-dir_fonction_9${lien_dirigeant_count} lien_dirigeant code_fonction fonction form-control" onchange="checkFonction('dir_fonction_9${lien_dirigeant_count}');">
                                <option value=""></option>
                                {% for f in lien_fonction %}
                                    <option value="{{ f.code }}">{{ f.fonction }}</option>
                                {% endfor %}
                            </select>
                        </div>
                        <span class="validation_span span_required-dir_fonction_9${lien_dirigeant_count}">le champs fonction est obligatoire</span>
                    </div>

                    <div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12">
                        <label class="col-md-12 p-0">Nom :</label>
                        <div class="col-md-12 change-box-group">
                            <input type="text" class="input-dir_nom_9${lien_dirigeant_count} personne nom nom form-control" value="" onblur="checkNom('dir_nom_9${lien_dirigeant_count}');">
                        </div>
                        <span class="validation_span span_required-dir_nom_9${lien_dirigeant_count}">le champs nom est obligatoire</span>
                    </div>

                    <div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12">
                        <label class="col-md-12 p-0">Prenom :</label>
                        <div class="col-md-12 change-box-group">
                            <input type="text" class="input-dir_prenom_9${lien_dirigeant_count} personne prenom prenom form-control" value="" onblur="checkPrenom('dir_prenom_9${lien_dirigeant_count}');" >
                        </div>
                        <span class="validation_span span_required-dir_prenom_9${lien_dirigeant_count}">le champs prenom est obligatoire</span>
                    </div>

                    <div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12">
                        <label class="col-md-12 p-0">Email :</label>
                        <div class="col-md-12 change-box-group">
                            <input type="text" class="input-dir_email_9${lien_dirigeant_count} personne email email form-control" value="" onblur="checkEmailDir('dir_email_9${lien_dirigeant_count}');">
                        </div>
                        <span class="validation_span span_email-dir_email_9${lien_dirigeant_count}">le champs email est invalid</span>
                        <span class="validation_span span_required-dir_email_9${lien_dirigeant_count}">le champs email est obligatoire</span>
                    </div>
                    

                    <div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12">
                        <label class="col-md-12 p-0">Tel 1 :</label>
                        <div class="col-md-12 change-box-group">
                            <input type="text" class="input-tel-dir_tel_1_9${lien_dirigeant_count} lien_dirigeant tel_1 telephone_1 form-control" value="" onblur="checkTel1('dir_tel_1_9${lien_dirigeant_count}');">   
                        </div>
                        <span class="validation_span span_tel1-dir_tel_1_9${lien_dirigeant_count}">le champs telephone 1 est invalid</span>
                        <span class="validation_span span_tel1_required-dir_tel_1_9${lien_dirigeant_count}">le champs telephone 1 est obligatoire</span>
                    </div>

                    <div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12">
                        <label class="col-md-12 p-0">Tel 2 :</label>
                        <div class="col-md-12 change-box-group">
                            <input type="text" class="input-tel-dir_tel_2_9${lien_dirigeant_count} lien_dirigeant tel_2 telephone_2 form-control" value="" onblur="checkTel2('dir_tel_2_9${lien_dirigeant_count}');">
                        </div>
                        <span class="validation_span span_tel2-dir_tel_2_9${lien_dirigeant_count}">le champs telephone 2 est invalid</span>
                    </div>

                    <div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12">
                        <label class="col-md-12 p-0">Fax :</label>
                        <div class="col-md-12 change-box-group">
                            <input type="text" class="input-tel-dir_fax_9${lien_dirigeant_count} lien_dirigeant fax fax form-control" value="" onblur="checkFax('dir_fax_9${lien_dirigeant_count}');">                     
                        </div>
                        <span class="validation_span span_fax-dir_fax_9${lien_dirigeant_count}">le champs fax est invalid</span>
                    </div>

                    <div class="form-group mb-4 col-lg-4 col-xlg-4 col-md-6 col-sm-12">
                        <button type="button" class="btn btn-success saveDirigeant saveDirigeant${lien_dirigeant_count}" onclick="save_dirigeant('${lien_dirigeant_count}');" >Valider</button>
                    </div>

                </div>

            `);                                                     
                                         
        }

        


        /* dirigeant add validation */

        function checkEmailDir(id){
            var email = $('.input-'+id).val();
            var reg_email = new RegExp("(.+)@(.+){2,}\.(.+){2,}");
            if(reg_email.test(email)){
                $('.span_email-'+id).css('display', 'none');
                $('.span_required-'+id).css('display','none');
            }else{
                $('.span_email-'+id).css('display','block');
                $('.span_required-'+id).css('display','none');
            }
        }

        function checkTel1(id){
            var tel_1 = $('.input-tel-'+id).val();
            var reg   = new RegExp("^0|[1-9][0-9]*$");
            if(reg.test(tel_1) && tel_1.length >= 10 && tel_1.length <= 20){
                $('.span_tel1-'+id).css('display', 'none');
                $('.span_tel1_required-'+id).css('display','none');
            }else{
                $('.span_tel1-'+id).css('display','block');
                $('.span_tel1_required-'+id).css('display','none');
            }
        }

        function checkTel2(id){
            var tel_2 = $('.input-tel-'+id).val();
            var reg   = new RegExp("^0|[1-9][0-9]*$");
            if((reg.test(tel_2) && tel_2.length >= 10 && tel_2.length <= 20) || (tel_2 == "")){
                $('.span_tel2-'+id).css('display', 'none');
            }else{
                $('.span_tel2-'+id).css('display','block');
            }
        }

        function checkFax(id){
            var fax = $('.input-tel-'+id).val();
            var reg   = new RegExp("^0|[1-9][0-9]*$");
            if((fax == "") || (reg.test(fax) && fax.length >= 10 && fax.length <= 20)  ){
                $('.span_fax-'+id).css('display', 'none');

            }else{
                $('.span_fax-'+id).css('display','block');
            }
        }

        function checkNom(id){
            var nom    = $('.input-'+id).val();
            if(nom == ""){
                $('.span_required-'+id).css('display','block');
            }else{
                $('.span_required-'+id).css('display','none');
            }      
        }

        function checkPrenom(id){
            var prenom = $('.input-'+id).val();
            if(prenom == ""){
                $('.span_required-'+id).css('display','block');
            }else{
                $('.span_required-'+id).css('display','none');
            }     
        }

        function checkFonction(id){
            var fonction = $('.input-'+id).val();
            if(fonction == ""){
                $('.span_required-'+id).css('display','block');
            }else{
                $('.span_required-'+id).css('display','none');
            }
        }
         /* end dirigeant add validation */

         /* PRESTATION VALIDATION */

        function checkPrestation(id){
            var prestation = $('.input-'+id).val();
            if(prestation == ""){
                $('.span_required-'+id).css('display','block');
            }else{
                $('.span_required-'+id).css('display','none');
            }
        }

        /* end validation prestation */
        
