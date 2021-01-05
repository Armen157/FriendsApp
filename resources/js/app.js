window.Jquery = require('jquery');
require('./bootstrap');

$(function() {
    $("input[name=find_friends]").on('input', function(e){
        $.ajax({
            url: "/users",
            type:'POST',
            //contentType:'application/json',
            data: {string: $(this).val()},
            beforeSend: function() {
                $('.users-search__search-result').append('<div class="loading-block"></div>');
            },
            success: function(data) {
                drawUsersList(data);
            }
        });
    });


    function drawUsersList(data) {
        console.log(data)
        $('.users-search__search-result .loading-block').fadeOut();
        setTimeout(function(){
            $('.users-search__search-result .loading-block').remove()
        }, 3000);
        const isFriend = 1;
        const pending = 2;
        let userList = data['users'];
        let friendList = data['friends'];
        let html = '';

        for (let i = 0; i < userList.length; i++) {
            let status_id = null;
            for(let j = 0; j < friendList.length; j++) {
                if(friendList[j]['user_receiver_id'] === userList[i]['id']) {
                    status_id = friendList[j]['status_id'];
                }
            }
            userList[i]['status_id'] = status_id;
        }

        if(userList.length) {
            for(let i=0; i < userList.length; i++){
                html+='<li class="users-list__item">' +
                    '<div class="users-list__item-inner df justify-content-between">' +
                        '<span class="users-list__name">'+ userList[i]['name'] + ' ' + userList[i]['lastname'] +  '</span>';
               if(userList[i]['status_id'] !== isFriend){
                   if(userList[i]['status_id'] === pending) {
                       html+='<button type="button" class="btn btn-primary add-friend-btn disabled" data-id="'+userList[i]['id']+'"><i class="fas fa-user-plus"></i> Add friend</button>';
                   }else {
                       html+='<button type="button" class="btn btn-primary add-friend-btn" data-id="'+userList[i]['id']+'"><i class="fas fa-user-plus"></i> Add friend</button>';
                   }
               }
                html+='</div>' +
                    '</li>'
            }
            $('.users-search__search-result .users-list').empty().append(html);
        }else {
            $('.users-search__search-result .users-list').empty().append("<li class='tc'>No results</li>");
        }

        //Add friend action
        $('.add-friend-btn').on('click', function(e){
            if($(this).hasClass('disabled')){
               return false;
            }
            $.ajax({
                url: "/add_friend",
                type:'POST',
                //contentType:'application/json',
                data: {'user_receiver_id': $(this).data('id')},
                beforeSend: function() {

                },
                success: function(data) {
                    $('#success-modal').modal();
                }
            });
        });

    }

});

