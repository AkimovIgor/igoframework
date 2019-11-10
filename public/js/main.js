$(document).ready(function() {
    // $('#ajax-btn').click(function() {
    //     $.ajax({
    //         url: '/main/test',
    //         type: 'post',
    //         data: {id: 2},
    //         success: function(res) {
    //             $('.content').html(res);
    //         }
    //     });
    // });
    $('.ajax-btn').on('click', function(event) {
        id = $(this).attr('data-href');
        $.ajax({
            url: '/main/test',
            type: 'post',
            cache: false,
            data: {id: id},
            success: function(res) {
                window.history.pushState({id: id}, null, '/main/test');
                window.history.replaceState(null, null, '/main/test');
                $('.content').html(res);
            }
        });
    });
});

window.addEventListener('popstate', function(e) {
    window.location.reload(true);
    // $('.content').load(window.location.pathname);
    // console.log(window);
});