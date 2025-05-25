  
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

<body>
        @include('menu')

        <div class="grid_10">
            <div class="box round first grid">
                <h2>Liste des donateurs</h2>
                <div class="block">
                    
                    <h6>Total <span class="badge badge-warning">{{ number_format($total,0,'.',' ') }} </span></h6>
                    <table class="data display datatable" id="example">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Type Pers.</th>
                                <th>Désignation / Nom Prénom(s)</th>
                                <th>Commune</th>
                                <th>Tel</th>
                                <th>Cel</th>
                                <th>Email</th>
                                <th>BP</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donateur as $p)
                                <tr>
                                    <td> {{ $p->code }} </td>
                                    <td> {{ $p->typepers == 'pp' ? "Personne physique" : "Personne morale" }} </td>
                                    <td> {{ $p->designation.$p->nom.' '.$p->prenom }} </td>
                                    <td> {{ $p->commune }} </td>
                                    <td> {{ $p->tel }} </td>
                                    <td> {{ $p->cel }} </td>
                                    <td> {{ $p->email }} </td>
                                    <td> {{ $p->bp }} </td>
                                    <td>
                                        <a href="/donateur/{{$p->code}}" style="line-height: initial;" class="btn btn-small btn-warning"> Détails</a> 
                                        <a href="/update_donateur/{{$p->code}}" style="line-height: initial;" class="btn btn-small btn-primary"> Modifier</a> 
                                    </td>
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
