<?php
/**
 * Created by PhpStorm.
 * User: h13002021
 * Date: 16/12/15
 * Time: 17:01
 */

$base = $_SERVER['DOCUMENT_ROOT']."src/";

include_once($base .'controller/Controller.php');
class ConfirmationController extends Controller {
    public function __construct(Model $model) {
        parent::__construct($model);

    }// ControllerInscription

    public function update() {
      $this->model->get();
    }
}
