@include('header')


<body>
    
    <div class="mycontain">
            @include('menu')
            <!-- <div class="clear">
            </div> -->

            <div class="grid_8">
                <div class="box round first grid">
                    <h2>Modification du Prestataire N° {{ $presta->code }}</h2>
                    <div class="block ">
                        <form id="signupform">
                        <input name="code" id="code" type="hidden" value="{{$presta->code}}" />
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <table class="form">
                            <tr>
                                <td class="col1">
                                    <label>
                                        Nom Magasin</label>
                                </td>
                                <td class="col2">
                                    <input name="nom_magasin" type="text" value="{{$presta->nom_magasin}}" id="grumble" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        Localisation</label>
                                </td>
                                <td>
                                    <input name="localisation" value="{{$presta->localisation}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label> Nom Representant </label>
                                </td>
                                <td>
                                    <input name="nom_representant" value="{{$presta->nom_representant}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>
                                        N° Tel</label>
                                </td>
                                <td>
                                    <input name="tel" value="{{$presta->tel}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>
                                        N° Cel</label>
                                </td>
                                <td>
                                    <input name="cel" value="{{$presta->cel}}" type="text" class="mini" />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>
                                        Commune</label>
                                </td>
                                <td>
                                    <select id="select" name="commune" required="">
                                        @foreach($communes as $com)
                                            <option value="{{$com}}" {{ $com == $presta->commune ? 'selected="selected"' : '' }}> {{ $com }} </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>Email</label>
                                </td>
                                <td>
                                    <input name="email" value="{{$presta->email}}" type="email" class="mini" />
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
         $("#send").click(function(e){
            e.preventDefault();
            let form = $("#signupform").serialize();
            let code = $('#code').val();
            // let isValid = checkValid(form)
            // if(isValid) {
                $.post("/update_presta", form,
                function(data, status){
                    if (data.code == 'success') {
                        alert(`Modification réussie`);
                        document.location.href = `/presta/${code}`;
                    }
                    else alert(`${data.msg}`);
                });
            // }
        });
        });
    </script>
</body>
</html>
