<script type="text/javascript">
    function refillCity(id, cities){
        $(id).html("");
        $.each(cities, function (key, item) {
            $(id).append($('<option>', {
                value: item,
                text : item
            }));
        });
    }

    function refillCityWithSelected(id, cities, selectedValue){
        $(id).html("");
        $.each(cities, function (key, item) {
            if(item == selectedValue){
                $(id).append($('<option>', {
                    value: item,
                    text : item,
                    selected : true
                }));
            }else{
                $(id).append($('<option>', {
                    value: item,
                    text : item
                }));
            }

        });
    }

    $(document).ready(function(){
        var allStatesAndCities = <?php echo json_encode(config('cities'), JSON_PRETTY_PRINT); ?>;

        //-------------- initializing

        var selectedBirthPlaceState = "";
        var selectedAddressState = "{{ $addressState }}";
        var selectedCorAddressState = "{{ $corAddressState }}";

        $("#state").html("");
        $.each(allStatesAndCities, function (key, item) {
            if(key == selectedAddressState){
                $('#state').append($('<option>', {
                    value: key,
                    text : key,
                    selected : true
                }));
            }else{
                $('#state').append($('<option>', {
                    value: key,
                    text : key
                }));
            }

        });

        $("#cor_state").html("");
        $.each(allStatesAndCities, function (key, item) {
            if(key == selectedCorAddressState){
                $('#cor_state').append($('<option>', {
                    value: key,
                    text : key,
                    selected : true
                }));
            }else{
                $('#cor_state').append($('<option>', {
                    value: key,
                    text : key
                }));
            }

        });

        var addressCities = allStatesAndCities[selectedAddressState];
        var addressCorCities = allStatesAndCities[selectedCorAddressState];
        refillCityWithSelected("#city", addressCities, "{{ $addressCity }}");
        refillCityWithSelected("#cor_city", addressCorCities, "{{ $corAddressCity }}");

        //-------------- on change

        $("#state").on('change', function(){
            selectedAddressState = $("#state option:selected").text();
            addressCities = allStatesAndCities[selectedAddressState];
            refillCity("#city", addressCities);
        });

        $("#cor_state").on('change', function(){
            selectedAddressState = $("#cor_state option:selected").text();
            addressCities = allStatesAndCities[selectedAddressState];
            refillCity("#cor_city", addressCities);
        });


    });
</script>
