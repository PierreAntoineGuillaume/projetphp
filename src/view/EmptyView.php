<?php
/**
 * Created by PhpStorm.
 * User: g13003750
 * Date: 08/01/16
 * Time: 16:35
 */

class EmptyView extends View {

    public function __construct($model) {
        $this->model = $model;
    }// UserView

    public function display() {
        if(
            isset($_POST['array1'])
            && isset($_POST['array2'])
            && isset($_POST['array3'])
            && isset($_POST['array4'])
        ) {
            $tab = array();
            array_push($tab,$_POST['array1']);
            array_push($tab,$_POST['array2']);
            array_push($tab,$_POST['array3']);
            array_push($tab,$_POST['array4']);
            $this->model->addArticle($tab);

        }
    }

}