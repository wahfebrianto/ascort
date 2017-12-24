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

    $(document).ready(function(){
        var allStatesAndCities = <?php echo json_encode(config('cities'), JSON_PRETTY_PRINT); ?>;

        //-------------- initializing
        var selectedBirthPlaceState = "Aceh";
        var selectedAddressState = "Aceh";

        $("#birth_place_state").html("");
        $.each(allStatesAndCities, function (key, item) {
            $('#birth_place_state').append($('<option>', {
                value: key,
                text : key
            }));
        });

        $("#state").html("");
        $.each(allStatesAndCities, function (key, item) {
            $('#state').append($('<option>', {
                value: key,
                text : key
            }));
        });

        $("#cor_state").html("");
        $.each(allStatesAndCities, function (key, item) {
            $('#cor_state').append($('<option>', {
                value: key,
                text : key
            }));
        });

        var birthPlaceCities = allStatesAndCities[selectedBirthPlaceState];
        refillCity("#birth_place", birthPlaceCities);

        var addressCities = allStatesAndCities[selectedAddressState];
        refillCity("#city", addressCities);

        var addressCities = allStatesAndCities[selectedAddressState];
        refillCity("#cor_city", addressCities);

        //-------------- on change
        $("#birth_place_state").on('change', function(){
            selectedBirthPlaceState = $("#birth_place_state option:selected").text();
            console.log(selectedBirthPlaceState);
            birthPlaceCities = allStatesAndCities[selectedBirthPlaceState];
            refillCity("#birth_place", birthPlaceCities);
        });

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
