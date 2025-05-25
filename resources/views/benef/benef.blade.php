
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
                <h2>Détails du Bénéficiaire N° {{ $code }}</h2>

                <div style="text-align:right" class="pt-2">
                    <a href="/bf_carte/{{$code}}" target="_blank" style="line-height: initial;" class="btn btn-warning">Télécharger carte</a>
                </div>

                <div class="block">
                    <h5>Infos Bénéficiaire</h5>
                    <table class="table table-bordered" id="example">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Prénom(s)</th>
                                <th>CNI</th>
                                <th>Commune</th>
                                <th>Cel</th>
                                <th>Référent</th>
                                <th>Conso du mois</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> {{ $benef->code }} </td>
                                <td> {{ $benef->nom }} </td>
                                <td> {{ $benef->prenom }} </td>
                                <td> {{ $benef->cni }} </td>
                                <td> {{ $benef->commune }} </td>
                                <td> {{ $benef->cel }} </td>
                                <td> {{ $benef->referent }} </td>
                                <td> {{ $benef->montant_bon }} </td>
                            </tr>
                        </tbody>
				    </table>
                </div>
                
                <div class="block">
                    <h6>Ses Types de Bons </h6>
                    <table class="data display datatable" id="example">
                        <thead>
                            <tr>
                                <th>Categorie</th>
                                <th>Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($typebons as $dt)
                                <tr>
                                    <td> {{ $dt->libelle }} </td>
                                    <td> {{ number_format($dt->montant,0,'.',' ') }} </td>
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
