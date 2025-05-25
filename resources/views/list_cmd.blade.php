  
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
                <h2>Liste des commandes</h2>
                <div class="block">
                    <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>Code</th>
							<th>Date</th>
                            <th>Donateur</th>
                            <th>Cel Donateur</th>
							<th>Montant</th>
							<th>Statut</th>
							<th>Action(s)</th>
						</tr>
					</thead>
					<tbody>
                        @foreach($commandes as $c)
                            <tr>
                                <td> {{ $c->code }} </td>
                                <td> {{ $c->date_cmd }} </td>
                                <td> {{ $c->donateur->nom.' '.$c->donateur->prenom }}  </td>
                                <td> {{ $c->donateur->cel }}  </td>
                                <td> {{ number_format($c->montant_cmd,0,'.',' ') }} </td>
                                <td> {{ $c->statut }}  </td>
                                <td> <a href="/cmd_infos/{{$c->code}}" style="line-height: initial;" class="btn btn-small btn-warning"> Détails</a> </td>
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
