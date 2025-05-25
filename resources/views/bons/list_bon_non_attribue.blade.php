    
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
                <h2>Liste des bons en attente d'attribution</h2>

                <div style="padding: 8px;">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <button id="myBtn" class="btn btn-warning">Attribuer les bons</button>
                </div>

                <div class="block">
                    <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>ID</th>
							<th>Date création</th>
                            <th>Date règlement</th>
                            <th>Type Bon</th>
							<th>Montant</th>
						</tr>
					</thead>
					<tbody>
                        @foreach($bons as $b)
                            <tr>
                                <td> {{ $b->id }} </td>
                                <td> {{ $b->date }} </td>
                                <td> {{ $b->date_regl }}  </td>
                                <td> {{ $b->categ }}  </td>
                                <td> {{ number_format($b->pu,0,'.',' ') }} </td>
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
            $('#myBtn').click(function(e){
                $("#myBtn").attr("disabled", true);
                $("#myBtn").text("Veuillez patienter...");
                e.preventDefault();
                $.post("/attribuer", {},
                    function(data, status){
                        if (data === 'success') {
                            alert(`Bon attribué avec succès`);
                            window.location.href = "/list_bon_non_attribue";
                        }
                        else {
                            alert(`Oups! Une erreur s'est produite durant le traitement`);
                            $("#myBtn").attr("disabled", false);
                            $("#myBtn").text("Attribuer les bons");
                        }            
                });
                
            });
        });
    </script>
</body>
</html>
