<?php
/**
 * Created by Enzo.
 * User: g13003750
 * Date: 21/12/15
 * Time: 21:03
 */

include_once('ModelPDO.php');
include_once('src/class/User.php');

class DefaultModel extends ModelPDO {

	private $user;

    public function __construct() {
        parent::__construct();
        $this->user = build_user (1);
        $this->user->initializeFriends();
        $this->user->initializeCategories();
        $this->user->initializeFlux();
        $this->user->initializeArticles();
    }

    public function getCurrentUser() {
        return $this->user;
    }

	public function getOption() {

	}

	public function getSpecific() {
		
	}
}