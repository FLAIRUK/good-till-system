<?php

namespace FLAIRUK\GoodTillSystem\Models;

use FLAIRUK\GoodTillSystem\API;
use Illuminate\Support\Facades\Config;

class Product extends API {

    /**
     * The url attribute.
     *
     * @var string
     */
    // protected $url = 'https://api.thegoodtill.com/api/products/';

    /**
     * The url attribute.
     *
     * @var array
     */
    protected $payload = [];

    /**
     * The url attribute.
     *
     * @var
     */
    protected $url;

    /**
     * The url attribute.
     *
     * @var
     */
    protected $id;

    /**
     * The url attribute.
     *
     * @var
     */
    protected $outlet_id;

    /**
     * The url attribute.
     *
     * @var 
     */
    protected $product_name;


    const PRODUCTS = 'products';

    /**
     * Create a new Good Till Product instance.
     *
     * @param array $user
     * @return void
     */
    public function __construct(array $user) {
        parent::__construct($user, $this->url);
    }

    public function setURL($url = null): void {
        $this->url = $url ? $url : Config::get('goodtill.routes.api') . self::PRODUCTS;
    }

    
    /**
     * Product Set Outlet ID
     * 
     * @param $id
     * @return object
     */
    public function setID($id): object {
        $this->id = $id;
        return $this;
    }

    /**
     * Product Set Outlet ID
     * 
     * @param $id
     * @return object
     */
    public function setOutlet($id): object {
        $this->outlet_id = ['outlet_id' => $id];
        return $this;
    }

   /**
     * Product Set Outlet ID
     * 
     * @param $id
     * @return object
     */
    public function setName($name): object {
        $this->product_name = ['product_name' => $name];
        // $this->product_name = array_merge($this->payload, ['product_name' => $name ]);
        return $this;
    }

       /**
     * Product Set Outlet ID
     * 
     * @return array
     */
    public function summary($date_range = null): array {
        $this->url = $this->url ? $this->url . 'summary' : Config::get('goodtill.routes.api') . 'products/summary';
        return $this->create(['daterange' => $date_range]);
    }
    
    // public function payload() {
        
    //     dump(get_class_vars (Product::Class)); die;
    //     return array_merge();
    // }

}


// {
//     "id": "3ab9d0b6-6c00-4653-a1f1-926bbdb33727",
//     "outlet_id": "aad28387-468f-426c-b0c9-6491f331f12b",
//     "category_id": "906bb40e-c202-41e6-a29d-b1bc6bd4cb2d",
//     "brand_id": "7d75e12a-2a1c-41ac-861c-9a7e1fbadeaa",
//     "supplier_id": "6cebed0e-a7ac-46b6-8d8d-c53012e128e9",
//     "vat_code_id": "5cb3c470-3f61-4e45-bffa-1a13a54e27db",
//     "parent_product_id": null,
//     "product_name": "BERLINER PILSNER",
//     "product_sku": "BS",
//     "product_desc": "BERLINER PILSNER Description",
//     "display_name": "BERLINER PILSNER",
//     "barcode": "123456789",
//     "weight": 1300,
//     "is_loose": 0,
//     "is_open_price_product": 0,
//     "label_as_main": 0,
//     "purchase_price": "2.500",
//     "supplier_purchase_price": "2.500",
//     "supplier_unit": null,
//     "store_to_supplier_unit_conversion": "1.000",
//     "selling_price": "4.750",
//     "track_inventory": 1,
//     "inventory": "0.000",
//     "min_stock": "10.000",
//     "has_ingredient_stock": 0,
//     "take_stock_from_parent": 0,
//     "stock_quantifier": "0.000",
//     "current_variants": [
//         {
//             "id": "1bda37bc-24c0-46d0-bb03-707de6142484",
//             "product_name": "BERLINER PILSNER Pint",
//             "product_sku": "BSPINT",
//             "display_name": "BERLINER PILSNER Pint",
//             "selling_price": "2.500",
//             "child_attributes": {
//                 "Size": "Pint"
//             },
//             "take_stock_from_parent": 0,
//             "stock_quantifier": "1.000",
//             "inventory": "0.000"
//         },
//         {
//             "id": "cd1b0151-aaaa-4e84-bb9d-8bc45c2d8f28",
//             "product_name": "BERLINER PILSNER Half",
//             "product_sku": "BSHALF",
//             "display_name": "BERLINER PILSNER Half",
//             "selling_price": "1.260",
//             "child_attributes": {
//                 "Size": "Half"
//             },
//             "take_stock_from_parent": 0,
//             "stock_quantifier": "1.000",
//             "inventory": "0.000"
//         }
//     ],
//     "alert_on": 1,
//     "alert_below": "20.000",
//     "delivery_reorder_on": 1,
//     "delivery_reorder_point": "20.000",
//     "delivery_code": "BSDEL",
//     "has_variant": 1,
//     "active": 1,
//     "shareable": 1,
//     "print_on_receipt": 1,
//     "print_on_kitchen": 0,
//     "print_on_drink": 1,
//     "print_on_other": 0,
//     "lock_price": 1,
//     "display_colour": "2299ee",
//     "account_code": null,
//     "display_order": 0,
//     "tags": [
//         "tag 2",
//         "tag 3"
//     ],
//     "has_modifier": 0,
//     "ticket_printer_1": 0,
//     "ticket_printer_2": 0,
//     "ticket_printer_3": 0,
//     "ticket_printer_4": 0,
//     "has_attributes": 1,
//     "display_attr_pos": 1,
//     "attributes": [
//         "Size"
//     ],
//     "variants": [
//         {
//             "product_name": "BERLINER PILSNER Shot",
//             "product_sku": "BSSHOT",
//             "display_name": "BERLINER PILSNER",
//             "selling_price": "1",
//             "child_attributes": {
//                 "Size": "Shot"
//             },
//             "take_stock_from_parent": 0,
//             "stock_quantifier": 1,
//             "inventory": 0
//         }
//     ],
//     "modifier_sets": [
//         {
//             "id": "aa7a07fa-db81-4eb3-b450-ea102c799f3f"
//         },
//         {
//             "id": "1fd5e9cb-1fda-4982-bf26-e15163e6e1a2"
//         }
//     ],
//     "child_attributes": {
//         "Size": "Half"
//     }
// }