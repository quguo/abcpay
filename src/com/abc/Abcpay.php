<?php
namespace com\abc;
/*
$orders=array(
  'id'=>'',
  'total_fee'=>'',
  'date'=>'',
  'time'=>'',
)
*/
require_once ('ebusclient/PaymentRequest.php');
class Abcpay{
    public $orders;
    function __construct($orders){
      $this->orders=$orders;
    }
    public function pay(){
      $tRequest = new \PaymentRequest();
      $tRequest->order["PayTypeID"] = 'ImmediatePay'; //设定交易类型
      $tRequest->order["OrderNo"] = $this->orders['id']; //设定订单编号
      //$tRequest->order["ExpiredDate"] = ($_POST['ExpiredDate']); //设定订单保存时间
      $tRequest->order["OrderAmount"] = $this->orders['total_fee']; //设定交易金额
      //$tRequest->order["Fee"] = ($_POST['Fee']); //设定手续费金额
      $tRequest->order["CurrencyCode"] ='156'; //设定交易币种
      //$tRequest->order["ReceiverAddress"] = ($_POST['ReceiverAddress']); //收货地址
      $tRequest->order["InstallmentMark"] = '0'; //分期标识

      //$tRequest->order["BuyIP"] = ($_POST['BuyIP']); //IP
      //$tRequest->order["OrderDesc"] = ($_POST['OrderDesc']); //设定订单说明
      //$tRequest->order["OrderURL"] = ($_POST['OrderURL']); //设定订单地址
      $tRequest->order["OrderDate"] = $this->orders['date']; //设定订单日期 （必要信息 - YYYY/MM/DD）
      $tRequest->order["OrderTime"] = $this->orders['time']; //设定订单时间 （必要信息 - HH:MM:SS）
      //$tRequest->order["orderTimeoutDate"] = ($_POST['orderTimeoutDate']); //设定订单有效期
      $tRequest->order["CommodityType"] = '0202'; //设置商品种类

      //2、订单明细
      
      $orderitem = array ();
      $orderitem["ProductName"] =$this->orders['body']; //商品名称
      $orderitem["UnitPrice"] = $this->orders['total_fee']; //商品总价
      $orderitem["Qty"] = "1"; //商品数量
      $tRequest->orderitems[0] = $orderitem;
      
      //3、生成支付请求对象
      $tRequest->request["PaymentType"] ='A'; //设定支付类型
      $tRequest->request["PaymentLinkType"] = '1'; //设定支付接入方式

      //$tRequest->request["ReceiveAccount"] = ($_POST['ReceiveAccount']); //设定收款方账号
      //$tRequest->request["ReceiveAccName"] = ($_POST['ReceiveAccName']); //设定收款方户名
      $tRequest->request["NotifyType"] ='1'; //设定通知方式
      $tRequest->request["ResultNotifyURL"] ='http://m.hltou.com'; //设定通知URL地址
      $tRequest->request["MerchantRemarks"] =$this->orders['body']; //设定附言
      $tRequest->request["IsBreakAccount"] = '0'; //设定交易是否分账

      $data=array();
      $tResponse = $tRequest->postRequest();
      if ($tResponse->isSuccess()) {
          $PaymentURL = $tResponse->GetValue("PaymentURL");
          $data['code']=1;
          $data['msg']=$PaymentURL;
      }else{
          $data['code']=0;
      }
      return $data;
    }

}
?>
