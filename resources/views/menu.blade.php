<div class="container_12" style="background-color:#CBD5E0">
<div class="grid_12 header-repeat">
            <div id="branding" style="background-color:#E85921">
                <div class="floatleft">
                    <img style="width: 180px;background-color: white;" src="{{asset('img/logo.png')}}" alt="Logoo" /></div>
                <div class="floatright">
                    <div class="floatleft">
                        <img src="{{asset('img/img-profile.jpg')}}" alt="Profile Pic" /></div>
                    <div class="floatleft marginleft10">
                        <ul class="inline-ul floatleft">
                            <li> {{ Auth::user()->nom }} {{ Auth::user()->prenom }} </li>
                            <li><a href="{{route('logout')}}">Déconnexion</a></li>
                        </ul>
                    </div>
                </div>
                <div class="clear">
                </div>
            </div>
        </div>
        <div class="clear">
        </div>
        
        <div class="grid_2">
            <div class="box sidemenu">
                <div class="block" id="section-menu">
                    <ul class="section menu">
                        <li>
                            <!-- <a class="menuitem" data-toggle="collapse" aria-expanded="false" role="button" aria-controls="demo" href="#demo">Bénéficiaire</a> -->
                            <button class="my-custom-btn" data-toggle="collapse" data-target="#benef" aria-expanded="false" aria-controls="benef">
                                Bénéficiaire
                            </button>
                            <ul id="benef" class="collapse show submenu">
                                <li><a class="{{ Route::is('signup_benef') ? 'active_a' : '' }}" href="{{route('signup_benef')}}">Inscription</a> </li>
                                <li><a class="{{ Route::is('list_benef') ? 'active_a' : '' }}" href="{{route('list_benef')}}">Liste des bénéficiaires</a> </li>
                                <li><a class="{{ Route::is('list_proposition') ? 'active_a' : '' }}" href="{{route('list_proposition')}}">Liste des propositions</a> </li>
                            </ul>
                        </li>
                        
                        <li>
                            <!-- <a class="menuitem">Prestataire</a> -->
                            <button class="my-custom-btn" data-toggle="collapse" data-target="#presta" aria-expanded="false" aria-controls="presta">
                                Prestataire
                            </button>
                            <ul id="presta" class="submenu">
                                <li><a>Inscription</a> </li>
                                <li><a class="{{ Route::is('list_presta') ? 'active_a' : '' }}" href="{{route('list_presta')}}">Liste des prestataires</a> </li>
                            </ul>
                        </li>
                        
                        <li>
                            <button class="my-custom-btn" data-toggle="collapse" data-target="#donateur" aria-expanded="false" aria-controls="donateur">
                                Donateur
                            </button>
                            <ul id="donateur" class="submenu">
                                <li><a>Inscription</a> </li>
                                <li><a class="{{ Route::is('list_donateur') ? 'active_a' : '' }}" href="{{route('list_donateur')}}">Liste des donateurs</a> </li>
                            </ul>
                        </li>

                        <li>
                            <!-- <a class="menuitem">Commande</a> -->
                            <button class="my-custom-btn" data-toggle="collapse" data-target="#cmd" aria-expanded="false" aria-controls="cmd">
                                Commande
                            </button>
                            <ul id="cmd" class="submenu">
                                <li><a class="{{ Route::is('list_cmd_attente_regl') ? 'active_a' : '' }}" href="{{route('list_cmd_attente_regl')}}"> Commandes en attente de reglement</a> </li>
                                <li><a class="{{ Route::is('list_cmd') ? 'active_a' : '' }}" href="{{route('list_cmd')}}">Liste des commandes</a> </li>
                            </ul>
                        </li>

                        <li>
                            <!-- <a class="menuitem">Bons</a> -->
                            <button class="my-custom-btn" data-toggle="collapse" data-target="#bon" aria-expanded="false" aria-controls="bon">
                                Bons
                            </button>
                            <ul id="bon" class="submenu">
                                <li><a class="{{ Route::is('list_bon_non_attribue') ? 'active_a' : '' }}" href="{{route('list_bon_non_attribue')}}">Attribution des bons</a> </li>
                                <li><a class="{{ Route::is('list_bon_attribue') ? 'active_a' : '' }}" href="{{route('list_bon_attribue')}}">Liste des bons attribués</a> </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>