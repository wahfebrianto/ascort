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
        var birthPlaceCity = "{{ $birthCity }}";

        var selectedBirthPlaceState = "";
        var selectedAddressState = "{{ $addressState }}";

        $("#birth_place_state").html("");
        $.each(allStatesAndCities, function (key, item) {

            if($.inArray(birthPlaceCity, item) >= 0){
                $('#birth_place_state').append($('<option>', {
                    value: key,
                    text : key,
                    selected : true
                }));
                selectedBirthPlaceState = key;

            }else{
                $('#birth_place_state').append($('<option>', {
                    value: key,
                    text : key
                }));
            }

        });

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

        var birthPlaceCities = allStatesAndCities[selectedBirthPlaceState];
        refillCityWithSelected("#birth_place", birthPlaceCities, birthPlaceCity);

        var addressCities = allStatesAndCities[selectedAddressState];
        refillCityWithSelected("#city", addressCities, "{{ $addressCity }}");

        //-------------- on change
        $("#birth_place_state").on('change', function(){
            selectedBirthPlaceState = $("#birth_place_state option:selected").text();
            birthPlaceCities = allStatesAndCities[selectedBirthPlaceState];
            refillCity("#birth_place", birthPlaceCities);
        });

        $("#state").on('change', function(){
            selectedAddressState = $("#state option:selected").text();
            addressCities = allStatesAndCities[selectedAddressState];
            refillCity("#city", addressCities);
        });


    });
</script>
