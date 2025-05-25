    
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
                    Liste des bénéficiaires</h2>
                <div class="block">
                    
                    <h6>Total <span class="badge badge-warning">{{ number_format($total,0,'.',' ') }} </span></h6>
                    <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>Code</th>
							<th>Nom</th>
							<th>Prénom(s)</th>
							<th>CNI</th>
							<th>Référent</th>
                            <th>Cel</th>
                            <th>Actions</th>
						</tr>
					</thead>
					<tbody>
                        @foreach($benefs as $b)
                            <tr>
                                <td> {{ $b->code }} </td>
                                <td> {{ $b->nom }} </td>
                                <td> {{ $b->prenom }} </td>
                                <td> {{ $b->cni }} </td>
                                <td> {{ $b->referent }} </td>
                                <td> {{ $b->cel }} </td>
                                <td> 
                                    <a href="/benef/{{$b->code}}" style="line-height: initial;" class="btn btn-small btn-warning"> Détails</a> 
                                    <a href="/update_benef/{{$b->code}}" style="line-height: initial;" class="btn btn-small btn-primary"> Modifier</a> 
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

    <script>
        var $=jQuery.noConflict();
        $(document).ready(function(){
        
        });
    </script>
</body>
</html>
