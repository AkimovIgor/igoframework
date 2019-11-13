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


    /************ BOOTSTRAP ************/
    $num = 1;
    $('.navbar .dropdown-item').on('click', function (e) {

               
        var $el = $(this).children('.dropdown-toggle')
        var $parent = $el.offsetParent(".dropdown-menu");

        $(this).parent("li").toggleClass('open');

        if ($el[0] !== undefined) {
            e.preventDefault();
        }
        e.stopPropagation();

        if (!$parent.parent().hasClass('navbar-nav')) {

            if ($parent.hasClass('show')) {
                $parent.removeClass('show');
                $el.next().removeClass('show');
                $el.next().css({"top": -999, "left": -999});
                
                
            } else {
                $parent.parent().find('.show').removeClass('show');
                $parent.addClass('show');
                $el.next().addClass('show');

                
                if ($el[0]){
                    $pos = 1;

                    if ($num == 0) {
                        
                        $pos = 1;
                        $num = 1;
                    } else {
                        $num = 0;
                    }
                    
                    $el.next().css({"top": $el[0].offsetTop + 10, "left": ($parent.outerWidth() - 4) * $pos});
                }
                
            }
            // console.log($num);
        }
    });



    $('.navbar .dropdown').on('hidden.bs.dropdown', function () {
        $(this).find('li.dropdown').removeClass('show open');
        $(this).find('ul.dropdown-menu').removeClass('show open');
    });

});

function getRandom(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min + 1)) + min; //Максимум и минимум включаются
}

window.addEventListener('popstate', function(e) {
    window.location.reload(true);
    // $('.content').load(window.location.pathname);
    // console.log(window);
});