<?php 

require_once "../src/models/productModel.php";
require_once "../src/models/product_cateModel.php";
require_once "../src/models/attributeModel.php";
require_once "../src/models/brandModel.php";

    class BrandService {
        
        private $productModel;
        private $categoryModel;
        private $product_cateModel;
        private $attributeModel;
        private $brandModel;

        public function __construct()
        {
            $this->productModel = new ProductModel();
            $this->product_cateModel = new Product_cateModel();
            $this->attributeModel = new AttributeModel();
            $this->brandModel = new BrandModel();
        }

        public function GetAllBrands(){
            return $this->brandModel->GetAll();
        }

    }

?>