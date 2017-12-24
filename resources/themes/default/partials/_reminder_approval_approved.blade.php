<?php ?>
<div class="info-box">
    <span class="info-box-icon bg-green"><i class="ion ion-clipboard"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">Approval Approved</span>
        <span>{!! json_decode($reminder->content)->message !!}</span>
        <br />
        <span>
            <a class="btn btn-action btn-danger btn-sm" href="{{ url('dashboard/dismissreminder/' . $reminder->id) }}">
                <i class="fa fa-times"></i> Dismiss
            </a>
        </span>
    </div>
</div>
