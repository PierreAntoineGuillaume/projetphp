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
        //For addArticle
        if(isset($_POST['array1']) && isset($_POST['array2']) && isset($_POST['array3']) && isset($_POST['array4'])) {
            $tab = array();
            array_push($tab,$_POST['array1']);
            array_push($tab,$_POST['array2']);
            array_push($tab,$_POST['array3']);
            array_push($tab,$_POST['array4']);
            $this->model->addArticle($tab);

        }
        else if(isset($_POST['enable'])) {
            $begin = substr($_POST['enable'],0,3);
            $end = substr($_POST['enable'],3);
            if($begin == "del") {
                $this->model->deleteUser($end);
                echo "je passe ici : " . $end;
            }
            else {
                $this->model->enableOrDisable($begin,$end);
            }
        }
        else if(isset($_POST['idUserCategorie']) && isset($_POST['nameCategorie']) && isset($_POST['colorCategorie'])) {
            $tab = array();
            array_push($tab,$_POST['idUserCategorie']);
            array_push($tab,$_POST['nameCategorie']);
            array_push($tab,$_POST['colorCategorie']);
            $this->model->addCategorie($tab);
        }
        else if(isset($_POST['linkImgFavorite']) && isset($_POST['idImg'])) {
            if($_POST['linkImgFavorite'] == "http://aaron-aaron.alwaysdata.net/src/images/favorite_off.png")
                $value = "off";
            else
                $value = "on";
            $this->model->switchFavoriteFlux($value,$_POST['$idImg']);
        }
    }

}