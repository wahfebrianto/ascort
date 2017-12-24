<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Slip PDF')</title>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="{{ asset("css/bootstrap-print.css") }}" rel="stylesheet" type="text/css" />
    <style>
		body {
			font-size: 10px;
		}
        table, p, h1, h2, h3, h4 {
            font-family: 'Segoe UI', sans-serif;
        }
        table td {
            font-size: 8pt;
        }
        p {
            margin: 0px;
            font-size: 9pt;
        }
        table th {
            text-align: center;
        }
        table.info td {
            padding: 2px 0px;
            font-size: 8pt;
        }
        table.info {
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        .page-break {
            page-break-after: always;
        }
        td.border-bottom-decor, div.border-bottom-decor {
            border-bottom: 1px solid #ddd;
        }
        tr { page-break-inside: avoid;  }
    </style>
</head>
<body>
<script type='text/php'>
      if ( isset($pdf) ) {
        $font = Font_Metrics::get_font('helvetica', 'normal');
        $size = 8;
        $y = $pdf->get_height() - 24;
        $x = $pdf->get_width() - 75 - Font_Metrics::get_text_width('1/1', $font, $size);
        $pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
      }
</script>
<!--
<div class="header">
    <h5>MLCS PROJECT</h5>
</div>
<div class="footer">

</div>
-->
@yield('content')
</body>
</html>
