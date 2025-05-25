    
    @include('header')
    
    <!-- import specific -->
    <script type='text/javascript' src='https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js'></script>
    <script type='text/javascript' src='https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js'></script>
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                "language": {
                "search": "Recherche:",
                "lengthMenu": "Affiche _MENU_ lignes",
                "zeroRecords": "Aucun élément à afficher",
                "info": "Page _PAGE_ sur _PAGES_",
                "infoEmpty": "Aucun élément à afficher",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "paginate": {
                    "previous": "Précédent",
                    "next": "Suivant"
                }
                },
                "bFilter": false
            });
        });
    </script>

<body>
        @include('menu')

        <div class="grid_10">
            <div class="box round first grid">
                <h2>Détails de la commande N° {{ $code_cmd }}</h2>

                <div style="text-align:right" class="pt-2">
                    <button id="myBtn" data-toggle="modal" data-target="#myModal" class="btn btn-warning">Régler la commande</button>
                </div>

                
                <div id="myModal" class="modal">
                    <div class="modal-dialog" role="document">
                    <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Règlement de la commande</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        <div class="modal-body">
                            <form id="form_regler">
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect2">Type de réglement</label>
                                    <select name="type_regl" class="form-control" id="exampleFormControlSelect2">
                                        <option>Espèce</option>
                                        <option>Chèque</option>
                                        <option>Virement bancaire</option>
                                        <option>Orange Money</option>
                                        <option>Moov Money</option>
                                        <option>MTN Money</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Date du règlement:</label>
                                    <input name="date_regl" type="text" placeholder="dd/mm/yyyy" class="form-control" id="recipient-name">
                                    <input type="hidden" name="code_cmd" value="{{$code_cmd}}">
                                    <p class="text-muted">Exemple: 01/01/2020</p>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            <button id="reglercmd" type="button" class="btn btn-warning">Confirmer</button>
                        </div>
                    </div>
                    </div>
                </div>


                <div class="block">
                    <h5>Infos Donateur</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Type Pers.</th>
                                <th>Désignation / Nom Prénom(s)</th>
                                <th>Tel</th>
                                <th>Cel</th>
                                <th>BP</th>
                                <th>Commune</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $donateur->code }}</td>
                                <td> {{ $donateur->typepers == 'pp' ? "Personne physique" : "Personne morale" }} </td>
                                <td> {{ $donateur->typepers == 'pp' ? $donateur->nom.' '.$donateur->prenom : $donateur->designation }} </td>
                                <td> {{ $donateur->tel }} </td>
                                <td> {{ $donateur->cel }} </td>
                                <td> {{ $donateur->bp }} </td>
                                <td> {{ $donateur->commune }} </td>
                                <td> {{ $donateur->email }} </td>
                            </tr>    
                        </tbody>
                    </table>
                </div>
                
                <div class="block">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Détails commande</h6>
                        </div>
                        <div class="col-md-6">
                            <p style="text-align:right;padding-right:15px">
                                <b>Total</b> <span class="badge badge-warning"> {{ number_format($total,0,'.',' ') }} </span>
                            </p>
                        </div>
                    </div>
                    <table class="data display datatable" id="example">
                        <thead>
                            <tr>
                                <th>Type Bon</th>
                                <th>Qté</th>
                                <th>PU</th>
                                <th>Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lignes as $l)
                                <tr>
                                    <td> {{ $l->libelle }} </td>
                                    <td> {{ $l->qte }} </td>
                                    <td> {{ $l->pu }}  </td>
                                    <td> {{ number_format(intval($l->pu) * $l->qte,0,'.',' ') }}  </td>
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
    $('#reglercmd').click(function(e){
        e.preventDefault();
        let form = $("#form_regler").serialize();
        $.post("/regl_cmd", form,
            function(data, status){
                if (data === 'success') {
                    alert(`Réglement effectué avec succès`);
                    window.location.href = "/list_cmd_attente_regl";
                }
                else {
                    alert(`Oups! Une erreur s'est produite durant le traitement`);
                } 
        });
    });
});
</script>

</body>
</html>
