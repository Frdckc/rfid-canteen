<?php  include_once('../connect.php'); 



if(!empty($_GET['menuid'])){
           $pid = $_GET['menuid']; 
           $qty= $_GET['qty']; 
           $amt= $_GET['amt']; 
            $wasFound = false;
            $i = 0; 
            $_SESSION['daybal'];
            
            $day=$_SESSION['totalbills']+($amt*$qty);
            if( $_SESSION['daybal']<$day){
                $_SESSION['flash'] = ['type'=>'warning','msg'=>'Daily limit reached.'];
                header("Location: ../cart.php#cart-section");
                exit();
            }


            $result = $db->prepare("SELECT qty FROM tbl_menu WHERE id='$pid'");
            $result->execute(); 
            if($row = $result->fetch()) {
              if ($row['qty']=='0'){
                $_SESSION['flash'] = ['type'=>'danger','msg'=>'Not available.'];
                header("Location: ../cart.php#cart-section");
                exit();
              } else { 
               $menuQTY=$row['qty']-$qty; 

              $q = $db->prepare("UPDATE tbl_menu SET  qty=?  WHERE id=?"); 
              $q->execute([$menuQTY, $pid]);
             }    } 
             if(!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1){
              $_SESSION["cart_array"]=array(0=>array("menuID"=>$pid,"quantity"=>$qty)); 
            }else{
              foreach($_SESSION["cart_array"] as $each_menu){
                $i++;
                foreach($each_menu as $key => $value){
                    if($key == "menuID" && $value == $pid){  
                        array_splice($_SESSION["cart_array"], $i - 1, 1, array(array(
                            "menuID" => $pid,
                            "quantity" => $each_menu['quantity'] + $qty
                        )));
                        $wasFound = true;
                        break; // optional, to exit inner loop early
                    }
                }    
            }
              if($wasFound==false){
                array_push($_SESSION["cart_array"],array("menuID"=>$pid,"quantity"=>$qty));
              }
            } 

          }
    $_SESSION['flash'] = ['type'=>'success','msg'=>'Order successfully submitted.'];
    header("Location: ../cart.php#cart-section");
    exit();
 
 ?>