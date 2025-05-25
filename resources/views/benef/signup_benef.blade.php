@include('header')


<body>
    
    <div class="mycontain">
            @include('menu')
            <!-- <div class="clear">
            </div> -->

            <div class="grid_8">
                <div class="box round first grid">
                    <h2>
                        Formulaire d'inscription d'un bénéficiaire
                        @if(session('proposition'))
                            [via Parrain]
                        @endif
                    </h2>
                    <div class="block ">
                        <form id="signupform">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <table class="form">
                            @if(session('proposition'))
                                <tr>
                                    <td class="col1">
                                        <label>Nom du Parrain</label>
                                    </td>
                                    <td class="col2">
                                        <input disabled value="{{session('proposition')->parrain_nom}}" type="text" id="grumble" />
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        <label>Profession du Parrain</label>
                                    </td>
                                    <td>
                                        <input disabled value="{{session('proposition')->parrain_profession}}" type="text" class="mini" />
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td class="col1">
                                    <label>Nom</label>
                                </td>
                                <td class="col2">
                                    <input name="nom" value="{{ session('proposition') ? session('proposition')->benef_nom : '' }}" type="text" id="grumble" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Prénoms</label>
                                </td>
                                <td>
                                    <input name="prenom" value="{{ session('proposition') ? session('proposition')->benef_prenom : '' }}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>N° CNI</label>
                                </td>
                                <td>
                                    <input name="cni" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>N° Cel</label>
                                </td>
                                <td>
                                    <input name="cel" value="{{ session('proposition') ? session('proposition')->benef_mobile : '' }}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>Référent</label>
                                </td>
                                <td>
                                    <input name="referent" value="{{ session('proposition') ? session('proposition')->parrain_nom : '' }}" type="text" class="mini" />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>Commune</label>
                                </td>
                                <td>
                                    <select id="select" name="commune" required="">
                                        @foreach($communes as $com)
                                            <option value="{{$com}}"> {{ $com }} </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>Bons alimentaires</label>
                                </td>
                                <td>
                                    <div style="display:inline-block; margin-right:7px"> <input name="ba_250" type="checkbox" /> 250 </div>
                                    <div style="display:inline-block; margin-right:7px"> <input name="ba_500" type="checkbox" /> 500 </div>
                                    <div style="display:inline-block; margin-right:7px"> <input name="ba_2500" type="checkbox" /> 2 500 </div>
                                    <div style="display:inline-block;"> <input name="ba_6000" type="checkbox" /> 6 000 </div>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>Bons médicaux</label>
                                </td>
                                <td>
                                    <div style="display:inline-block; margin-right:7px"> <input name="bs_250" type="checkbox" /> 250 </div>
                                    <div style="display:inline-block; margin-right:7px"> <input name="bs_500" type="checkbox" /> 500 </div>
                                    <div style="display:inline-block; margin-right:7px"> <input name="bs_2500" type="checkbox" /> 2 500 </div>
                                    <div style="display:inline-block;"> <input name="bs_6000" type="checkbox" /> 6 000 </div>
                                </td>
                            </tr>

                            @if(session('proposition'))
                                <input type="hidden" name="proposition_state" value="{{session('proposition')->id}}" />
                            @endif

                            <tr>
                                <td>
                                    <button id="send" style="background-color: #F90;border-color: #D58000;color: white;" class="btn btn-warning">Enregistrer</button>
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
            $("#send").text('Un instant svp...').attr('disabled', true);
            // let isValid = checkValid(form)
            // if(isValid) {
                $.post("/signup_benef", form,
                function(data, status){
                    if (data.code == 'success') {
                        alert(`Inscription réussie`);
                        document.getElementById("signupform").reset();
                        $("#send").text('Enregistrer').attr('disabled', false);
                    }
                    else {
                        alert(`${data.msg}`);
                        $("#send").text('Enregistrer').attr('disabled', false);
                    }
                });
            // }
        });
        });
    </script>
</body>
</html>
