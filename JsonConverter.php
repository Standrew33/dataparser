<?php

class JsonConverter
{
    private const DATA_FOLDER = 'spare_parts_feed/*[0-9].json';

    private function getDataFolder($file)
    {
        $json = json_decode(file_get_contents($file), true);
        return $json;
    }

    public function setDataReverse()
    {
        $newArr = [];
        $temp_files = glob(self::DATA_FOLDER);
        foreach($temp_files as $key => $file) {
            $json = self::getDataFolder($file);

            $fvMake = explode(' / ', $json['vehicle']['name']);
            $vMake = $fvMake[0];
            $vModel = !isset($fvMake[1]) ? '-' : $fvMake[1];

            foreach ($json['categories'] as $key2 => $category3) {
                if (!empty($category3['categories'])) {
                    foreach ($category3['categories'] as $category4) {
                        foreach ($category4['spare_parts'] as $spare_part) {
                            $product = $spare_part['product'];
                            $newProduct = [
                                'name' => $product['name'],
                                'product_number' => $product['product_no'],
                                'vat' => $product['vat_percent'],
                                'price_vat' => $product['unit_price_incl_vat'] ?? 0,

                                //Required fields for validation
                                'images' => null, 'visible' => 1, 'plu' => 0, 'heureka_cpc' => null,
                                'google_category_id' => null, 'zbozi_cpc' => null, 'meta_keywords' => null,
                                'free_billing' => 0, 'deposit_code' => null, 'deposit_logic' => null,
                                'description' => null, 'dimensions' => null, 'external_code' => 0,
                                'flags' => null, 'flag_validity' => null, 'gifts' => null, 'glami_category_id' => null,
                                'heureka_category_id' => null, 'information_parameters' => null,
                                'internal_note' => null, 'logistic' => null, 'orig_url' => null, 'oss_tax_rates' => null,
                                'part_number' => 0, 'pricelists' => null, 'purchase_price' => null,
                                'recycling_fee' => null, 'related_files' => null, 'related_products' => null,
                                'related_videos' => null, 'serial_number' => 0, 'set_items' => null,
                                'short_description' => null, 'standard_price' => null,
                                'stock' => null, 'stock_min_supply' => null, 'supplier' => null,
                                'surcharge_parameters' => null, 'text_properties' => null,
                                'unit_of_measure' => null, 'zbozi_category_id' => null, 'zbozi_search_cpc' => null,
                                'visibility' => 'visible', 'availability' => 1, 'availability_in_stock' => 1,
                                'free_shipping' => 0, 'warranty' => 24, 'adult' => 0, 'action_price' => null,
                                'allows_iplatba' => 0, 'allows_payu' => 0, 'allows_pay_online' => 0,
                                'alternative_products' => null, 'appendix' => null, 'apply_discount_coupon' => 0,
                                'apply_loyalty_discount' => 0, 'apply_quantity_discount' => 0,
                                'apply_volume_discount' => 0, 'arukereso_hidden' => 0,
                                'arukereso_marketplace_hidden' => 0, 'atypical_billing' => 0,
                                'atypical_product' => null, 'atypical_shipping' => 0,
                                'availability_out_of_stock' => null, 'code' => '0', 'currency' => 'CZK',
                                'decimal_count' => 0, 'ean' => '1310121350001', 'external_id' => 0, 'firmy_cz' => 0,
                                'heureka_cart_hidden' => 0, 'heureka_hidden' => 0, 'item_type' => 'product',
                                'manufacturer' => 'Text', 'meta_description' => 'description',
                                'min_price_ratio' => 0, 'negative_amount' => 0, 'price' => 0, 'price_ratio' => 0,
                                'seo_title' => 'title', 'toll_free' => 0, 'unit' => 'ks', 'xml_feed_name' => '1',
                                'zbozi_hidden' => 0,

                                'categories' => [/*[
                                    'name' => $category4['name'],
                                    'categories' => [[
                                        'name' => $category3['name'],
                                        'categories' => [[
                                            'modelname' => $vModel,
                                            'categories' => [['makename' => $vMake]]
                                        ]]
                                    ]]]*/
                                    $vMake .' > '. $vModel .' > '. $category3['name'] .' > '. $category4['name']
                                ]
                            ];

                            if (!isset($newArr[$product['product_no']])) {
                                $newArr[$product['product_no']] = $newProduct;
                            } else {
                                $newArr[$product['product_no']]['categories'][] =
                                    $vMake .' > '. $vModel .' > '. $category3['name'] .' > '. $category4['name'];

                                //Logic for multilevel categories, but is not validated for RNG
//                                $cat4 = $newArr[$product['product_no']]['categories'];
//                                $keyCat4 = array_search($category4['name'], array_column($cat4, 'name'));
//                                if ($keyCat4 !== false) {
//                                    $keyCat3 = array_search($category3['name'], array_column($cat4[$keyCat4]['categories'], 'name'));
//                                    if ($keyCat3 !== false) {
//                                        $newArr[$product['product_no']]['categories'][$keyCat4]['categories'][$keyCat3]['categories'][] = [
//                                            'modelname' => $vModel,
//                                            'categories' => [
//                                                'makename' => $vMake,
//                                            ]
//                                        ];
//                                    } else {
//                                        $newArr[$product['product_no']]['categories'][$keyCat4]['categories'][] = [
//                                            'name' => $category3['name'],
//                                            'categories' => [
//                                                'modelname' => $vModel,
//                                                'categories' => [
//                                                    'makename' => $vMake,
//                                                ]
//                                            ]
//                                        ];
//                                    }
//                                } else {
//                                    $newArr[$product['product_no']]['categories'][] = [
//                                        'name' => $category4['name'],
//                                        'categories' => [
//                                            'name' => $category3['name'],
//                                            'categories' => [
//                                                'modelname' => $vModel,
//                                                'categories' => [
//                                                    'makename' => $vMake,
//                                                ]
//                                            ]
//                                        ]
//                                    ];
//                                }
                            }
                        }
                    }
                } else { }
            }
        }

        return $newArr;
    }

    public function generateXML($arr)
    {
        $xml = new SimpleXMLElement('<SHOP/>');
        self::arrToXML($arr, $xml);
        $xml->asXML('plny.xml');
    }

    private function arrToXML($data, &$xml)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subnode = $xml->addChild($key != 'categories' ? 'SHOPITEM' : strtoupper($key));
                self::arrToXML($value, $subnode);
            } else {
                $xml->addChild(!is_numeric($key) ? strtoupper($key) : 'CATEGORY', htmlspecialchars($value ?? ''));
            }
        }
    }
}