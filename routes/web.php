<?php

Route::group(['prefix' => 'admin'],function (){

    Route::group(['middleware'=>'admin'],function (){

        Route::get('profile','Admin\SettingController@profile');
        Route::post('profile/main/update','Admin\SettingController@profileMainUpdate');
        Route::post('profile/security/update','Admin\SettingController@profileSecurityUpdate');

        ########################
        #### About Section #####
        ########################
        Route::get('about','Admin\SettingController@about');


        ########################
        ## Report & Dashboard ##
        ########################
        Route::group(['prefix'=>'report'],function(){
            Route::get('user','Admin\ReportController@user');
            Route::get('content','Admin\ReportController@content');
            Route::get('balance','Admin\ReportController@balance');
        });

        #####################
        ## Balance Section ##
        #####################
        Route::group(['prefix'=>'balance'],function (){
           Route::get('transaction','Admin\ReportController@transaction');
           Route::get('list','Admin\BalanceController@lists');
           Route::get('list/excel','Admin\BalanceController@listsExcel');
           Route::get('new','Admin\BalanceController@newItem');
           Route::post('store','Admin\BalanceController@store');
           Route::get('edit/{id}','Admin\BalanceController@edit');
           Route::post('edit/store/{id}','Admin\BalanceController@editStore');
           Route::get('delete/{id}','Admin\BalanceController@delete');
           Route::get('withdraw','Admin\BalanceController@withdraw');
           Route::get('withdraw/excel','Admin\BalanceController@withdrawExcel');
           Route::post('withdraw/all','Admin\BalanceController@withdrawAll');
           Route::get('analyzer','Admin\BalanceController@analyze');
           Route::get('print/{id}','Admin\BalanceController@printer');
        });

        ################
        ##User Section##
        ################
        Route::group(['prefix'=>'user'],function (){

            Route::get('lists','Admin\UserController@lists');
            Route::get('vendor','Admin\UserController@vendors');
            Route::get('item/{id}','Admin\UserController@item');
            Route::post('edit/{id}','Admin\UserController@edit');
            Route::get('event/{id}','Admin\UserController@event');
            Route::get('delete/{id}','Admin\UserController@delete');
            Route::post('editprofile/{id}','Admin\UserController@editprofile');
            Route::get('incategory/{id}','Admin\UserController@incategory');
            Route::post('ratesection/{id}','Admin\UserController@rateSection');
            Route::get('ratesection/delete/{id}','Admin\UserController@rateSectionDelete');

            ## Seller & Apply
            Route::get('seller','Admin\UserController@Seller');

            ## Category Section
            Route::group(['prefix'=>'category'], function (){
                Route::get('','Admin\UserController@category');
                Route::get('delete/{id}','Admin\UserController@categoryDelete');
                Route::get('edit/{id}','Admin\UserController@categoryEdit');
                Route::post('store','Admin\UserController@categoryStore');
                Route::post('edit/store/{id}','Admin\UserController@categoryEditStore');
            });



            ## Rate Section
            Route::get('rate','Admin\UserController@rate');
            Route::post('rate/store','Admin\UserController@ratestore');
            Route::get('rate/delete/{id}/{tag}','Admin\UserController@ratedelete');
            Route::get('rate/edit/{id}/{tag}','Admin\UserController@rateedit');

            ## User Login ##
            Route::get('userlogin/{id}','Admin\UserController@userLogin');

        });

        ##################
        ## Sell Section ##
        ##################
        Route::group(['prefix'=>'buysell'],function (){
            Route::get('list','Admin\SellController@lists');
        });

        #####################
        ## Channel Section ##
        #####################
        Route::group(['prefix'=>'channel'],function (){
            Route::get('list','Admin\UserController@channelList');
            Route::get('delete/{id}','Admin\UserController@channelDelete');
            Route::get('item/{id}','Admin\UserController@channelEdit');
            Route::get('excel','Admin\UserController@channelExcel');
            Route::post('store/{id}','Admin\UserController@channelStore');
            Route::group(['prefix'=>'request'],function (){
                Route::get('/','Admin\UserController@channelRequest');
                Route::get('delete/{id}','Admin\UserController@channelRequestDelete');
                Route::get('draft/{id}','Admin\UserController@channelRequestDraft');
                Route::get('publish/{id}','Admin\UserController@channelRequestPublish');

            });
        });

        #####################
        ###Manager Section###
        #####################
        Route::group(['prefix'=>'manager'],function (){
            Route::get('lists','Admin\ManagerController@lists');
            Route::get('item/{id}','Admin\ManagerController@item');
            Route::post('capatibility/{id}','Admin\ManagerController@capatibility');
            Route::get('new','Admin\ManagerController@newAdmin');
            Route::post('new/store','Admin\ManagerController@storeadmin');
        });

        ###################
        ###Email Section###
        ###################
        Route::group(['prefix'=>'email'],function (){
            Route::get('new','Admin\EmailController@emailNew');
            Route::get('templates','Admin\EmailController@templateLists');
            Route::get('template/new','Admin\EmailController@templateNew');
            Route::get('template/item/{id}','Admin\EmailController@templateItem');
            Route::get('template/delete/{id}','Admin\EmailController@templateDelete');
            Route::post('template/edit','Admin\EmailController@templateEdit');
            Route::post('sendMail','Admin\EmailController@sendMail');
        });

        ##################
        ###Blog Section###
        ##################
        Route::group(['prefix'=>'blog'],function () {
            Route::get('posts','Admin\BlogController@posts');
            Route::get('post/new','Admin\BlogController@newPost');
            Route::get('post/edit/{id}','Admin\BlogController@editPost');
            Route::get('post/delete/{id}','Admin\BlogController@postDelete');
            Route::post('post/store','Admin\BlogController@store');

            Route::get('category','Admin\BlogController@category');
            Route::get('category/edit/{id}','Admin\BlogController@categoryEdit');
            Route::get('category/delete/{id}','Admin\BlogController@categoryDelete');
            Route::post('category/store','Admin\BlogController@categoryStore');

            Route::get('comments','Admin\BlogController@comments');
            Route::get('comment/edit/{id}','Admin\BlogController@commentEdit');
            Route::get('comment/delete/{id}','Admin\BlogController@commentDelete');
            Route::get('comment/view/{action}/{id}','Admin\BlogController@commentView');
            Route::post('comment/store','Admin\BlogController@commentStore');
            Route::get('comment/reply/{id}','Admin\BlogController@commentReply');
            Route::post('comment/reply/store','Admin\BlogController@commentReplyStore');

            ## Article Section
            Route::get('article','Admin\BlogController@article');
            Route::get('article/edit/{id}','Admin\BlogController@articleEdit');
            Route::get('article/delete/{id}','Admin\BlogController@articleDelete');
            Route::post('article/edit/store/{id}','Admin\BlogController@articleStore');
        });

        ####################
        ###Ticket Section###
        ####################
        Route::group(['prefix'=>'ticket'],function () {
            Route::get('tickets','Admin\TicketController@tickets');
            Route::get('new','Admin\TicketController@ticketNew');
            Route::post('store','Admin\TicketController@ticketNewStore');
            Route::get('ticketsopen','Admin\TicketController@ticketsOpen');
            Route::get('ticketsclose','Admin\TicketController@ticketsClose');
            Route::get('delete/{id}','Admin\TicketController@ticketDelete');
            Route::get('close/{id}','Admin\TicketController@ticketClose');
            Route::get('open/{id}','Admin\TicketController@ticketOpen');
            Route::get('user/{id}','Admin\TicketController@ticketUser');
            Route::post('user/store','Admin\TicketController@ticketUserStore');
            Route::get('user/delete/{id}','Admin\TicketController@ticketUserDelete');


            Route::get('reply/{id}','Admin\TicketController@ticketReply');
            Route::get('reply/{ticketid}/edit/{id}','Admin\TicketController@ticketReplyEdit');
            Route::get('reply/delete/{id}','Admin\TicketController@ticketReplyDelete');
            Route::post('reply/store/{id}','Admin\TicketController@ticketStore');

            Route::get('category','Admin\TicketController@category');
            Route::get('category/edit/{id}','Admin\TicketController@categoryEdit');
            Route::get('category/delete/{id}','Admin\TicketController@categoryDelete');
            Route::post('category/store','Admin\TicketController@categoryStore');
        });

        ##########################
        ###Notification Section###
        ##########################
        Route::group(['prefix'=>'notification'],function () {
            Route::get('list','Admin\NotificationController@lists');
            Route::get('new','Admin\NotificationController@notificationNew');
            Route::get('edit/{id}','Admin\NotificationController@notificationEdit');
            Route::get('delete/{id}','Admin\NotificationController@notificationDelete');
            Route::post('store','Admin\NotificationController@store');
            Route::group(['prefix'=>'template'],function (){
                Route::get('/','Admin\NotificationController@templateLists');
                Route::get('tnew','Admin\NotificationController@templateNew');
                Route::get('item/{id}','Admin\NotificationController@templateItem');
                Route::get('delete/{id}','Admin\NotificationController@templateDelete');
                Route::post('edit','Admin\NotificationController@templateEdit');
            });
        });

        ######################
        ###Discount Section###
        ######################
        Route::group(['prefix'=>'discount'],function () {
            Route::get('list','Admin\DiscountController@lists');
            Route::get('new','Admin\DiscountController@discountNew');
            Route::get('edit/{id}','Admin\DiscountController@discountEdit');
            Route::get('delete/{id}','Admin\DiscountController@discountDelete');
            Route::post('store','Admin\DiscountController@store');

            ## Content Off Section
            Route::get('contentnew','Admin\DiscountController@contentNew');
            Route::get('contentlist','Admin\DiscountController@contentList');
            Route::post('content/store','Admin\DiscountController@contentStore');
            Route::get('content/edit/{id}','Admin\DiscountController@contentEdit');
            Route::get('content/delete/{id}','Admin\DiscountController@contentDelete');
            Route::post('content/edit/store/{id}','Admin\DiscountController@contentEditStore');
            Route::post('content/edit/store/{id}','Admin\DiscountController@contentEditStore');
            Route::get('content/publish/{id}','Admin\DiscountController@contentPublish');
            Route::get('content/draft/{id}','Admin\DiscountController@contentdraft');
        });

        #################################################
        ########### Content ############## Section ######
        #################################################
        Route::group(['prefix'=>'content'],function () {

            Route::get('list','Admin\ContentController@lists');
            Route::get('waiting','Admin\ContentController@waitingList');
            Route::get('draft','Admin\ContentController@draftList');
            Route::get('edit/{id}','Admin\ContentController@edit');
            Route::get('delete/{id}','Admin\ContentController@delete');
            Route::post('store/{id}/{mode}','Admin\ContentController@store');
            Route::get('list/excel','Admin\ContentController@excel');
            Route::get('user/{id}','Admin\ContentController@userContent');

            ### Comment Section
            Route::get('comment','Admin\ContentController@comments');
            Route::get('comment/edit/{id}','Admin\ContentController@commentEdit');
            Route::get('comment/delete/{id}','Admin\ContentController@commentDelete');
            Route::get('comment/view/{action}/{id}','Admin\ContentController@commentView');
            Route::post('comment/store','Admin\ContentController@commentStore');

            # Support Section
            Route::get('support','Admin\ContentController@supports');
            Route::get('support/edit/{id}','Admin\ContentController@supportEdit');
            Route::get('support/delete/{id}','Admin\ContentController@supportDelete');
            Route::get('support/view/{action}/{id}','Admin\ContentController@supportView');
            Route::post('support/store','Admin\ContentController@supportStore');

            ### Parts Section
            Route::get('part/delete/{id}','Admin\ContentController@partDelete');
            Route::get('edit/{id}/part/{pid}','Admin\ContentController@partEdit');
            Route::post('partstore/{id}','Admin\ContentController@partStore');

            ## Usage
            Route::get('usage/{id}','Admin\ContentController@contentUsage');

            ### Category section
            Route::group(['prefix'=>'category'],function (){
                Route::get('','Admin\ContentController@category');
                Route::get('edit/{id}','Admin\ContentController@categoryEdit');
                Route::get('delete/{id}','Admin\ContentController@categoryDelete');
                Route::get('childs/{id}','Admin\ContentController@childs');
                Route::post('store','Admin\ContentController@categoryStore');

                ### Filter Section
                Route::group(['prefix'=>'filter'],function (){
                    Route::get('{id}','Admin\ContentController@categoryFilter');
                    Route::get('delete/{id}','Admin\ContentController@categoryFilterDelete');
                    Route::get('{id}/edit/{fid}','Admin\ContentController@categoryFilterEdit');
                    Route::post('store/{mode}','Admin\ContentController@categoryFilterStore');

                    ### Tag Section
                    Route::group(['prefix'=>'tag'],function (){
                        Route::get('{id}','Admin\ContentController@categoryFilterTags');
                        Route::post('store/{mode}','Admin\ContentController@categoryFilterTagNew');
                        Route::get('delete/{id}','Admin\ContentController@categoryFilterTagDelete');
                        Route::get('{id}/edit/{fid}','Admin\ContentController@categoryFilterTagEdit');
                    });

                });
            });

        });

        #################
        ## Ads Section ##
        #################
        Route::group(['prefix'=>'ads'],function (){
            # Plans
            Route::get('plans','Admin\AdsController@plans');
            Route::get('newplan','Admin\AdsController@newPlan');
            Route::post('plan/store','Admin\AdsController@newPlanStore');
            Route::post('plan/edit/store/{id}','Admin\AdsController@planEditStore');
            Route::get('plan/edit/{id}','Admin\AdsController@planEdit');
            Route::get('plan/delete/{id}','Admin\AdsController@planDelete');

            #boxs
            Route::get('box','Admin\AdsController@boxs');
            Route::get('newbox','Admin\AdsController@newBox');
            Route::get('box/edit/{id}','Admin\AdsController@boxEdit');
            Route::get('box/delete/{id}','Admin\AdsController@boxDelete');
            Route::post('box/edit/store/{id}','Admin\AdsController@boxEditStore');
            Route::post('box/store','Admin\AdsController@boxStore');


            # Request
            Route::get('request','Admin\AdsController@requests');

            # Vip
            Route::get('vip','Admin\AdsController@vipList');
            Route::post('vip/store','Admin\AdsController@vipStore');
            Route::get('vip/edit/{id}','Admin\AdsController@vipEdit');
            Route::get('vip/delete/{id}','Admin\AdsController@vipDelete');
            Route::post('vip/edit/store/{id}','Admin\AdsController@vipEditStore');
        });

        #####################
        ## Setting Section ##
        #####################
        Route::group(['prefix'=>'setting'],function (){
           Route::post('store/{luncher?}','Admin\SettingController@store');
           Route::get('blog','Admin\SettingController@blog');
           Route::get('notification','Admin\SettingController@notification');
           Route::get('main','Admin\SettingController@main');
           Route::get('display','Admin\SettingController@display');
           Route::get('content','Admin\SettingController@content');
           Route::get('term','Admin\SettingController@term');
           Route::get('user','Admin\SettingController@user');
           Route::get('social','Admin\SettingController@social');
           Route::get('social/edit/{id}','Admin\SettingController@socialEdit');
           Route::get('social/delete/{id}','Admin\SettingController@socialDelete');
           Route::post('social/store','Admin\SettingController@socialStore');
           Route::get('footer','Admin\SettingController@footer');
           Route::get('pages','Admin\SettingController@Pages');
           Route::get('default','Admin\SettingController@defaults');
        });

        ######################
        ### Convert Section ##
        ######################
        Route::group(['prefix'=>'video'],function (){
            Route::get('convert/{id}','VideoController@Convert');
            Route::get('copy/{id}','VideoController@copy');
            Route::get('preconvert/{id}','VideoController@preConvert');
            Route::get('convertlogo/{id}','VideoController@Convertlogo');
            Route::post('screenshot','VideoController@screenShot');
        });

        #######################
        ### Request Section ###
        #######################
        Route::group(['prefix'=>'request'],function (){
           Route::get('list','Admin\RequestController@lists');
           Route::get('delete/{id}','Admin\RequestController@delete');
           Route::get('draft/{id}','Admin\RequestController@draft');
           Route::get('publish/{id}','Admin\RequestController@publish');

           ## RECORD SECTION
            Route::group(['prefix'=>'record'],function (){
                Route::get('list','Admin\RequestController@recordList');
                Route::get('delete/{id}','Admin\RequestController@recorddelete');
                Route::get('draft/{id}','Admin\RequestController@recorddraft');
                Route::get('publish/{id}','Admin\RequestController@recordpublish');
            });

        });


        ###################
        ## Video Section ##
        ###################
        Route::get('video/stream/{id}','VideoController@streamAdmin');

    });

    Route::get('/',function (){return redirect('/admin/login');});
    Route::get('login','Admin\UserController@login');
    Route::get('logout','Admin\UserController@logout');
    Route::post('dologin','Admin\UserController@dologin');
    Route::get('remember','Admin\UserController@remember');

});

Route::group(['prefix'=>'user'],function (){
    Route::group(['middleware'=>['user','notification']],function (){


        ## Vimeo
        Route::group(['prefix'=>'vimeo'], function (){
            Route::any('download','User\VimeoController@download');
        });

        ## Update Notification Ajax
        Route::get('ajax/notification/{mode}/{notification_id}',function ($mode,$notification_id){
            global $user;
            if(setNotification($user['id'],$mode,$notification_id) == 1)
                return 1;
            else
                return 0;
        });


        Route::get('dashboard','User\UserController@dashboard');
        Route::post('security/change','User\UserController@passwordChange');

        Route::get('become','User\UserController@becomeVendor');

        ###################
        #### Trip Mode ####
        ###################
        Route::group(['prefix'=>'trip'],function (){
           Route::get('deactive','User\UserController@tripModeDeactive');
           Route::post('active','User\UserController@tripModeActive');
        });

        #################
        #### Profile ####
        #################
        Route::group(['prefix'=>'profile'],function (){
            Route::get('/','User\UserController@profile');
            Route::post('store','User\UserController@profileStore');
            Route::post('meta/store','User\UserController@profileMetaStore');
            Route::post('avatar','User\UserController@avatarChange');
            Route::post('image','User\UserController@profileImageChange');
        });

        #############
        #### Buy ####
        #############
        Route::group(['prefix'=>'video'],function (){

            Route::group(['prefix'=>'buy'],function (){
                Route::get('','User\BuyController@lists');
                Route::get('print/{id}','User\BuyController@buyPrint');
                Route::post('confirm/{id}','User\BuyController@buyConfirm');
                Route::get('rate/{id}/{rate}','User\BuyController@buyRateStore');
            });

            Route::group(['prefix'=>'off'],function (){
                Route::get('','User\BuyController@off');
                Route::get('delete/{id}','User\BuyController@offDelete');
                Route::get('edit/{id}','User\BuyController@offEdit');
                Route::post('edit/store/{id}','User\BuyController@offEditStore');
                Route::post('store','User\BuyController@offStore');
            });

            Route::group(['prefix'=>'promotion'],function (){
                Route::get('','User\BuyController@promotion');
                Route::get('buy/{id}','User\BuyController@promotionBuy');
                Route::post('buy/pay','User\BuyController@promotionPay');
                Route::any('buy/pay/verify','User\BuyController@promotionVerify');
            });

            Route::group(['prefix'=>'record'],function (){
                Route::get('','User\RecordController@lists');
                Route::get('edit/{id}','User\RecordController@edit');
                Route::get('delete/{id}','User\RecordController@delete');
                Route::post('store','User\RecordController@store');
                Route::post('edit/store/{id}','User\RecordController@editStore');
            });

            Route::group(['prefix'=>'request'],function (){
                Route::get('','User\RequestController@lists');
                Route::get('edit/{id}','User\RequestController@edit');
                Route::get('delete/{id}','User\RequestController@delete');
                Route::post('store','User\RequestController@store');
                Route::post('edit/store/{id}','User\RequestController@editStore');
                Route::post('admit','User\RequestController@admit');
            });

            Route::group(['prefix'=>'subscribe'], function(){
               Route::get('','User\BuyController@subscribeList');
            });

        });

        ## Article Section
        Route::group(['prefix'=>'article'],function (){
            Route::get('','User\ArticleController@lists');
            Route::get('list','User\ArticleController@lists');
            Route::get('new','User\ArticleController@articleNew');
            Route::get('edit/{id}','User\ArticleController@articleEdit');
            Route::post('store','User\ArticleController@store');
            Route::post('edit/store/{id}','User\ArticleController@editStore');
            Route::get('delete/{id}','User\ArticleController@delete');
        });

        #################
        #### Channel ####
        #################
        Route::group(['prefix'=>'channel'],function (){
           Route::get('','User\ChannelController@channelList');
           Route::get('new','User\ChannelController@channelNew');
           Route::post('store','User\ChannelController@channelStore');
           Route::get('delete/{id}','User\ChannelController@channelDelete');
           Route::get('edit/{id}','User\ChannelController@channelEdit');
           Route::post('edit/store/{id}','User\ChannelController@channelEditStore');

           ## Chanel Request Section
           Route::get('request/{id}','User\ChannelController@channelRequest');
           Route::post('request/store','User\ChannelController@channelRequestStore');

           ## Chanel Video Section
           Route::get('video/{id}','User\ChannelController@chanelVideo');
           Route::post('video/store/{id}','User\ChannelController@chanelVideoStore');
           Route::get('video/delete/{id}','User\ChannelController@chanelVideoDelete');
        });

        #################
        #### Content ####
        #################
        Route::group(['prefix'=>'content'],function (){
            Route::get('','User\ContentController@contentList');
            Route::get('delete/{id}','User\ContentController@contentDelete');
            Route::get('request/{id}','User\ContentController@contentRequest');
            Route::get('draft/{id}','User\ContentController@contentDraft');
            Route::get('new','User\ContentController@contentNew');
            Route::post('new/store','User\ContentController@contentStore');
            Route::get('edit/{id}','User\ContentController@contentEdit');
            Route::post('edit/store/{id}','User\ContentController@contentEditStore');
            Route::post('edit/store/request/{id}','User\ContentController@contentEditStoreRequest');
            Route::post('edit/meta/store/{id}','User\ContentController@contentMetaStore');

            Route::get('part/list/{id}','User\ContentController@contentPartList');
            Route::get('part/new/{id}','User\ContentController@contentPartNew');
            Route::get('part/delete/{id}','User\ContentController@contentPartDelete');
            Route::get('part/draft/{id}','User\ContentController@contentPartDraft');
            Route::get('part/request/{id}','User\ContentController@contentPartRequest');
            Route::get('part/edit/{id}','User\ContentController@contentPartEdit');
            Route::post('part/store','User\ContentController@contentPartStore');
            Route::post('part/edit/store/{id}','User\ContentController@contentPartEditStore');

            Route::get('part/json/{id}','User\ContentController@contentPartsJson');
        });

        #################
        #### Tickets ####
        #################
        Route::group(['prefix'=>'ticket'],function (){
            Route::get('','User\TicketController@Lists');
            Route::post('store','User\TicketController@store');
            Route::get('reply/{id}','User\TicketController@reply');
            Route::post('reply/store','User\TicketController@replyStore');
            Route::get('close/{id}','User\TicketController@close');

            ## Comment Section
            Route::get('comments','User\TicketController@comments');
            ## Notification
            Route::get('notification','User\TicketController@notifications');
            ## Support
            Route::group(['prefix'=>'support'],function (){
                Route::get('','User\TicketController@support');
                Route::get('json/{content}/{sender}','User\TicketController@supportJson');
                Route::post('jsonStore','User\TicketController@supportStore');
            });

        });

        ##############
        #### Sell ####
        ##############
        Route::group(['prefix'=>'balance'],function (){
           Route::get('sell/list','User\SellController@sellDownload');
           Route::get('','User\SellController@sellDownload');
           Route::get('sell/post','User\SellController@sellPost');
           Route::post('sell/post/setPostalCode','User\SellController@setPostalCode');
           Route::get('log','User\SellController@logs');
           Route::get('charge','User\SellController@charge');
           Route::post('charge/pay','User\SellController@chargePay');
           Route::get('report','User\SellController@report');
        });

    });
    Route::group(['middleware'=>'notification'],function (){
        Route::get('/','User\UserController@login');
        Route::get('/login','User\UserController@login');
        Route::get('/logout','User\UserController@logout');
        Route::post('/dologin','User\UserController@dologin');
        Route::get('active/{id}','User\UserController@active');
        Route::post('reset','User\UserController@reset');
        Route::get('reset/token/{token}','User\UserController@resetToken');

        Route::get('/sociliate/google','User\UserController@googleLogin');
        Route::get('/google/login','User\UserController@googleDoLogin');

        Route::get('/register','User\UserController@register');
        Route::post('/doregister','User\UserController@doregister');

        ## Register Steps ##
        Route::get('steps/one/{phone}','User\UserController@registerStepOne');
        Route::get('steps/two/{phone}/{code}','User\UserController@registerStepTwo');
        Route::get('steps/two/repeat/{phone}','User\UserController@registerStepTwoRepeat');
        Route::any('steps/three/{phone}/{code}','User\UserController@registerStepThree');
    });
});

Route::group(['middleware'=>'notification'],function (){

    Route::get('/','ContentController@main');
    Route::get('category/{id}','ContentController@category');
    Route::get('category','ContentController@category');
    Route::get('search','ContentController@search');
    Route::get('jsonsearch','ContentController@jsonSearch');


    ## Blog Section ##
    Route::group(['prefix'=>'blog'],function (){
       Route::get('/','ContentController@blog');
       Route::get('post/{id}','ContentController@blogPost');
       Route::get('mobile/{id}','ContentController@blogPostMobile');
       Route::get('category/{id}','ContentController@blogCategory');
       Route::post('post/comment/store','ContentController@blogPostCommentStore');
       Route::get('tag/{key}','ContentController@blogTags');
    });

    ## Page Section ##
    Route::group(['prefix'=>'page'],function (){
        Route::get('{key}','ContentController@page');
    });

    ## Gift & Off
    Route::get('gift/{code}','ContentController@giftCheker');

    ### Product Section ###
    Route::group(['prefix'=>'product'],function (){
        Route::get('{id}','ContentController@product');
        Route::get('part/{id}/{pid}','ContentController@productPart');

        ## Comment & Support
        Route::post('comment/store/{id}','ContentController@productCommentStore');
        Route::post('support/store','ContentController@productSupportStore');
        Route::get('support/rate/{id}/{rate}','ContentController@productSupportRate');

       ## Favorite ##
        Route::get('fav/{id}','ContentController@productFav');
        Route::get('unfav/{id}','ContentController@productUnFav');
        Route::get('{id}/rate/{rate}','ContentController@productRate');

        ## Subscribe ##
        Route::get('subscribe/{id}/{type}/{payMode}','ContentController@productSubscribe');
    });

    ## Chanel Section
    Route::group(['prefix'=>'chanel'],function (){
        Route::get('{username}','ContentController@chanel');
        Route::get('follow/{id}','ContentController@chanelFollow');
        Route::get('unfollow/{id}','ContentController@chanelUnFollow');
    });

    ## Article Section
    Route::group(['prefix'=>'article'],function (){
        Route::get('/list','ContentController@articleList');
        Route::get('item/{id}','ContentController@articleItem');
    });

    ### Profile Section ###
    Route::get('follow/{id}','User\UserController@follow');
    Route::get('unfollow/{id}','User\UserController@unfollow');
    Route::get('profile/{id}','User\UserController@profileView');
    Route::post('profile/request/store','User\UserController@profileRequestStore');


    ### Bank Section ###
    Route::group(['prefix'=>'bank'],function (){

        Route::group(['prefix'=>'paypal'], function () {
            Route::get('pay/{id}/{type}','Admin\PayController@paypalPay');
            Route::any('status','Admin\PayController@paypalStatus');
            Route::any('cancel/{id}','Admin\PayController@paypalCancel');
        });

        Route::group(['prefix'=>'paystack'], function () {
            Route::get('pay/{id}/{type}','Admin\PayController@paystackPay');
            Route::any('status/{id}','Admin\PayController@paystackStatus');
            Route::any('cancel/{id}','Admin\PayController@paystackCancel');
        });

        Route::group(['prefix'=>'paytm'], function () {
            Route::get('pay/{id}/{type}','Admin\PayController@paytmPay');
            Route::any('status/{product_id}','Admin\PayController@paytmStatus');
            Route::any('cancel/{id}','Admin\PayController@paytmCancel');
        });

        Route::group(['prefix'=>'payu'], function () {
            Route::get('pay/{id}/{type}','Admin\PayController@payuPay');
            Route::any('status/{product_id}','Admin\PayController@payuStatus');
            Route::any('cancel/{id}','Admin\PayController@payuCancel');
        });

        Route::get('/credit/pay/{id}/{mode}','Admin\PayController@creditPay');
    });

    ### Request Section ###
    Route::group(['prefix'=>'request'],function (){
       Route::get('','ContentController@request');
       Route::get('new','ContentController@requestNew');
       Route::post('store','ContentController@requestStore');
       Route::get('follow/{id}','ContentController@requestFollow');
       Route::get('unfollow/{id}','ContentController@requestUnFollow');
       Route::get('suggestion/{id}/{suggest}','ContentController@requestSuggestion');
    });

    ### Record Section ###
    Route::group(['prefix'=>'record'],function (){
       Route::get('','ContentController@record');
       Route::get('follow/{id}','ContentController@recordFollow');
       Route::get('unfollow/{id}','ContentController@recordUnFollow');
    });

    ## Video Section ##
    Route::group(['prefix'=>'video'],function (){
        Route::get('stream/{id}','VideoController@stream');
        Route::get('download/{id}','VideoController@download');
    });
});

## Usage
Route::get('usage/{product}/{user}','ContentController@usage');

## Login
Route::get('login/{user}','ContentController@login');

## Get Progress OF Covert
Route::get('/progress','VideoController@progress');

Route::any('payment/wallet/status','PaymentController@walletStatus');
