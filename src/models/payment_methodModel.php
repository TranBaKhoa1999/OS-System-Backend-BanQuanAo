<?php 

require_once "../src/config/db.php";

    class Payment_methodModel extends DBConnection {
        
        public function GetAll(){
            $sql = "SELECT * FROM payment_method";
            $data = $this->runQuery($sql);
            $data->execute();
            if($data->rowcount() > 0){
                $brand = $data->fetchAll(PDO::FETCH_OBJ);
                return ($brand);
            }
            else{
                return (
                    array('message'=>'not found')
                );
            }
        }

        public function GetSingle($id){
            $sql = "SELECT * FROM payment_method WHERE id = $id";
            $data = $this->runQuery($sql);
            $data->execute();
            if($data->rowcount() > 0){
                $brand = $data->fetchAll(PDO::FETCH_OBJ);
                return $brand;
            }
            else{
                return (
                    array('message'=>'not found')
                );
            }
        }

        public function Add($name,$cost,$status){
            $sql = "INSERT INTO payment_method(name,status) VALUE(:name,:status)";

            $data = $this->runQuery($sql);
            $data->bindParam(':name',$name);
            $data->bindParam(':status',$status);

            if($data->execute()){
                return (
                    array('message'=>'add success!')
                );
            }
            else{
                return (
                    array('message'=>'add fail!')
                );
            }
        }
        
        public function Update($id,$name,$status){
            $sql = "UPDATE payment_method SET name=:name,status=:status WHERE id = $id";
            $data = $this->runQuery($sql);

            $data->bindParam(':name',$name);

            if($data->execute()){
                return (
                    array('message'=>'update success!')
                );
            }
            else{
                return (
                    array('message'=>'update fail!')
                );
            }
        }
        public function UpdateStatus($id,$status){
            $sql ="UPDATE payment_method SET status ='$status' WHERE id=$id";
            $data = $this->runQuery($sql);
            if($data->execute()){
                return (
                    array('message'=>'update success!')
                );
            }
            else{
                return (
                    array('message'=>'update fail!')
                );
            }
        }
        public function GetAllBillRelative($id){
            $sql ="SELECT * FROM `billing` WHERE payment_method = $id AND Status ='Done'";
            $data = $this->runQuery($sql);
            $data->execute();
            if($data->rowcount() > 0){
                $brand = $data->fetchAll(PDO::FETCH_OBJ);
                return ($brand);
            }
            else{
                return false;
            }
        }

    }

?>