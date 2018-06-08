<?php

return [
    'product'      => 'Product',
    'product_info' => 'Product Info',
    'notes'        => 'Notes',
    'status'       =>
        [
            'available' => 'Available',
            'no_stock'  => 'No Stock',
            'rented'    => 'Rented',
            'status'    => 'Status',
        ],
    'contracts'    =>
        [
            'header'      => 'On-Going Contracts',
            'clContract'  => 'Contract',
            'clEndDate'   => 'End Date',
            'clStartDate' => 'Start Date',
        ],
    'transactions' =>
        [
            'header'           => 'Product Transactions',
            'clDate'           => 'Date',
            'clLocation'       => 'Location',
            'clQuantity'       => 'Quantity',
            'clText'           => 'Text',
            'clTotal_quantity' => 'Total Quantity',
        ],
    'groups'       =>
        [
            'new_group'       => 'New Group',
            'table_header'    => 'All product groups',
            'clEco_account'   => 'Eco. Account',
            'clHas_inventory' => 'Has Inventory',
            'clName'          => 'Name',
            'clNumber'        => 'Number',
            'clProducts'      => 'Products',
            'new_group_modal' =>
                [
                    'labels'       =>
                        [
                            'eco_account'   => 'Economic account:',
                            'has_inventory' => 'Has Inventory?',
                            'name'          => 'Name:',
                            'number'        => 'Number:',
                        ],
                    'title'        => 'Create new Product Group',
                    'placeholders' =>
                        [
                            'name'   => 'Enter group name',
                            'number' => 'Enter group number',
                        ],
                ],
        ],
];
