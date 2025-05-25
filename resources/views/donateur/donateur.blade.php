
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
                <h2>Détails du Donateur N° {{ $code }}</h2>

                <div class="block">
                    <h5>Infos Donateur</h5>
                    <table class="table table-bordered" id="example">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Type Pers.</th>
                                <th>Désignation / Nom Prénom(s)</th>
                                <th>Commune</th>
                                <th>Ville</th>
                                <th>Pays</th>
                                <th>Tel</th>
                                <th>Cel</th>
                                <th>Email</th>
                                <th>BP</th>
                                <th>Référent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                    <td> {{ $donateur->code }} </td>
                                    <td> {{ $donateur->typepers == 'pp' ? "Personne physique" : "Personne morale" }} </td>
                                    <td> {{ $donateur->designation.$donateur->nom.' '.$donateur->prenom }} </td>
                                    <td> {{ $donateur->commune }} </td>
                                    <td> {{ $donateur->ville }} </td>
                                    <td> {{ $donateur->pays }} </td>
                                    <td> {{ $donateur->tel }} </td>
                                    <td> {{ $donateur->cel }} </td>
                                    <td> {{ $donateur->email }} </td>
                                    <td> {{ $donateur->bp }} </td>
                                    <td> {{ $donateur->referent }} </td>
                            </tr>
                        </tbody>
				    </table>
                </div>
                
                <div class="block">
                    <h6>Ses Commandes </h6>
                    <table class="data display datatable" id="example">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Type Regl</th>
                                <th>Date Regl</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dons as $dt)
                                <tr>
                                    <td> {{ $dt['code'] }} </td>
                                    <td> {{ $dt['date_cmd'] }} </td>
                                    <td> {{ number_format($dt['montant_cmd'],0,'.',' ') }} </td>
                                    <td> {{ $dt['type_regl'] }} </td>
                                    <td> {{ $dt['date_regl'] }} </td>
                                    <td> <a href="/cmd_infos/{{$dt['code']}}" target="_blank" style="line-height: initial;" class="btn btn-small btn-warning"> Détails</a> </td>
                                </tr>
                            @endforeach
                        </tbody>
				    </table>
                </div>
                
                <div class="block">
                    <h6>Ses Bénéficiaires </h6>
                    <table class="data display datatable" id="example">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Prénoms</th>
                                <th>CNI</th>
                                <th>Cel</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($benefs as $dt)
                                <tr>
                                    <td> {{ $dt->code }} </td>
                                    <td> {{ $dt->nom }} </td>
                                    <td> {{ $dt->prenom }} </td>
                                    <td> {{ $dt->cni }} </td>
                                    <td> {{ $dt->cel }} </td>
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
