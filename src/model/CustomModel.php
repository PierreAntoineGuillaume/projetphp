<?php
/**
 * Created by PhpStorm.
 * User: g13003750
 * Date: 08/01/16
 * Time: 14:09
 */


class CustomModel extends ModelPDO {

    public function __construct() {
        parent::__construct();
    } // CustomModel

    //////////////////////////ADMIN/////////////////////////////////
    public function enableOrDisableUser($enableOrDisable,$idUser) {
        if($enableOrDisable === "ena") {
            $sql = "UPDATE USERS SET ENABLE = 0 WHERE ID = " . $idUser;
        }
        else {
            $sql = "UPDATE USERS SET ENABLE = 1 WHERE ID = " . $idUser;
        }
        $this->pdo->query($sql);
    } // enableOrDisableUser() : enable or disable a user of the database

    public function deleteUser($idUser) {
        $sql = 'DELETE FROM USERS WHERE ID='.$idUser;
        $this->pdo->query($sql);
    } // deleteUser() : delete a user of the database
    //////////////////////////ADMIN/////////////////////////////////

    //////////////////////////////////FOR A USER//////////////////////////////////
    public function addArticle($articleToAdd) {
        $sql = "INSERT INTO ARTICLE (IDUSER,TITLE,THEME,URL,CONTENT,POSTED) VALUES (".$_SESSION['ID'].",'" . $articleToAdd[0]."','" . $articleToAdd[1]."','" . $articleToAdd[2] . "', '". $articleToAdd[3] ."','" . date('Y-m-d H:i:s'). "')";
        $this->pdo->query($sql);
    } // addArticle() : add a article for the current user

    public function addCategory($categorieToAdd) {
        if(!isset($categorieToAdd[1]))return;
        $sql = "INSERT INTO CATEGORIE (IDUSER,NAME,COLOR) VALUES ('".$_SESSION['ID']."','" . $categorieToAdd[0]."','" . $categorieToAdd[1] . "')";
        $this->pdo->query($sql);
    } // addCategory() : add a category for the current user

    public function changeFavoriteRSSFeed($value, $idRSSFeed, $idCategory) {
        $sql = "null" ;
        if ($value == "on") {
            $sql = 'UPDATE FLUX_ASSOC SET ISFAVORITE = 1 WHERE IDFLUX = ' . $idRSSFeed . ' AND IDCATE = '.$idCategory;
        }
        else {
            $sql = 'UPDATE FLUX_ASSOC SET ISFAVORITE = 0 WHERE IDFLUX = ' . $idRSSFeed . ' AND IDCATE = '.$idCategory;
        }
        $this->pdo->query($sql);
    } // changeFavoriteRSSFeed() : add or delete the current user list of his rss feed

    public function focusToThisRSSFeed($url) {
        $sql = 'SELECT * FROM FLUX_INFORMATION WHERE IDFLUX IN (SELECT ID FROM FLUX WHERE URL = \''.$url.'\')';
        $stmt = $this->pdo->query($sql);
        $array = array();
        while($result = $stmt->fetch()) {
            $fluxArt = new FluxArticle($result['TITLE'],$result['POSTED'],$result['CONTENT'],$result['URL'],$result['MD5VERSION']);
            array_push($array,$fluxArt->display());
        }
        return json_encode($array);
    } // focusToThisRSSFeed() : focus the current page user to the rss feed

    public function addRSSFeedCategoryUser($nameFluxAdd,$nameCategorieToAdd,$urlFluxAdd) {
        $sql = "SELECT ID FROM CATEGORIE WHERE IDUSER = ".$_SESSION['ID']." AND NAME = \"".$nameCategorieToAdd."\"";
        $resultCate = $this->pdo->query($sql)->fetch();
        $idCate = $resultCate[0];

        $sql = "SELECT COUNT(*) FROM FLUX WHERE URL = \"".$urlFluxAdd."\"";
        $resultVerif = $this->pdo->query($sql)->fetch();
        if($resultVerif[0] == 0) {
            $sql = "INSERT INTO FLUX(URL) VALUES(\"".$urlFluxAdd."\")";
            $this->pdo->query($sql);
            $idFlux = $this->pdo->lastInsertId();

            $sql = "INSERT INTO FLUX_ASSOC(IDCATE,IDFLUX,NAME,ISFAVORITE) VALUES(".$idCate.",".$idFlux.",\"".$nameFluxAdd."\",0)";
            $this->pdo->query($sql);

            $flux = new FluxUser($idFlux,$urlFluxAdd,$nameFluxAdd,0);
            $flux->refresh();
        } else {
            $sql = "SELECT ID FROM FLUX WHERE URL = \"".$urlFluxAdd."\"";
            $resultFlux = $this->pdo->query($sql)->fetch();
            $idFlux = $resultFlux[0];

            $sql = "INSERT INTO FLUX_ASSOC(IDCATE,IDFLUX,NAME,ISFAVORITE) VALUES(".$idCate.",".$idFlux.",\"".$nameFluxAdd."\",0)";
            $this->pdo->query($sql);
        }
    } // addRSSFeedCategoryUser() : Add a rss feed to a category for current user

    public function userToFindAndToDisplay($userToFind) {
        $sql = 'SELECT count(*) FROM FRIEND WHERE IDUSER = '.$_SESSION['ID'].' AND IDFRIEND IN (SELECT ID FROM USERS WHERE NAME = "'.$userToFind.'")';
        $result = $this->pdo->query($sql)->fetch();
        $array = array();
        if ($result[0] == 0) {
            $sql = 'SELECT USERS.ID, NAME, AVATAR FROM USERS, USER_INFORMATION WHERE USERS.ID = USER_INFORMATION.ID AND ENABLE = 1 AND USERS.ID <> ' . $_SESSION['ID'] . ' AND USERS.NAME = "' . $userToFind . '"';
            $stmt = $this->pdo->query($sql);
            while ($result = $stmt->fetch()) {
                array_push($array, $result['ID']);
                array_push($array, $result['NAME']);
                array_push($array, $result['AVATAR']);
            }
            return json_encode($array);
        }
    } // userToFindAndToDisplay() : return an array or users found

    public function userToAddInFriend($idUserToAdd) {
        $sql = 'INSERT INTO FRIEND (IDUSER,IDFRIEND) VALUES('.$_SESSION['ID'].','.$idUserToAdd.')';
        $this->pdo->query($sql);
    } // userToAddInFriend() : add a user in the current user friendlist

    public function catToDelete($idCatDelete) {
        $sql = 'DELETE FROM CATEGORIE WHERE ID = '.$idCatDelete.' AND IDUSER=' . $_SESSION['ID'];
        $this->pdo->query($sql);
        return $sql;
    } // catToDelete() : delete a category of current user

    public function RSSFeedToDeleteOfACategory($idRSSFeed,$idCategory) {
        $sql = 'DELETE FROM FLUX_ASSOC WHERE IDFLUX='.$idRSSFeed.' AND IDCATE='.$idCategory;
        $this->pdo->query($sql);
    } // RSSFeedToDeleteOfACategory() : delete a RSSFeed of a category of current user

    public function deleteOneFriend($idFriend) {
        $sql = 'DELETE FROM FRIEND WHERE IDUSER ='.$_SESSION['ID'].' AND IDFRIEND ='.$idFriend;
        $this->pdo->query($sql);
    } // deleteOneFriend() : delete a friend of current user

    public function addMail($nameE,$passwordE,$serverE,$portE) {
        $sqlInsertEmail = 'INSERT INTO EMAIL (IDUSER,ADDRESS,PASSWORD) VALUES('.$_SESSION['ID'].',"'.$nameE.'","'.$passwordE.'")';
        $this->pdo->query($sqlInsertEmail);
        $id = "";
        $sql = 'SELECT * FROM EMAIL';
        $stmt = $this->pdo->query($sql);
        while ($result = $stmt->fetch()) {
            if($result['ADDRESS'] === $nameE) {
                $id = $result['ID'];
            }
        }
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $sqlInsertEmailConnection = 'INSERT INTO EMAIL_CONNECTION (IDMAIL,SERVER,PORT) VALUES ('.$id.',"'.$serverE.'",'.$portE.')' ;
        $this->pdo->query($sqlInsertEmailConnection);
    }

    public function loadMail($mail) {
        $sql = 'SELECT EMAIL.ID, ADDRESS, PASSWORD, SERVER, PORT FROM EMAIL, EMAIL_CONNECTION WHERE EMAIL.ID = IDMAIL AND IDUSER = '.$_SESSION['ID'] .' AND ADDRESS = "'.$mail.'"';
        $stmt = $this->pdo->query($sql);
        $array = array();
        while ($result = $stmt->fetch()) {
            $mail = new Email($result['ID'],$result['ADDRESS']);
            $mail->connect($result['SERVER'],$result['PORT'],$result['PASSWORD']);
            $mail->initializeMails();
            foreach($mail->getMails() as $value)  {
                array_push($array, $value->display());
            }
        }
        return json_encode($array);
    } // loadMail() : huge function where it load all mails

    public function friendBlog($idFriend) {
        $array = array();
        $sql = 'SELECT * FROM ARTICLE WHERE IDUSER = '.$idFriend;
        $stmt = $this->pdo->query($sql);
        while ($result = $stmt->fetch()) {
            $article = new Article($result['ID'],$result['TITLE'],$result['THEME'],$result['URL'],$result['CONTENT'],$result['POSTED']);
            array_push($array, $article->display());
        }
        return json_encode($array);
    } // friendBlog() : display friend blog
    //////////////////////////////////~FOR A USER//////////////////////////////////

    public function refresh() {

    }

    public function getSpecific () {

    } // getSpecific


}