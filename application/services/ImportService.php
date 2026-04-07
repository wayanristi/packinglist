<?php

class ImportService {

    public function parse_excel_rows($rows)
    {
        $first = $rows[0];

        $op_number = $first['Production Order'] ?? '';

        $drum_numbers = [];
        foreach ($rows as $row) {
            if (!empty($row['Standard Description'])) {
                preg_match_all('/\d+/', $row['Standard Description'], $matches);
                foreach ($matches[0] as $num) {
                    $drum_numbers[] = $num;
                }
            }
        }

        $lots = [];
        foreach ($rows as $row) {
            if (!empty($row['Lot'])) {
                preg_match_all('/B\d+/', $row['Lot'], $matches);
                foreach ($matches[0] as $lot) {
                    $lots[] = $lot;
                }
            }
        }

        $lots = array_values(array_unique($lots));

        $raw_qty_lot = $first['Quantity Lot'] ?? '0';
        $raw_qty_lot = str_replace('.', '', $raw_qty_lot);
        $raw_qty_lot = str_replace(',', '.', $raw_qty_lot);

        return [
            'customer_name'        => $first['CustomerName'] ?? '',
            'production_order'     => $op_number,
            'sales_order'          => $first['Sales Order'] ?? '',
            'ordered_quantity'     => $first['Ordered Quantity'] ?? 0,
            'lot'                  => json_encode($lots),
            'quantity_lot'         => (float) $raw_qty_lot,
            'item'                 => $first['Item'] ?? '',
            'standard_description' => json_encode($drum_numbers),
            'description'          => $first['Description'] ?? ''
        ];
    }

}