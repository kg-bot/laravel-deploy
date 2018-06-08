<?php

return [
    'contracts'    =>
        [
            'clContract'  => 'Kontrakt',
            'clEndDate'   => 'Start dato',
            'clStartDate' => 'Slut dato',
            'header'      => 'Aktive Kontrakter',
        ],
    'groups'       =>
        [
            'clEco_account'   => 'e-conomic konto',
            'clHas_inventory' => 'Har lagertræk?',
            'clName'          => 'Navn',
            'clNumber'        => 'Nummer',
            'clProducts'      => 'Produkter',
            'new_group'       => 'Ny gruppe',
            'new_group_modal' =>
                [
                    'labels'       =>
                        [
                            'eco_account'   => 'e-conomic samlekonto:',
                            'has_inventory' => 'Har lagertræk?',
                            'name'          => 'Navn:',
                            'number'        => 'Nummer:',
                        ],
                    'placeholders' =>
                        [
                            'name'   => 'Skriv gruppe navn',
                            'number' => 'Skriv gruppe navn',
                        ],
                    'title'        => 'Opret ny varegruppe',
                ],
            'table_header'    => 'Alle varegrupper',
        ],
    'notes'        => 'Noter',
    'product'      => 'Produkt',
    'product_info' => 'Produkt info',
    'status'       =>
        [
            'available' => 'Tilgængelig',
            'no_stock'  => 'Ingen på lager',
            'rented'    => 'Udlejet',
            'status'    => 'Status',
        ],
    'transactions' =>
        [
            'clDate'           => 'Dato',
            'clLocation'       => 'Lokation',
            'clQuantity'       => 'Antal',
            'clText'           => 'Tekst',
            'clTotal_quantity' => 'Total Antal',
            'header'           => 'Transaktioner',
        ],
];
