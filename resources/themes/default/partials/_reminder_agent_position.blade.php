<?php ?>
<div class="info-box">
    <span class="info-box-icon bg-yellow"><i class="fa ion-ribbon-b"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">{{ $reminder->title }}</span>
        <span>{!! json_decode($reminder->content)->message !!}</span>
        <br />
        <span>
            {!! Form::open( ['method' => 'POST', 'url' => $reminder->respond_link, 'class' => 'form-horizontal'] ) !!}
            {!! Form::hidden('id', $reminder->id, ['class' => 'form-control', 'id' => 'id']) !!}
            {!! Form::hidden('agent_id', json_decode($reminder->content)->id, ['class' => 'form-control']) !!}

            <div class="form-group">
                <div class="input-group select2-bootstrap-append col-md-5">
                    {!! Form::select('agent_position_id', App\AgentPosition::getAgentPositions_ForDropDown(), null, ['class' => 'form-control']) !!}
                    <span class="input-group-addon">
                        <span class="fa fa-fw fa-briefcase"></span>
                    </span>
                </div>
                <input class="btn btn-action btn-success btn-sm" type="submit" value="Yes">
                <a class="btn btn-action btn-danger btn-sm" href="{{ url('dashboard/dismissreminder/' . $reminder->id) }}">
                    <i class="fa fa-times"></i> No
                </a>
            </div>

            {!! Form::close() !!}
        </span>
    </div>
</div>
