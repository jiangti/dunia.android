define(['libs/jquery', 'simplemodal'], function(init) {
    $(document).ready(function() {
        $("#login-link").click(function() {
            var url = $(this).attr('href');
            $.ajax({
                url: '/user/login/',
                success: function(data) {
                    $.modal(data, {height: '420px'});
                    return false;
                }
            });
            return false;
        });
    });
});