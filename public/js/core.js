// var $select1 = $( '#customer' ),
//     $select2 = $( '#amount' ),
//     $options = $select2.find( 'option' );
//
// $select1.on( 'change', function() {
//    var data = $select2.html( $options.filter( '[value="' + this.value + '"]' ) );
// } ).trigger( 'change' );
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$("#customer").on("change", function (e) {
    var customer = e.target.value;
    // console.log(customer);
    $.post("services", { id: customer }, function (response) {
        // console.log(response);
        $("#amount").empty();
        $.each(response, function (index, service) {
            // console.log(service);
            $("#amount").append(
                '<option value="' +
                    service.id +
                    '">' +
                    service.name +
                    "</option>"
            );
        });
    });
});

$("#customerbyphone").on("change", function (e) {
    var customer = e.target.value;
    // console.log(customer);
    $.post("services", { id: customer }, function (response) {
        // console.log(response);
        $("#amountbyphone").empty();
        $.each(response, function (index, service) {
            // console.log(service);
            $("#amountbyphone").append(
                '<option value="' +
                    service.id +
                    '">' +
                    service.name +
                    "</option>"
            );
        });
    });
});
