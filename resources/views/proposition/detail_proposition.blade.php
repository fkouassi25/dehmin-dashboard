@include('header')


<body>
    
    <div class="mycontain">
            @include('menu')
            <!-- <div class="clear">
            </div> -->

            <div class="grid_8">
                <div class="box round first grid">
                    <h2>
                        Détails proposition d'un bénéficiaire
                        @if($proposition->statut != 'EN_ATTENTE')
                            [{{$proposition->statut}}]
                        @endif
                    </h2>
                    <div class="block">
                        <form>
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <table class="form">
                            
                            <tr>
                                <td class="col1">
                                    <label>Nom du Parrain</label>
                                </td>
                                <td class="col2">
                                    <input disabled value="{{$proposition->parrain_nom}}" type="text" id="grumble" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="col1">
                                    <label>Type de Pers.</label>
                                </td>
                                <td class="col2">
                                    <input disabled value="{{$proposition->typepersonne}}" type="text" id="grumble" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>Profession du Parrain</label>
                                </td>
                                <td>
                                    <input disabled value="{{$proposition->parrain_profession}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>Email Parrain</label>
                                </td>
                                <td>
                                    <input disabled value="{{$proposition->parrain_email}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>Contact du Parrain</label>
                                </td>
                                <td>
                                    <input disabled value="{{$proposition->parrain_mobile}}" type="text" class="mini" />
                                </td>
                            </tr>

                            
                            <tr>
                                <td>
                                    <label>Nom du Benef</label>
                                </td>
                                <td>
                                    <input disabled value="{{$proposition->benef_nom}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>Prénoms du Benef</label>
                                </td>
                                <td>
                                    <input disabled value="{{$proposition->benef_prenom}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>Localisation du Benef</label>
                                </td>
                                <td>
                                    <input disabled value="{{$proposition->benef_localisation}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>Contact du Benef</label>
                                </td>
                                <td>
                                    <input disabled value="{{$proposition->benef_mobile}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>Lien Parenté</label>
                                </td>
                                <td>
                                    <input disabled value="{{$proposition->benef_lien_parente}}" type="text" class="mini" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td style="vertical-align: top;">
                                    <label>Description</label>
                                </td>
                                <td>
                                    <textarea disabled cols="70" rows="10">{{$proposition->benef_motivation}}</textarea>
                                </td>
                            </tr>
                        
                             @if($proposition->statut == 'EN_ATTENTE')
                                <tr>
                                    <td colspan="2">
                                        <a onclick="return confirm('Êtes-vous sûrs de vouloir refuser la proposition ?')" href="/process_proposition/{{$proposition->id}}/refusee" style="line-height: initial;background-color: #dc3545;border-color: #dc3545;color: white;margin-right:10px;" class="btn btn-danger"> Refuser</a>
                                        <a onclick="return confirm('Voulez-vous vraiment accepter cette proposition ?')" href="/process_proposition/{{$proposition->id}}/validee" style="line-height: initial;background-color: #007bff;border-color: #007bff;color: white;margin-right:10px;" class="btn btn-primary"> Accepter</a>
                                        <a href="/process_proposition/{{$proposition->id}}/signup_validee" style="line-height: initial;background-color: #28a745;border-color: #28a745;color: white;" class="btn btn-primary"> Enregistrer et Accepter</a>
                                    </td>
                                    
                                </tr>
                            @endif
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

</body>
</html>
