<?php

return [
    'new_customer'               => 'New customer',
    'table'                      =>
        [
            'header'   => 'All customers',
            'clName'   => 'Customer name',
            'clNumber' => 'Number',
            'clPhone'  => 'Phone',
            'clZip'    => 'Zip Code',
        ],
    'info'                       =>
        [
            'address'         => 'Address',
            'cellphone'       => 'Cellphone',
            'city'            => 'City',
            'ean_number'      => 'EAN Number',
            'email'           => 'E-mail',
            'group'           => 'Group',
            'header'          => 'Customer info',
            'name'            => 'Name',
            'number'          => 'Number',
            'phone'           => 'Phone',
            'primary_contact' => 'Primary Contact',
            'vat_number'      => 'VAT no.',
            'zip_code'        => 'Zip Code',
        ],
    'contacts'                   =>
        [
            'header' => 'Contact Persons',
        ],
    'location'                   =>
        [
            'header' => 'Customer location',
        ],
    'new_customer_modal'         =>
        [
            'header'       => 'Create new Customer',
            'labels'       =>
                [
                    'address'        => 'Address:',
                    'cellphone'      => 'Cellphone:',
                    'city'           => 'City:',
                    'contact_person' => 'Contact Person:',
                    'ean_number'     => 'EAN Number:',
                    'email'          => 'E-mail:',
                    'group'          => 'Customer Group:',
                    'name'           => 'Name:',
                    'number'         => 'Number:',
                    'phone'          => 'Phone:',
                    'vat_number'     => 'VAT Number:',
                    'zip_code'       => 'Zip Code:',
                ],
            'placeholders' =>
                [
                    'address'        => 'Enter address',
                    'cellphone'      => 'Enter cellphone:',
                    'city'           => 'Enter city',
                    'contact_person' => 'Enter contact person name',
                    'ean_number'     => 'Enter EAN number',
                    'email'          => 'Enter email address',
                    'name'           => 'Enter customer name',
                    'number'         => 'Enter customer number',
                    'phone'          => 'Enter phone',
                    'vat_number'     => 'Enter VAT Number',
                    'zip_code'       => 'Enter zip code',
                ],
        ],
    'new_customer_contact_modal' =>
        [
            'header'       => 'Create new Customer Contact',
            'labels'       =>
                [
                    'email' => 'Email:',
                    'name'  => 'Name:',
                    'phone' => 'Phone:',
                ],
            'placeholders' =>
                [
                    'email' => 'Enter email',
                    'name'  => 'Contact name',
                    'phone' => 'Enter phone number',
                ],
        ],
    'groups'                     =>
        [
            'new_group'       => 'New group',
            'table'           =>
                [
                    'clCustomers'   => 'Customers',
                    'clEco_account' => 'Eco. Account',
                    'clID'          => 'ID',
                    'clName'        => 'Name',
                    'clNumber'      => 'Number',
                ],
            'heading'         => 'Customer groups',
            'new_group_modal' =>
                [
                    'heading'      => 'New customer group',
                    'labels'       =>
                        [
                            'eco_account' => 'Economic Account:',
                            'name'        => 'Name:',
                            'number'      => 'Number:',
                        ],
                    'placeholders' =>
                        [
                            'name'   => 'Enter group name',
                            'number' => 'Enter group number',
                        ],
                ],
        ],
];
