@include('header')


<body>
    
    <div class="mycontain">
    <div class="container_12" style="background-color:#CBD5E0">
        <div class="clear">
        </div>
        

            <div style="width:100%;">
                <div class="grid_6" style="width: 50%;margin: 0 auto;display:block;float:none;">
                    <div class="box round first grid">
                        <h2>Authentification</h2>
                        <div class="block ">
                            <form id="signupform" method="POST" action="/login">
                            @csrf
                            <table class="form">
                                <tr>
                                    <td class="col3">
                                        <label>Email</label>
                                    </td>
                                    <td class="col2">
                                        <input name="email" class="medium" type="text" id="grumble" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col3">
                                        <label>Mot de Passe</label>
                                    </td>
                                    <td>
                                        <input name="pwd" type="password" class="medium" />
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <button id="send" style="background-color: #F90;border-color: #D58000;color: white;" class="btn btn-warning">Connexion</button>
                                    </td>
                                </tr>

                            </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="clear"></div>
            <div></div>
        </div>
        
        <div class="clear"></div>
        
        @include('footer')

    </div>

</body>
</html>
