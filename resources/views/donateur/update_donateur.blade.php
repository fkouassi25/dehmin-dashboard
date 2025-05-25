@include('header')


<body>
    
    <div class="mycontain">
            @include('menu')
            <!-- <div class="clear">
            </div> -->

            <div class="grid_8">
                <div class="box round first grid">
                    <h2>Modification du Donateur N° {{ $donateur->code }}</h2>
                    <div class="block ">
                        <form id="signupform">
                        <input name="code" id="code" type="hidden" value="{{$donateur->code}}" />
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <table class="form">

                            <tr>
                                <td>
                                    <label> Type Personne </label>
                                </td>
                                <td>
                                    <select id="choixp" name="typepers" required="">
                                        <option value="pp" {{ $donateur->typepers == 'pp' ? 'selected="selected"' : '' }}>Personne physique</option>
                                        <option value="pm" {{ $donateur->typepers == 'pm' ? 'selected="selected"' : '' }}>Personne morale</option>
                                    </select>
                                </td>
                            </tr>

                            <tr class="pp_field">
                                <td class="col1">
                                    <label>Nom</label>
                                </td>
                                <td class="col2">
                                    <input id="nom" name="nom" type="text" value="{{$donateur->nom}}" id="grumble" />
                                </td>
                            </tr>

                            <tr class="pp_field">
                                <td>
                                    <label>Prénoms</label>
                                </td>
                                <td>
                                    <input id="prenom" name="prenom" value="{{$donateur->prenom}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr class="pm_field">
                                <td>
                                    <label> Désignation </label>
                                </td>
                                <td>
                                    <input id="designation" name="designation" value="{{$donateur->designation}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>N° Tel</label>
                                </td>
                                <td>
                                    <input name="tel" value="{{$donateur->tel}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>N° Cel</label>
                                </td>
                                <td>
                                    <input name="cel" value="{{$donateur->cel}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>BP</label>
                                </td>
                                <td>
                                    <input name="bp" value="{{$donateur->bp}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>Référent</label>
                                </td>
                                <td>
                                    <input name="referent" value="{{$donateur->referent}}" type="text" class="mini" />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>Commune</label>
                                </td>
                                <td>
                                    <select id="select" name="commune" required="">
                                        @foreach($communes as $com)
                                            <option value="{{$com}}" {{ $com == $donateur->commune ? 'selected="selected"' : '' }}> {{ $com }} </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>Pays</label>
                                </td>
                                <td>
                                    <select id="select" name="pays" required="">
                                        @foreach($pays as $p)
                                            <option value="{{$p}}" {{ $p == $donateur->pays ? 'selected="selected"' : '' }}> {{ $p }} </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>Ville</label>
                                </td>
                                <td>
                                    <input name="ville" value="{{$donateur->ville}}" type="text" class="mini" />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>Email</label>
                                </td>
                                <td>
                                    <input name="email" value="{{$donateur->email}}" type="email" class="mini" />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>Nouveau Mot de passe</label>
                                </td>
                                <td>
                                    <input name="pwd" type="password" class="mini" />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>Confirmer le mot de passe</label>
                                </td>
                                <td>
                                    <div style="display:inline-block; margin-right:7px"> <input name="pwd_upd_confirm" type="checkbox" /> Je souhaite changer le mot de passe </div>
                                </td>
                            </tr>
                            


                            <tr>
                                <td>
                                    <button id="send" style="background-color: #F90;border-color: #D58000;color: white;" class="btn btn-warning">Modifier</button>
                                </td>
                            </tr>

                        </table>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="clear"></div>
            <div></div>
        </div>
        
        <div class="clear"></div>
        
        @include('footer')

    </div>

    <script>
        var $=jQuery.noConflict();
        $(document).ready(function(){
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        let type = $('#choixp').val();
        if (type == 'pm') {
                $('.pp_field').hide()
                $('.pm_field').show()
        }
        else {
            showFieldPP('')
        }
        

        $('#choixp').change(function(){
            var selectedP = $(this).children("option:selected").val();
            
            if (selectedP == 'pm') {
                $('.pp_field').hide()
                $('.pm_field').show()
            }
            else {
                showFieldPP('')
            }
        });


         $("#send").click(function(e){
            e.preventDefault();
            let form = $("#signupform").serialize();
            let code = $('#code').val();

            let type = $('#choixp').val();
            if (type == 'pm') {
                let designation = $('#designation').val();
                if (designation == '') {
                    alert('Le champs désignation ne peut être vide.');
                    return;
                }
            }
            else {
                let nom = $('#nom').val();
                let prenom = $('#prenom').val();
                if ( nom == '' || prenom == '') {
                    alert('Les champs Nom et Prénoms doivent être renseignés.');
                    return;
                }
            }
            // let isValid = checkValid(form)
            // if(isValid) {
                $.post("/update_donateur", form,
                function(data, status){
                    if (data.code == 'success') {
                        alert(`Modification réussie`);
                        document.location.href = `/donateur/${code}`;
                    }
                    else alert(`${data.msg}`);
                });
            // }
        });

        function showFieldPP(opts) {
            if (opts == 'changeSelectedValue') $('#choixp').val('pp')
            $('.pp_field').show()
            $('.pm_field').hide()
        }

        });
    </script>
</body>
</html>
