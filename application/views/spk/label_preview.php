<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Label Preview</title>

    <style>
        body {
            font-family: Arial;
            background: white;
        }

        .label {
            width: 720px;
            margin: auto;
            border: 3px solid black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid black;
            padding: 5px;
            font-size: 14px;
        }

        .center {
            text-align: center;
            font-weight: bold;
        }

        .right {
            text-align: right;
            font-weight: bold;
        }

        .company {
            font-style: italic;
            font-size: 18px;
            font-weight: bold;
        }

        .remark {
            height: 160px;
            vertical-align: top;
            font-weight: bold;
        }

        .manual {
            text-align: center;
        }

        .labeltext {
            font-weight: bold;
            width: 260px;
        }

        input {
            width: 100%;
            border: none;
            outline: none;
            text-align: center;
            font-weight: normal;
            font-size: 14px;
        }

        .label {
            width: 720px;
            margin: auto;
            border: 3px solid black;
            transform: scale(0.85);
            transform-origin: top center;
        }

        textarea {
            width: 100%;
            height: 110px;
            border: none;
            outline: none;
            resize: none;
            font-weight: normal;
            font-size: 14px;
        }

        @media print {
            .label {
                page-break-after: always;
            }
        }
    </style>
</head>

<body>

    <?php
    $totalDrum = isset($drums) ? count($drums) : 1;
    $drumType = isset($drums[0]) ? $drums[0] : '';
    ?>

    <?php for ($i = 1; $i <= $totalDrum; $i++): ?>

        <?php
        $drumNomor = $i;

        // reset nomor untuk drum sisa (khusus stranding)
        if ($type === "stranding") {
            $drumUtama = $totalDrum - 1; // asumsi drum sisa 1
            if ($i > $drumUtama) {
                $drumNomor = $i - $drumUtama;
            }
        }
        ?>
        <div class="label">

            <table>

                <tr>
                    <td colspan="4" class="right">
                        JCC – MV – PS – 002 – F004 – REV 1
                    </td>
                </tr>

                <tr>
                    <td colspan="3" class="company">
                        PT. JEMBO CABLE COMPANY
                    </td>
                    <td class="center">
                        PRODUCTION
                    </td>
                </tr>

                <tr>
                    <td class="labeltext">MACHINE NO. :</td>
                    <td colspan="3">
                        <input type="text" style="text-align:left;">
                    </td>
                </tr>

                <tr>
                    <td class="labeltext">PROCESS :</td>
                    <td colspan="3"><?= strtoupper($type) ?></td>
                </tr>

                <tr>
                    <td class="labeltext">ORDER NO. :</td>
                    <td colspan="3"><?= $excel->production_order ?></td>
                </tr>

                <tr>
                    <td class="labeltext">TYPE/SIZE :</td>
                    <td colspan="3"><?= $excel->description ?></td>
                </tr>

                <tr>
                    <td class="labeltext">CUSTOMER :</td>
                    <td colspan="3"><?= $excel->customer_name ?></td>
                </tr>

                <tr>
                    <td class="labeltext">DIAMETER :</td>
                    <td colspan="3"><?= $diameter ?></td>
                </tr>
                
                <tr>
                    <td class="labeltext">DRUM NO. :</td>
                    <td colspan="3">

                        <?php
                        if ($type === "insul") {

                            $no = $i;

                            $size = '';
                            if (isset($drums[$i - 1])) {
                                $size = preg_replace('/\D/', '', $drums[$i - 1]);
                            }

                            $lots = json_decode($excel->lot ?? '[]', true);
                            $lot  = $lots[$i - 1] ?? '';

                            echo $no . '   ' . $size . '   ' . $lot;
                        } else {

                           $drumVal = $drums[$i - 1] ?? '';
echo $drumVal . ' - ' . $drumNomor;
                        }
                        ?>

                    </td>
                </tr>

                <tr>
                    <td class="labeltext">LENGTH :</td>
                    <td colspan="3">

                        <?php
                        if ($type === "drawing" && !empty($qty_drum)) {

                            echo $qty_drum;
                        }elseif (($type === "stranding" || $type === "stranding2" || $type === "insul") && isset($lengths[$i - 1])) {
    echo $lengths[$i - 1];
} else {

                            echo rtrim(rtrim(number_format($length, 2, '.', ''), '0'), '.');
                        }
                        ?>

                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="remark">
                        REMARKS<br><br>
                        <textarea></textarea>
                    </td>

                    <td colspan="2" class="remark">
                        TEST RESULT<br><br>
                        <textarea></textarea>
                    </td>
                </tr>

                <tr>
                    <td rowspan="2" class="center">DATE</td>
                    <td rowspan="2" class="center">SHIFT</td>
                    <td colspan="2" class="center">OPERATOR</td>
                </tr>

                <tr>
                    <td class="center">NAME</td>
                    <td class="center">SIGN</td>
                </tr>

                <tr>
                    <td class="manual"><input type="text"></td>
                    <td class="manual"><input type="text"></td>
                    <td class="manual"><input type="text"></td>
                    <td class="manual"><input type="text"></td>
                </tr>

            </table>

        </div>
        <br><br>
    <?php endfor; ?>
</body>

</html>