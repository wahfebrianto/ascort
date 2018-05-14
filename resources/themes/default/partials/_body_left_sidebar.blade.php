<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('/assets/images/logo-print.jpg?'.time()) }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p class="small">{{ config('names.perusahaan') }}</p>

                <span class="small">Multilevel Commission Sys.</span>
            </div>
        </div>

        {!! MenuBuilder::renderMenu('home')  !!}

        {!! MenuBuilder::renderMenu('admin', true)  !!}

    </section>
    <!-- /.sidebar -->
</aside>
