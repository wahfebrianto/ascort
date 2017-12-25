<?php ?>
@if(in_array(json_decode($reminder->content)->branch_office_id, \App\BranchOffice::getBranchOfficesID()))
<div class="info-box">
    @if($reminder->title == "Approval Approved")
    <span class="info-box-icon bg-green"><i class="ion ion-clipboard"></i></span>
    @else
    <span class="info-box-icon bg-red"><i class="ion ion-clipboard"></i></span>
    @endif

    <div class="info-box-content">
        <span class="info-box-text">{{$reminder->title}}</span>
        <span>{!! json_decode($reminder->content)->message !!}</span>
        <br />
        <span>
            <a class="btn btn-action btn-danger btn-sm" href="{{ url('dashboard/dismissreminder/' . $reminder->id) }}">
                <i class="fa fa-times"></i> Dismiss
            </a>
        </span>
    </div>
</div>
@endif
