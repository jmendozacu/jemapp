<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   RMA
 * @version   2.4.5
 * @build     1677
 * @copyright Copyright (C) 2017 Mirasvit (http://mirasvit.com/)
 */



return array(
    'rma_id' => '',
    'increment_id' => '',
    'customer_id' => '24',
    'user_id' => '0',
    'status_id' => '1',
    'retyrty' => '',
    'order_id' => array(
        0 => '57',
        2 => 'offline order test',
        3 => 'offline order2 test',
    ),
    'offline' => array(
        'order_name' => array(
            0 => 'offline order test',
            1 => 'offline order2 test',
        ),
    ),
    'items' => array(
        57 => array(
            1 => array(
                'order_id' => '57',
                'item_id' => '',
                'order_item_id' => '157',
                'qty_requested' => '1',
                'reason_id' => '',
                'condition_id' => '',
                'resolution_id' => '',
            ),
            2 => array(
                'order_id' => '57',
                'item_id' => '',
                'order_item_id' => '159',
                'qty_requested' => '0',
                'reason_id' => '',
                'condition_id' => '',
                'resolution_id' => '',
            ),
        ),
    ),
    'offline_items' => array(
        'offline order test' => array(
            'item1 test' => array(
                'name' => 'item1 test',
                'receipt_number' => 'offline order test',
                'qty_requested' => '1',
                'reason_id' => '',
                'condition_id' => '',
                'resolution_id' => '',
            ),
            'item2 test' => array(
                'name' => 'item2 test',
                'receipt_number' => 'offline order test',
                'qty_requested' => '4',
                'reason_id' => '1',
                'condition_id' => '3',
                'resolution_id' => '3',
            ),
        ),
        'offline order2 test' => array(
            'item1 test' => array(
                'name' => 'item1 test',
                'receipt_number' => 'offline order2 test',
                'qty_requested' => '3',
                'reason_id' => '1',
                'condition_id' => '1',
                'resolution_id' => '1',
            ),
        ),
    ),
    'firstname' => 'Jack',
    'lastname' => 'Fitz',
    'company' => '',
    'telephone' => '1313131313',
    'email' => 'jack@example.com',
    'street' => '7 N Willow St',
    'street2' => '',
    'city' => 'Montclair',
    'postcode' => '07042',
);
