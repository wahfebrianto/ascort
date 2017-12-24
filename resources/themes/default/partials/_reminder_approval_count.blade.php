<?php ?>
<div class="info-box">
    <span class="info-box-icon bg-aqua"><i class="ion ion-clipboard"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">Waiting Approval</span>
        <span>You have <?= $total ?> pending approval(s)</span>
        <br />
        <span>
            <a class="btn btn-action btn-success btn-sm" href="<?= url('approvals') ?>">
                <i class="fa fa-arrow-circle-right"></i> Go to approvals page
            </a>
        </span>
    </div>
</div>
