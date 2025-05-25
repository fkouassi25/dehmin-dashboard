    
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
                    Liste des propositions</h2>
                <div class="block">
                    
                    <h6>Total <span class="badge badge-warning">{{ number_format($total,0,'.',' ') }} </span></h6>
                    <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>Nom Parrain</th>
							<th>Profession</th>
							<th>Email</th>
							<th>Contact</th>
							<th>Lien Parenté</th>							
                            <th>Nom Prénom(s) Bénef</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
						</tr>
					</thead>
					<tbody>
                        @foreach($propositions as $prop)
                            <tr>
                                <td> {{ $prop->parrain_nom }} </td>
                                <td> {{ $prop->parrain_profession }} </td>
                                <td> {{ $prop->parrain_email }} </td>
                                <td> {{ $prop->parrain_mobile }} </td>
                                <td> {{ $prop->benef_lien_parente }} </td>
                                <td> {{ $prop->benef_nom.' '.$prop->benef_prenom }} </td>
                                <td> {{ $prop->date_proposition }} </td>
                                <td> {{ $prop->statut }} </td>
                                
                                <td> 
                                    <a href="/process_proposition/{{$prop->id}}" style="line-height: initial;" class="btn btn-small btn-primary"> Détails</a> 
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
