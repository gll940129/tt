<?php
/**
 * Created by PhpStorm.
 * User: xiaohui
 * Date: 2018/8/9
 * Time: 14:41
 */
class ModelErpErp extends Model{

    public function getSku(){
        $query = $this->db->query("SELECT a.model,a.sku,a.price,b.name FROM " . DB_PREFIX . "product a," . DB_PREFIX . "product_description b WHERE a.product_id=b.product_id AND api_status=1 GROUP BY a.product_id");
        $data = $query->rows;
        return $data;
    }

    public function requestSku($sku){
        foreach ($sku as $value){
            $strsku[] = "'".$value."'";
        }
        $whe = implode(',',$strsku);
        $this->db->query("UPDATE " . DB_PREFIX . "product SET api_status=9 WHERE sku IN(".$whe.')');
    }
//order_id,payment_code,payment_method,shipping_address_1,shipping_address_2,shipping_city,shipping_country,shipping_firstname,shipping_lastname,shipping_postcode,telephone,email,shipping_zone,currency_code,ip,total
    public function getOrder(){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order WHERE api_status=1");
        $data = $query->rows;
	
        return $data;
    }

    public function getOrderDetail($id){
        $query = $this->db->query("SELECT a.name,a.model,a.quantity,a.price,b.sku FROM " . DB_PREFIX . "order_product a," . DB_PREFIX . "product b WHERE a.product_id=b.product_id AND order_id=".$id);
        $data = $query->rows;
        return $data;
    }

    public function requestOrder($order){
        $strorder = implode(',',$order);
        $this->db->query("UPDATE " . DB_PREFIX . "order SET api_status=9 WHERE order_id IN(".$strorder.")");
    }

}