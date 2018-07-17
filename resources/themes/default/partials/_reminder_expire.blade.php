<?php ?>

<div class="info-box">
    <span class="info-box-icon bg-red"><i class="ion ion-clipboard"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">Sales Jatuh Tempo pada tanggal {!! $reminder->end_date !!}</span>
        <span>
          <?php
              $remcontent = json_decode($reminder->content);
          ?>
          Sales Number : <a href="{{ url('sales/' . $remcontent->id) }}">{!! $remcontent->number !!}</a> <br />
          Customer : {!! $remcontent->customer_name !!}
        </span>
        <br />
        <span>
            <a class="btn btn-action btn-danger btn-sm" href="{{ url('dashboard/dismissreminder/' . $reminder->id) }}">
                <i class="fa fa-times"></i> Dismiss
            </a>
        </span>
    </div>
</div>
