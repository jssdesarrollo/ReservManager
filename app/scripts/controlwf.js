$(document).ready(function() {
    $.ajax({
                url:"controlwf.php",
                method:"POST",
                dataType:"json",
                data:{cif:cif},
                cache:"false",
                success:function(data) {
                    
                }
            });
});


