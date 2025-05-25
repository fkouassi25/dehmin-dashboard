  
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
                <h2>
                    Liste des prestataires</h2>
                <div class="block">
                    
                    
                    <h6>Total <span class="badge badge-warning">{{ number_format($total,0,'.',' ') }} </span></h6>
                    <table class="data display datatable" id="example">
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
                            <th>Actions</th>
						</tr>
					</thead>
					<tbody>
                        @foreach($presta as $p)
                            <tr>
                                <td> {{ $p->code }} </td>
                                <td> {{ $p->nom_magasin }} </td>
                                <td> {{ $p->nom_representant }} </td>
                                <td> {{ $p->commune }} </td>
                                <td> {{ $p->localisation }} </td>
                                <td> {{ $p->tel }} </td>
                                <td> {{ $p->cel }} </td>
                                <td> {{ $p->email }} </td>
                                <td> 
                                    <a href="/presta/{{$p->code}}" style="line-height: initial;" class="btn btn-small btn-warning"> Compte</a> 
                                    <a href="/update_presta/{{$p->code}}" style="line-height: initial;" class="btn btn-small btn-primary"> Modifier</a> 
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
