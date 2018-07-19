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

        @media print{
            #downloadPDFAll{
                display:none;
            }
        }
    </style>
</head>
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
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
<script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
<!--script type="text/javascript" src="{{ asset('js/jspdf.min.js') }}"></script-->
<script type="text/javascript" src="{{ asset('js/html2pdf.js') }}"></script>
<!--
<div class="header">
    <h5>MLCS PROJECT</h5>
</div>
<div class="footer">

</div>
-->
<div class="text-right" style="margin:1%">
    <button id="downloadPDFAll" class="btn btn-primary">Download All in PDF</button>
</div>
@yield('content')
</body>


<script type="text/javascript">
    $(document).ready(function(){
        $("#downloadPDFAll").click(function(){
            var today = new Date();
            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
            var dateTime = date+'_'+time;
            var els = document.getElementsByClassName("wrapperpdf");
            Array.prototype.forEach.call(els, function(el) {
                    var opt = {
                      margin:       0.1,
                      filename:     el.getAttribute('name')+"_"+dateTime+".pdf",
                      image:        { type: 'jpeg', quality: 1 },
                      html2canvas:  { scale:1 },
                      jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
                    };
                  html2pdf().from(el).set(opt).save();
            });
        });

    });
</script>
</html>
