window.Jquery = require('jquery');
require('./bootstrap');

$(function() {
    getFriendsList();
    getNotificationList();

    $('.notification-icon').on('click', function(){
        $(this).next().fadeToggle();
    });

    $('.close-friend-request-modal').on('click', function(){
        $('#request-modal').modal('hide');
        getNotificationList();
    });


    function getNotificationList(){
        let html = '';
        $('.notification-block__inner').hide();
        $.ajax({
            url: "/request/list",
            type:'POST',
            //contentType:'application/json',
            data: {},
            beforeSend: function() {

            },
            success: function(data) {
                if(data.length) {

                    $('.notification-indicator').fadeIn();

                        for(let i=0; i < data.length; i++) {
                            html += '<li class="users-list__item">' +
                                '<div class="users-list__item-inner df justify-content-between align-items-center">' +
                                '<span class="notification-list__text">' + data[i]['name'] + ' ' + data[i]['lastname'] + ' sent you a friend request.</span>' +
                                '<div class="notification-btn-box">' +
                                '<span class="approve-friend-btn notification-action-btn" data-alias="approve" data-id="' + data[i]['friendship_id'] + '"> <i class="fas fa-check-circle"></i></span>' +
                                '<span class="reject-friend-btn notification-action-btn" data-alias="reject" data-id="' + data[i]['friendship_id'] + '"> <i class="fas fa-times-circle"></i></span>' +
                                '</div>' +
                                '</div>' +
                                '</li>';
                        }

                        $('.notifications-list').empty().append(html);


                    //Events
                    $('.notification-action-btn').on('click', function(){
                        let url = null;
                        let friendship_id = $(this).data('id');
                        if($(this).data('alias') === 'approve'){
                            url = '/request/approve'
                        }else if($(this).data('alias') === 'reject') {
                            url = '/request/rejected'
                        }
                        friendRequestAnswer(url, friendship_id);
                    })

                }else{
                    $('.notification-indicator').hide();
                    $('.notifications-list').empty().append("<li class='tc'>You don't have notifications yet.</li>");
                }
            }
        });
    }

    function friendRequestAnswer(url, friendship_id){
        $.ajax({
            url: url,
            type:'POST',
            //contentType:'application/json',
            data: {friendship_id: friendship_id},
            beforeSend: function() {

            },
            success: function(data) {
                $('#request-modal').modal('hide');
                $('#success-modal').modal();
                getFriendsList();
            }
        });
    }

    $('.request-answer').on('click', function(){
        let url = null;
        let friendship_id = $('#request-modal input[name="friendship_id"]').val();
        if($(this).data('alias') === 'reject') {
            url = '/request/rejected'
        }else if($(this).data('alias') === 'approve') {
            url = '/request/approve'
        }

        friendRequestAnswer(url, friendship_id);

    })

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


    function getFriendsList(){
        $.ajax({
            url: "/friends",
            type:'POST',
            //contentType:'application/json',
            data: {},
            beforeSend: function() {
                $('.my-friends-block-inner').append('<div class="loading-block"></div>');
            },
            success: function(data) {
                drawFriendsList(data);
                getNotificationList();
            }
        });
    }

    function drawFriendsList(data) {
        const isFriend = 1;
        let html = '';
        let friends_list = [];
        $('.my-friends-block-inner .loading-block').fadeOut();
        setTimeout(function(){
            $('.my-friends-block-inner .loading-block').remove()
        }, 3000);
        if(data.length){
            for(let i=0; i < data.length; i++) {
                if(data[i]['status_id'] === isFriend) {
                    friends_list.push(data[i])
                }
            }
        }

        if(friends_list.length) {
            for(let i=0; i < friends_list.length; i++){
                if(friends_list[i]['status_id'] === isFriend) {
                    html += '<li class="users-list__item">' +
                        '<div class="users-list__item-inner df justify-content-between">' +
                        '<span class="users-list__name">' + friends_list[i]['name'] + ' ' + friends_list[i]['lastname'] + '</span>' +
                        '<button type="button" class="btn btn-primary remove-friend-btn" data-id="' + friends_list[i]['user_receiver_id'] + '"><i class="fas fa-user-minus"></i> Unfriend</button>' +
                        '</div>' +
                        '</li>'
                }
            }
            $('.my-friends-block .users-list').empty().append(html);
        }else {
            $('.my-friends-block .users-list').empty().append("<li class='tc'>You don't have friends yet.</li>");
        }

        //Events
        //Remove friend
        $('.remove-friend-btn').on('click', function(e){
            $.ajax({
                url: "/remove_friend",
                type:'POST',
                //contentType:'application/json',
                data: {'user_id': $(this).data('id')},
                beforeSend: function() {

                },
                success: function(data) {
                    if(data) {
                        $('#success-modal').modal();
                        getFriendsList();
                    }

                }
            });
        });

    }

    function drawUsersList(data) {
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

        //Events
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

