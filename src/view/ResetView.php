<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 22/01/2016
 * Time: 09:30
 */

include_once('View.php');

class ResetView extends View {

    public function __construct($model) {
        parent::__construct($model);
    }// UserView

    public function display() {
        echo '
			<html>
				<head>
					<title>Aaron</title>
					<link rel="stylesheet" type="text/css" href="src/style/reset.css" />
				</head>
				<body>
					<img class="logo" src="src/images/aaron_logo_1.png">
        			<div id="formRegister" class="form">
        			    <form action="register" method="post">
        			    	<input class="bigInput" name="mail" type="email" placeholder="Adresse Mail"/><br/>
        			    	<input id="submitIndex" name="action" type="submit" value="Valider" />
        			    </form>
        			</div>
				</body>
			</html>
    	';
    }

}