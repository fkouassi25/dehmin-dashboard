@include('header')


<body>
    
    <div class="mycontain">
            @include('menu')
            <!-- <div class="clear">
            </div> -->

            <div class="grid_8">
                <div class="box round first grid">
                    <h2>Modification du bénéficiaire N° {{ $benef->code }}</h2>
                    <div class="block ">
                        <form id="signupform">
                        <input name="code" id="code" type="hidden" value="{{$benef->code}}" />
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <table class="form">
                            <tr>
                                <td class="col1">
                                    <label>
                                        Nom</label>
                                </td>
                                <td class="col2">
                                    <input name="nom" type="text" value="{{$benef->nom}}" id="grumble" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        Prénoms</label>
                                </td>
                                <td>
                                    <input name="prenom" value="{{$benef->prenom}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>
                                        N° CNI</label>
                                </td>
                                <td>
                                    <input name="cni" value="{{$benef->cni}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>
                                        N° Cel</label>
                                </td>
                                <td>
                                    <input name="cel" value="{{$benef->cel}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>Référent</label>
                                </td>
                                <td>
                                    <input name="referent" value="{{$benef->referent}}" type="text" class="mini" />
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
                                            <option value="{{$com}}" {{ $com == $benef->commune ? 'selected="selected"' : '' }}> {{ $com }} </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>
                                        Bons alimentaires</label>
                                </td>
                                <td>
                                    <div style="display:inline-block; margin-right:7px"> <input {{ in_array("ba_250",$bons) ? 'checked' : '' }} name="ba_250" type="checkbox" /> 250 </div>
                                    <div style="display:inline-block; margin-right:7px"> <input {{ in_array("ba_500",$bons) ? 'checked' : '' }} name="ba_500" type="checkbox" /> 500 </div>
                                    <div style="display:inline-block; margin-right:7px"> <input {{ in_array("ba_2500",$bons) ? 'checked' : '' }} name="ba_2500" type="checkbox" /> 2 500 </div>
                                    <div style="display:inline-block;"> <input {{ in_array("ba_6000",$bons) ? 'checked' : '' }} name="ba_6000" type="checkbox" /> 6 000 </div>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>
                                        Bons médicaux</label>
                                </td>
                                <td>
                                    <div style="display:inline-block; margin-right:7px"> <input {{ in_array("bs_250",$bons) ? 'checked' : '' }} name="bs_250" type="checkbox" /> 250 </div>
                                    <div style="display:inline-block; margin-right:7px"> <input {{ in_array("bs_500",$bons) ? 'checked' : '' }} name="bs_500" type="checkbox" /> 500 </div>
                                    <div style="display:inline-block; margin-right:7px"> <input {{ in_array("bs_2500",$bons) ? 'checked' : '' }} name="bs_2500" type="checkbox" /> 2 500 </div>
                                    <div style="display:inline-block;"> <input {{ in_array("bs_6000",$bons) ? 'checked' : '' }} name="bs_6000" type="checkbox" /> 6 000 </div>
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
                $.post("/update_benef", form,
                function(data, status){
                    if (data.code == 'success') {
                        alert(`Modification réussie`);
                        document.location.href = `/benef/${code}`;
                    }
                    else alert(`${data.msg}`);
                });
            // }
        });
        });
    </script>
</body>
</html>
