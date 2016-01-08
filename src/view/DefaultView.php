<?php
/**
 * Created by Kevin.
 * User: h13002021
 * Date: 21/12/15
 * Time: 22:01
 */

include_once('View.php');
include_once('src/util/regex.php');

class DefaultView extends View {

	private $categories;
	private $friends;

    public function __construct($model) {
        $this->model = $model;

        $this->categories = $this->model->getCategories();
        $this->friends = $this->model->getFriends();
    }// UserView

    public function display() {
    	echo '
    		<html>
				<head>
					<title>Aaron</title>
					<link rel="stylesheet" type="text/css" href="src/style/user.css" />
					<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
					<script type="text/javascript" src="src/js/popup.js" ></script>
					<script type="text/javascript" src="src/js/aside.js"></script>
					<script type="text/javascript" src="src/js/user_preference.js"></script>
					<script type="text/javascript" src="src/js/switch_flux.js"></script>
					<script type="text/javascript" src="src/js/search.js"></script>
				</head>
				<body>
					<!-- TOP SIDE -->
					<div id="top">
						<img class="logo" src="src/images/aaron_logo.png">
						<div id="TUser"><div id="TUserLogo"><button id="btnUser" class="imageButton" type="button"><img src="src/images/account.png"></button></div><div id="TUserName"><strong>'.$this->model->getName().'</strong></div></div>
						<a href="#" onclick="javascript:;" class="preference_btn"></a>
					</div>

					<!-- LEFT SIDE -->
					<div id="menu">
			        	<div id="LTBar">
							<input id="searchInput" name="search" type="search" placeholder="Recherche"/>
							<a href="#" onclick="javascript:;" class="imageButton search_btn"></a>
						</div>
						<div id="categorie_panel" class="searchOn">';
							foreach ($this->categories as $c) {
							echo '
								<button class="categorie default_block_panel" type="button" style="background-color:'.$c->getColor().';" value="'.$c->getName().'">'.$c->getName().'</button>
							';
							}
						echo '
						</div>
						<div id="friend_panel" class="searchOn hide"> ';
							foreach ($this->friends as $f) {
							echo '
								<button class="friend default_block_panel" value="'.$f->getName().'" type="button">'.$f->getName().'</button>
							';
							}
						echo '
						</div>';
						foreach ($this->categories as $c) {
						echo '<div id="'.$c->getName().'_panel" class="flux_Panel searchOn hide">
							  		<button class="default_block_panel backflux_btn"><span class="flux_name">Retour</span></button>';
							foreach($c->getFlux() as $in) {
								($in->isFavorite() == true) ? $et = 'on' : $et = 'off';
                                $rgb = hex2rgb($c->getColor());
							  echo '<button class="default_block_panel flux" value="'.$in->getName().'" type="button" style="background-color:rgba('.$rgb['red'].','.$rgb['green'].','.$rgb['blue'].',0.5);"><span class="flux_name">'.$in->getName().'</span><img class="flux_with_image" src="src/images/favorite_'.$et.'.png"></button>';
							}
						echo '</div>';
						}
						echo '
						<div id="favorite_panel" class="searchOn hide">';
							foreach($this->categories as $c) {
								foreach($c->getFlux() as $in) {
									if ($in->isFavorite() == false) continue;
									echo '<button class="default_block_panel flux" type="button" value="'.$in->getName().'">'.$in->getName().'</button>';
								}
							}

						echo '</div>
						<div id="LBBar">
							<a href="#" onclick="javascript:;" class="addF_btn"></a><a href="#" onclick="javascript:;" class="lessF_btn"></a>			
						</div>
			    	</div>

			    	<!-- PAGE CONTENT -->
			    	<div id="page">
			    		<div id="leftSmallMenu">
			    			<a href="#" onclick="javascript:;" class="close_btn"></a>
			    			<a href="#" onclick="javascript:;" class="open_btn"></a>
			    			<a href="#" onclick="javascript:;" class="all_btn"></a>
			    			<a href="#" onclick="javascript:;" class="favorite_btn"></a>
			    			<a href="#" onclick="javascript:;" class="friend_btn"></a>
			    		</div>

			    		<div id="content">
			    			prout
			    		</div>
			    	</div>
			    	<!-- END PAGE CONTENT -->

			    	<!-- PREFERENCE -->
			    	<div id="userPreference" class="hide">
			    	    <div id="userPreference_top">
                            <a href="#" onclick="javascript:;" class="pref_close_btn"></a>
                        </div>
                        <div id="userPreference_rest">
                            <button class="pref_option_btn" type="button">Options</button>
                            <button class="pref_deconnection_btn" type="button">Déconnexion</button>
                        </div>
			    	</div>

			    	<div id="userInformation" class="hide">
			    		<div id="userInformation_top">
                            <img class="userInformation_top_img" src="src/images/account.png"><span class="userInformation_top_name">'.$this->model->getName().'</span>
                        </div>
			    		<div id="userInformation_rest">

                        </div>
			    	</div>
			    	<!-- END PREFERENCE -->
			    	
					<!-- POP-UP -->
					<div id="overlay"></div>
			        <div id="popup" class="popup">
			        	<div class="addLibrary">
			        		<form id="F_library" action="" method="">
								<input class="smallInput" name="name" type="text" placeholder="Nom" required/>
								<input class="smallInput" name="color" type="color" placeholder="Couleur" required/>
					    		<input class="smallInput" name="submit" type="submit" value="Créer"/><button id="btnCancel" class="smallInput" type="reset" form="F_library">Annuler</button>
							</form>
			        	</div>
			        	<div class="sep"></div>
			            <div class="addFlux">
							<form id="F_flux" action="" method="">
								<input class="smallInput" name="name" type="text" placeholder="Nom" required/>
								<input class="smallInput" name="color" type="text" placeholder="Couleur" required/>
					    		<input class="bigInput" name="flux" type="text" placeholder="Url du flux" required/> <br/>
					    		<input class="bigInput" name="image" type="text" placeholder="Url de l\'image" /> <br/>
					    		<input class="smallInput" name="submit" type="submit" value="Ajouter"/><button id="btnCancel" class="smallInput" type="reset" form="F_flux">Annuler</button>
							</form>
						</div>
			        </div>
			        <script type="text/javascript">
			        $(function() {
				        var overlay = true;
				        $(".addF_btn").click(function () {
				            $("#overlay").css({"display":"block", opacity:0});
				            $("#overlay").fadeTo(200,0.5);
				            $("#popup").fadeTo(200,1);       
				            overlay = !overlay;   
				        }); 

				        $("#btnCancel").click(function () {
				            $("#overlay").fadeOut(200);
				            $(".popup").css("display", "none"); 
				            overlay = !overlay;
				        });
				    });
			        </script>
			        <!-- END POP-UP -->

				</body>
			</html>


    	';
    }
}