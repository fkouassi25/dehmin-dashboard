    
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
                <h2>Détails du Bon N° {{ $attribution->num_bon }}</h2>
                
                <div class="block">
                    <h6>Solde <span class="badge badge-warning">{{ number_format($attribution->solde,0,'.',' ') }} </span></h6>
                    <table class="data display datatable">
                        <thead>
                            <tr>
                                <th>Code Prestataire</th>
                                <th>Nom Prestataire</th>
                                <th>Date</th>
                                <th>Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details as $det)
                                <tr>
                                    <td>{{ $det->code }}</td>
                                    <td>{{ $det->nom_magasin }}</td>
                                    <td>{{ $det->date }}</td>
                                    <td>{{ number_format($det->montant,0,'.',' ') }}</td>
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

</body>
</html>
