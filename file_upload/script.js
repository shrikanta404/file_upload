$(document).ready(function() {
    $('#dataForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: 'insert.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response);
                $('#dataForm')[0].reset();
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
});
