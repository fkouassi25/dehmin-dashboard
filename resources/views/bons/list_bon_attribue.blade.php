    
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
                <h2>Liste des bons attribués</h2>
                
                @php
                    $total = 0;
                    $montant = 0;
                    foreach($data as $dt) {
                        $total += 1;
                        $montant += $dt->pu;
                    }
                @endphp

                <div class="block">
                    
                    <h6>Total <span class="badge badge-warning">{{ number_format($total,0,'.',' ') }} bons </span>
                    <span class="badge badge-warning">{{ number_format($montant,0,'.',' ') }} FCFA </span>
                    </h6>
                    
                    
                    <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>N° Bon</th>
							<th>Date attribution</th>
							<th>Type Bon</th>
							<th>Montant</th>
                            <th>Nom/Prénoms Bénéficiaire</th>
                            <th>Statut</th>
                            <th>Action(s)</th>
						</tr>
					</thead>
					<tbody>
                        @foreach($data as $dt)
                            <tr>
                                <td> {{ $dt->num_bon }} </td>
                                <td> {{ $dt->date_attr }} </td>
                                <td> {{ $dt->typebon }}  </td>
                                <td> {{ number_format($dt->montant_reel,0,'.',' ') }} </td>
                                <td> {{ $dt->nom." ".$dt->prenom }}  </td>
                                <td> {{ $dt->statut }}  </td>
                                <td>
                                    <a href="/detail_bon/{{$dt->id_attr}}" target="_blank" style="line-height: initial;" class="btn btn-small btn-warning"> Détails</a> 
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
