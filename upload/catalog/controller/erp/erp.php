<?php
/**
 * Created by PhpStorm.
 * User: xiaohui
 * Date: 2018/8/9
 * Time: 11:02
 */

class ControllerErpErp extends Controller
{
    /**
     *  获取未被抓取的sku
     */
    public function sku()
    {
        $this->load->model('erp/erp');

        $data = $this->model_erp_erp->getSku();
        foreach ($data as $value) {
            $goods[] = [
                'code'        => $value['sku'],
                'name'        => $value['name'],
                'sale_price'  => $value['price'],
                'spec'        => $value['model'],
                "category_id" => 4,
                "unit_id"     => 2,
                "status"      => 0
            ];
        }
        echo json_encode($goods);
    }

    /**
     *  将erp返回sku数据状态改为已抓取
     */
    public function requestsku()
    {
        $data = file_get_contents('php://input');
        $sku  = json_decode($data, true);
        $this->load->model('erp/erp');
        $this->model_erp_erp->requestSku($sku);
    }

    /**
     *  获取未被抓取的订单
     */
    public function order()
    {
		
        $this->load->model('erp/erp');

        $data = $this->model_erp_erp->getOrder();
	
        foreach ($data as $value) {
            $orderDetail = $this->model_erp_erp->getOrderDetail($value['order_id']);
            $detail      = array();
            foreach ($orderDetail as $v) {
                $detail[] = [
                    "products_name"            => $v["name"],
                    "sku_id"                   => $v["sku"],
                    "products_attributes_sale" => $v["model"],
                    "goods_count"              => $v["quantity"],
                    "goods_price"              => $v["price"],
                ];
            }
            $order[] = [
                'orders_id'           => $value['order_id'],
                //'enname'                => ($value['shipping_firstname'] . ' ' . $value['shipping_lastname'])??'',
				'name'                => $value['payment_fullname'] ?? ($value['shipping_firstname'] . ' ' . $value['shipping_lastname']),
                'street_address'      => $value['shipping_address_1'] . ' ' . $value['shipping_address_2'],
                'city'                => $value['shipping_city'],
                'country'             => $value['shipping_country'],
                'state'               => $value['shipping_zone'],
                'postcode'            => $value['shipping_postcode'],
                'telephone'           => $value['telephone'],
                'email'               => $value['email'],
                'currency'            => $value['currency_code'],
                'order_total'         => $value['total'],
                'payment_method'      => $value['payment_method'],
                'payment_module_code' => $value['payment_code'],
                'ip_address'          => $value['ip'],
                'orders_status'       => 'init',
                'time'                => date('Y-m-d H:i:s', time()),
                'detail'              => $detail
            ];
        }
        $json = json_encode($order);
        echo $json;
    }

    /**
     *  将erp返回订单数据状态改为已抓取
     */
    public function requestorder()
    {
        $data = file_get_contents('php://input');
        $order  = json_decode($data, true);
        $this->load->model('erp/erp');
        $this->model_erp_erp->requestOrder($order);
    }
}