
    @include('header')

    <!-- import specific -->
    <script type='text/javascript' src='https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js'></script>
    <script type='text/javascript' src='https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js'></script>
    <script type="text/javascript" src="{{asset('js/mydata-table.js')}}"></script>
    <script>
        $(document).ready(function() {
            mydataTable();
        });
    </script>
    
</head>
<body>
        @include('menu')

        <div class="grid_10">
            <div class="box round first grid">
                <h2>Détails du Prestataire N° {{ $code }}</h2>

                <div style="text-align:right" class="pt-2">
                    <button id="myBtn" data-toggle="modal" data-target="#myModal" class="btn btn-warning">Approvisionner le compte</button>
                </div>


                <div id="myModal" class="modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Approvisonner le compte</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="form_regler">
                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Veuillez indiquer le montant d'approvisionnement:</label>
                                        <input name="montant" type="text" class="form-control">
                                        <input type="hidden" id="code" name="code" value="{{$code}}">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                <button id="approvisionner_cmpt" type="button" class="btn btn-warning">Confirmer</button>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="block">
                    <h5>Infos Prestataire</h5>
                    <table class="table table-bordered" id="example">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Nom Magasin</th>
                                <th>Nom Représentant</th>
                                <th>Commune</th>
                                <th>Localisation</th>
                                <th>Tel</th>
                                <th>Cel</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> {{ $presta->code }} </td>
                                <td> {{ $presta->nom_magasin }} </td>
                                <td> {{ $presta->nom_representant }} </td>
                                <td> {{ $presta->commune }} </td>
                                <td> {{ $presta->localisation }} </td>
                                <td> {{ $presta->tel }} </td>
                                <td> {{ $presta->cel }} </td>
                                <td> {{ $presta->email }} </td>
                            </tr>
                        </tbody>
				    </table>
                </div>
                
                <div class="block">
                    <h6>Détails du compte [Solde <span class="badge badge-warning">{{ number_format($solde,0,'.',' ') }} </span> ]</h6>
                    <table class="data display datatable" id="example">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Désignation</th>
                                <th>Date opération</th>
                                <th>Débit</th>
                                <th>Crédit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compte as $dt)
                                <tr>
                                <td> {{ $dt['id'] }} </td>
                                <td> {{ $dt['designation'] }} </td>
                                <td> {{ $dt['date_oper'] }} </td>
                                <td> {{ number_format($dt['debit'],0,'.',' ') }} </td>
                                <td> {{ number_format($dt['credit'],0,'.',' ') }} </td>
                                </tr>
                            @endforeach
                        </tbody>
				    </table>
                </div>

               
            </div>
        </div>
        
        
        <div class="clear">
        </div>
    </div>
    <div class="clear">
    </div>
    
    @include('footer')
    
<script>

var $=jQuery.noConflict();
$(document).ready(function(){
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $('#approvisionner_cmpt').click(function(e){
        e.preventDefault();
        let form = $("#form_regler").serialize();
        let code = $('#code').val();
        $.post("/presta_approv", form,
            function(data, status){
                if (data === 'success') {
                    alert(`Approvionnement effectué avec succès`);
                    window.location.href = `/presta/${code}`;
                }
                else {
                    modal.style.display = "none";
                    alert(`Oups! Une erreur s'est produite durant le traitement`);
                } 
                    
        });
    });
        
});
</script>



<!-- 
<script>
  
</script> -->

</body>
</html>
