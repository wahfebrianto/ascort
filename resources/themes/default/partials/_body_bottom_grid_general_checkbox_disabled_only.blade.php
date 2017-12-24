<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkUser[]');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }
    $(document).ready(function() {
        $("#btnDisableSelected").on('click', function() {
            if(confirm('{!! trans('general.confirm.delete-selected') !!}')) {
                var $form = $('div#form-container > form').first();
                $form.attr('action', '{!! route($module_name . '.disable-selected') !!}');
                $form.submit();
            }
            return false;
        });
    });
</script>