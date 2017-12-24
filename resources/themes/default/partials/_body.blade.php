<body class="skin-blue sidebar-mini">

    <!-- Main body content -->
    @include('partials._body_content')


    <!-- Footer -->
    @include('partials._footer')

    <!-- Optional bottom section for modals etc... -->
    @yield('body_bottom')


    <!-- Application JS-->
    <script href="{{ asset('js/all.js') }}"></script>

</body>
