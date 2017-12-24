<?php ?>
<div class="info-box">
    <span class="info-box-icon bg-purple"><i class="ion ion-loop"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">{{ $reminder->title }}</span>
        <span>{!! json_decode($reminder->content)->message !!}</span>
        <br />
        <span>
            <a class="btn btn-action btn-success btn-sm" href="{{ url($reminder->respond_link . '/' .  $reminder->id) }}">
                <i class="fa fa-check"></i> Yes
            </a>

            <a class="btn btn-action btn-danger btn-sm" href="{{ url('dashboard/dismissreminder/' . $reminder->id) }}">
                <i class="fa fa-times"></i> No
            </a>
        </span>
    </div>
</div>
