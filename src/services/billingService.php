<?php 

require_once "../src/models/productModel.php";
require_once "../src/models/billingModel.php";
require_once "../src/models/billing_DetailModel.php";
require_once "../src/models/shipping_methodModel.php";
require_once "../src/models/payment_methodModel.php";
require_once "../src/models/customerModel.php";
require_once "../src/models/productModel.php";
require_once "../src/models/statisticalModel.php";

    class BillingService {
        
        private $productModel;
        private $billingModel;
        private $billing_detailModel;
        private $shipping_methodModel;
        private $payment_methodModel;
        private $customerModel;
        private $statisticalModel;

        public function __construct()
        {
            $this->productModel = new ProductModel();
            $this->billingModel = new BillingModel();
            $this->billing_detailModel = new Billing_detailModel();
            $this->shipping_methodModel = new Shipping_methodModel();
            $this->payment_methodModel = new Payment_methodModel();
            $this->customerModel = new CustomerModel();
            $this->statisticalModel = new StatisticalModel();
        }

        public function GetAllBills(){
            
            $list= $this->billingModel->GetAll();
            if($list){
                $list_Bill = array();
                foreach($list as $bill){
                    if($bill->Shipping_Method !=null ){ // get detail ship method of bill

                        $shipping = $this->shipping_methodModel->GetSingle($bill->Shipping_Method);
                        $name_ship=$shipping[0]->Name;
                    }
    
                    if($bill->Payment_Method !=null ){ // get detail payment method of bill
                        $payment = $this->payment_methodModel->GetSingle($bill->Payment_Method);
                        $name_payment=$payment[0]->Name;
                    }
                    $sum= [
                        'Id'                => $bill->Id,
                        'Shipping Method'   => $name_ship,
                        'Payment Method'    => $name_payment,
                        'Total'             => $bill->Total,
                        'Date'              => $bill->Date,
                        'Status'            => $bill->Status,
                        'Email'             =>$bill->Email,
                    ];
                    array_push($list_Bill,$sum);
                }
                return $list_Bill;
            }
            else{
                return (
                    array('message'=>'not found')
                );
            }
        }

        public function GetSingleBill($id_billing){
            $payment="";
            $name_payment="";
            $shipping="";
            $name_ship="";
            $cost_ship;
            $name_customer="";
            $phone_customer="";
            $address_customer="";
            $district="";
            $city="";
            $total=0;
            $bill =  $this->billingModel->GetSingle($id_billing);
            if($bill){

                // $bill =  $this->billingModel->GetSingle($id_billing);

                //get detail bill
                if($bill[0]->Email != null){ // get detail customer
                    $customer = $this->customerModel->GetSingle($bill[0]->Email);
                    $name_customer=$customer[0]->Name;
                    $phone_customer=$customer[0]->Phone;
                    $address_customer= $customer[0]->Address;
                    $city = $customer[0]->City;
                    $district = $customer[0]->District;
                }

                if($bill[0]->Shipping_Method !=null ){ // get detail ship method of bill

                    $shipping = $this->shipping_methodModel->GetSingle($bill[0]->Shipping_Method);
                    $cost_ship=$shipping[0]->Cost;
                    $name_ship=$shipping[0]->Name;
                    $total += (int)$cost_ship;
                }

                if($bill[0]->Payment_Method !=null ){ // get detail payment method of bill
                    $payment = $this->payment_methodModel->GetSingle($bill[0]->Payment_Method);
                    $name_payment=$payment[0]->Name;
                }
                // get Bill's detail
                $bill_detail = $this->billing_detailModel->Getsingle($bill[0]->Id);
                if($bill_detail){
                    $detail=array();
                    for($i=0;$i<count($bill_detail);$i++){
                        if($bill_detail[0]==null) break;
                        // get product name
                        $product = $this->productModel->Getsingle($bill_detail[$i]->Id_Product);
                        if($product){
                            $name = $product[0]->Name;
                            $count= $bill_detail[$i]->Count;
                            $price = $bill_detail[$i]->Price_Buy;
                            $total += ((int)$count*(int)$price);
                        }
                        $sub_detail =[
                            'Id Product' =>$product[0]->Id,
                            'Name' =>$name,
                            'Count' =>$count,
                            'Price Buy' =>$price
                        ];
                        
                        array_push($detail,$sub_detail);

                    }
                }
                
                //
                $sum= [
                    'Id'                => $bill[0]->Id,
                    'Shipping Method'   => $name_ship,
                    'Shipping Cost'     =>$cost_ship,
                    'Payment Method'    => $name_payment,
                    'Total'             => $total,
                    'Date'              => $bill[0]->Date,
                    'Status'            => $bill[0]->Status,
                    'Customer Name'     => $name_customer,
                    'Email'             =>$bill[0]->Email,
                    'Phone'             =>$phone_customer,
                    'City'              =>$city,
                    'District'          =>$district,
                    'Customer Address'  =>$address_customer,
                    'Detail'            =>$detail
                ];
                return $sum;
            }
            else{
                return (
                    array('message'=>'not found')
                );
            }

        }

        public function ChangeStatusBill($id_billing,$status){
            $bill = $this->billingModel->GetSingle($id_billing);
            $flag= false;
            if($bill){
                $current_status = $bill[0]->Status;

                if($status == "Cancel"){
                    $flag= true;
                }
                else if($status == "Shipping"){
                    if($current_status =="New" || $current_status == "Set Up"){
                        $flag = true;
                    }
                }
                else if($status == "Done"){
                    
                    if($current_status =="Shipping"){
                        $flag= true;
                    }
                }

                if($flag){
                    if($status =="Done"){
                        $This_bill = $this->GetSingleBill($id_billing)['Detail'];
                        for($i = 0 ;$i < count($This_bill) ;$i++){
                            $this->statisticalModel->UpdatePurchase($This_bill[$i]['Id Product'],$This_bill[$i]['Count']);
                        }
                    }
                    return $this->billingModel->ChangeStatus($id_billing,$status);
                }
                else{
                    return (
                        array('message'=>'Change status fail')
                    );
                }
            }
        }
        // admin tao bill moi
        public function SetupNewBill($email,$name,$phone,$city,$district,$address,$payment_method,$shipping_method,$case){
            
            $customer = $this->customerModel->GetSingle($email);
            if($customer){
                if($case == 1){
                    return $this->billingModel->Add($email,$payment_method,$shipping_method,0,'Set Up');
                }
                else{
                    return $this->billingModel->Add($email,$payment_method,$shipping_method,0,'New');
                }
            }
            else{
                if($this->customerModel->Add($email,$name,$phone,$city,$district,$address) ){ // ad khách hàng thành công
                    if($case == 1){
                        return $this->billingModel->Add($email,$payment_method,$shipping_method,0,'Set Up');
                    }
                    else{
                        return $this->billingModel->Add($email,$payment_method,$shipping_method,0,'New');
                    }
                }
                else {
                    return (
                        array('message'=>'Add fail')
                    );
                }
            }

        }
        public function InsertBillDetail($id_billing,$id_product,$count){
            $product = $this->productModel->GetSingle($id_product);
            if($product){
                $price_buy = $product[0]->Sale_Price;
                if($this->billing_detailModel->Add($id_billing,$id_product,$count,$price_buy)){
                    return $this ->UpdateTotalBill($id_billing);
                }
            }
            else{
                return (
                    array('message'=>'Add fail')
                );
            }
        }
        public function DeleteProductInBill($id_billing,$id_product){
            $this->billing_detailModel->Delete($id_billing,$id_product);
            return $this ->UpdateTotalBill($id_billing);
        }

        public function UpdateTotalBill($id_billing){
            $total = $this->GetSingleBill($id_billing)['Total'];
            return $this->billingModel->Updatetotal($id_billing,$total);
        }

        // customer tao bill
        public function InsertNewBill($email,$name,$phone,$city,$district,$address,$payment_method,$shipping_method,$array_products){
            if($this->SetupNewBill($email,$name,$phone,$city,$district,$address,$payment_method,$shipping_method,2)){
                $id = $this->billingModel->GetLastId();
                for($i=0;$i<count($array_products);$i++){
                    echo $array_products[$i]['id_product'].' '.$array_products[$i]['count'];
                    $this->InsertBillDetail($id[0]->id, $array_products[$i]['id_product'], $array_products[$i]['count']);
                }
                return (
                    array('message'=>'SUCCESS')
                );    
            }
            else{
                return (
                    array('message'=>'ERROR')
                );    
            }
            
        }

        // public function UpdateBrand($id, $name, $logo, $description){
        //     return $this->brandModel->Update($id, $name, $logo, $description);
        // }

    }

?>