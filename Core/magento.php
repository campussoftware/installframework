<?php 
include 'CurlCall.php';

echo "<prE>";
// orders  search 
$url="http://localhost/mginstance/202/index.php/rest/V1/products";


       $data ='{
  "product": {
    "sku": "MY_SKU",
    "name": "My Product",
    "attributeSetId": "4",
    "price": 20,
    "status": 1,
    "visibility": 4,
    "typeId": "virtual",
    "weight": 0,
    "extensionAttributes": {
      "stockItem": {
        "stockId": 1,
        "qty": 200,
        "isInStock": true,
        "isQtyDecimal": false,
        "useConfigMinQty": true,
        "minQty": 0,
        "useConfigMinSaleQty": 0,
        "minSaleQty": 0,
        "useConfigMaxSaleQty": true,
        "maxSaleQty": 0,
        "useConfigBackorders": false,
        "backorders": 0,
        "useConfigNotifyStockQty": true,
        "notifyStockQty": 20,
        "useConfigQtyIncrements": false,
        "qtyIncrements": 0,
        "useConfigEnableQtyInc": false,
        "enableQtyIncrements": false,
        "useConfigManageStock": true,
        "manageStock": true,
        "lowStockDate": "string",
        "isDecimalDivided": true,
        "stockStatusChangedAuto": 0,
        "extensionAttributes": {}
      }
    },
    "options": [],
    "tierPrices": [{
		"customerGroupId":1,
		"qty":2,
		"value":19.2
	},
	{
		"customerGroupId":1,
		"qty":3,
		"value":19.2
	},
	{
		"customerGroupId":1,
		"qty":4,
		"value":19.2
	},
	{
		"customerGroupId":1,
		"qty":5,
		"value":19.2
	},
	{
		"customerGroupId":1,
		"qty":6,
		"value":19.2
	}],
    "customAttributes": [
	{
		"attributeCode":"special_price",
		"value":10
	},
	{
		"attributeCode":"category_ids",
		"value":"2,4"
	}
    ]
  },
  "saveOptions": true
}';

	   
            $curl=new Core_CurlCall();
            $curl->setUrl($url);		
            $curl->setPostJsonData(($data));
			$curl->setheaders(array('Accept:application/json'));
			$curl->setheaders(array('SOAPAction: "catalogProductRepositoryV1Save"'));
            $curl->setheaders(array('Content-type:application/json'));
            $curl->setheaders(array('Authorization:Bearer 1sunrxhg0sv9apeqyl0ddsiprnsdiw7v'));
			$curl->setheaders(array("Content-Lenght: " . strlen(($data))));
            $curl->setReturnTransfer(true);
            $curl->callCurl();

print_r($curl);
echo "ramesh";
?>