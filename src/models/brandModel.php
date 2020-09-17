<?php 

require_once "../src/config/db.php";

    class BrandModel extends DBConnection {
        
        public function GetAll(){
            $data = $this->runQuery('select * from brand');
            $data->execute();
            if($data->rowcount() > 0){
                $loaiSanPham = $data->fetchAll(PDO::FETCH_OBJ);
                return ($loaiSanPham);
            }
            else{
                return (
                    array('message'=>'not found')
                );
            }
        }
        public function GetSingle($id){
            $sql = "SELECT * FROM brand WHERE id = $id";
            $data = $this->runQuery($sql);
            $data->execute();
            if($data->rowcount() > 0){
                $loaiSanPham = $data->fetchAll(PDO::FETCH_OBJ);
                return $loaiSanPham;
            }
            else{
                return (
                    array('message'=>'not found')
                );
            }
        }
        public function Add($tenbrand,$logo,$description){
            $sql = "INSERT INTO brand(name,logo,description) VALUE(:ten_loai,:logo,:description)";

            $data = $this->runQuery($sql);
            $data->bindParam(':name',$tenbrand);
            $data->bindParam(':logo',$logo);
            $data->bindParam(':description',$description);

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
        
        public function Update($id,$tenloai){
            $sql = "UPDATE loaisanpham SET tenloai =:ten_loai WHERE id = $id";
            $data = $this->runQuery($sql);
            $data->bindParam(':ten_loai',$tenloai);
            $data->execute();
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
        public function Delete($id){
            $sql = "DELETE FROM loaisanpham WHERE id = $id";
            $data = $this->runQuery($sql);
            $data->execute();
            if($data->execute()){
                return(
                    array('message'=>'delete success!')
                );
            }
            else{
                return(
                    array('message'=>'delete fail!')
                );
            }
        }

    }

?>