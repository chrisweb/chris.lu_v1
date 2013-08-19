/**
 * application.js
 * 
 */
define(['jquery', 'chris.library', 'blocksit', 'prettify', 'jquery.hoverdir'], function(jQuery, library) {

    /**
     * 
     * on window resize call the modifyClasses method
     * 
     * @returns {undefined}
     */
    var cssModifier = function() {

        library.log('# cssModifier');

        var pageDom = $('.ui-page-active');

        var pageWidth = pageDom.width();

        var mainDom = pageDom.find('div#main');
        var complementaryDom = pageDom.find('aside#right-side');

        library.log(pageWidth);

        modifyClasses(mainDom, complementaryDom, pageWidth);

        $(window).resize(function() {

            pageWidth = $('.ui-page-active').width();

            library.log(pageWidth);

            modifyClasses(mainDom, complementaryDom, pageWidth);

        });

    };

    /**
     * 
     * responsive design helper function that adds or removes classes depending
     * on the screen width of the user
     * 
     * @param {type} mainDom
     * @param {type} complementaryDom
     * @param {type} pageWidth
     * @returns {undefined}
     */
    var modifyClasses = function(mainDom, complementaryDom, pageWidth) {

        library.log('responsive classes ...');

        library.log(mainDom);
        library.log(complementaryDom);

        if (pageWidth <= 767) {

            if (mainDom.hasClass('col-12')) {

                mainDom.removeClass('col-12 col-sm-8 col-lg-8');

            }

            if (complementaryDom.hasClass('col-6')) {

                complementaryDom.removeClass('col-6 col-sm-4 col-lg-4');

            }

        } else {

            if (!mainDom.hasClass('col-12')) {

                mainDom.addClass('col-12 col-sm-8 col-lg-8');

            }

            if (!complementaryDom.hasClass('col-6')) {

                complementaryDom.addClass('col-6 col-sm-4 col-lg-4');

            }

        }

    };

    /**
     * 
     * initialize the readinglist
     * use blocksit to create a responsive boxes wall
     * 
     * @param {type} contextId
     * @returns {undefined}
     */
    var initializeReadinglist = function(contextId) {

        library.log('# initializeReadinglist');

        var containerClass = 'readinglist_container';

        //library.log('***** container ******');
        //library.log($('#' + contextId + ' .' + containerClass));
        //library.log('***********');

        var containerWidth = $('#' + contextId + ' .' + containerClass).width();

        library.log('containerWidth: ' + containerWidth);

        var numberOfColumns = 3;

        if (containerWidth < 727)
            numberOfColumns = 2;
        if (containerWidth < 654)
            numberOfColumns = 1;

        $('#' + contextId + ' .' + containerClass).BlocksIt({
            numOfCol: numberOfColumns,
            offsetX: 0,
            offsetY: 0,
            blockElement: '.readinglist_box'
        });

        library.log('blocksit got intialized');

        if (!library.isTouchDevice()) {

            $('.' + containerClass + ' > .readinglist_box').each(function() {

                $(this).hoverdir({
                    hoverDelay: 5
                });

            });

            library.log('hoverdir got intialized');

        }

    };

    /**
     * 
     * bookmarks page
     * create a jquery mobile listview wih autodividers and filter
     * 
     * @param {type} contextId
     * @returns {undefined}
     */
    var initializeBookmarks = function(contextId) {

        library.log('# initializeBookmarks');

        var containerClass = 'readinglist_container';
        
        var core = $('#core');

        core.on('tap', 'a:not(#bookmarks_back)', function(event) {

            event.preventDefault();
            
            var tagKey = $(this).attr('data-chris-tag-key');

            var request = $.ajax({
                url: $(this).attr('href') + '?format=json',
                type: 'GET',
                dataType: 'json'
            });

            request.done(function(data) {

                library.log(data);

                if ($.type(data.results) === 'array' && data.results.length > 0) {

                    window.scrollTo(0, 0);
                    
                    var core = $('section#core');

                    core.css('overflow', 'hidden');
                    core.css('min-height', core.height());

                    var resultsHtml = '';

                    $.each(data.results, function(index, value) {
                        
                        resultsHtml += '<li>';
                        resultsHtml += '<a href="' + value.url + '" title="' + value.title + '">';
                        resultsHtml += value.title + '<br>' + value.url;
                        resultsHtml += '</a>';
                        resultsHtml += '</li>';
                        
                    });

                    var newPane = $('<div id="bookmarks_list"></div>');
                    
                    var backButton = '<a class="btn btn-primary ui-link" href="/mybookmarks" id="bookmarks_back">BACK</a>';
                    
                    newPane.append(backButton);
                    
                    var bookmarksListing = $('<ul id="boomarks_' + tagKey + '" data-role="listview" data-autodividers="true" data-inset="true">' + resultsHtml + '</ul>');
                    
                    newPane.append(bookmarksListing);

                    var bookmarksTagsListing = $('#tags_list');
                    
                    bookmarksTagsListing.css('width', bookmarksTagsListing.width());
                    
                    newPane.css('width', bookmarksTagsListing.width());
                    
                    var bookmarksContainer = core.find('#bookmarks_container');

                    bookmarksContainer.css('width', (parseInt(core.width()) * 2) + 500);

                    bookmarksTagsListing.after(newPane);
                    
                    bookmarksTagsListing.css('float', 'left');
                    newPane.css('float', 'left');
                    
                    // tell jquery mobile to intialize the new listview
                    bookmarksListing.listview().trigger('create');

                    bookmarksTagsListing.animate({ width: 'toggle' }, 300, function() {
                        
                        
                        
                    });

                }

            });

            request.fail(function(jqXHR, textStatus) {

                library.log('bookmarks request failed: ' + textStatus);

            });

        });
        
        core.on('tap', 'a#bookmarks_back', function(event) {
            
            event.preventDefault();
            
            library.log('#bookmarks_list click BACK');
            
            window.scrollTo(0, 0);
            
            var bookmarksTagsListing = $('#tags_list');
            
            bookmarksTagsListing.find('#bookmarks').find('li').find('a').removeClass($.mobile.activeBtnClass);
            
            bookmarksTagsListing.animate({ width: 'toggle' }, 300, function() {
                
                var bookmarksList = $('#bookmarks_list');
                
                bookmarksList.remove();
                        
            });
            
        });

    };

    /**
     * 
     * load the facebook sdk on article read pages for the like button
     * 
     * @returns {undefined}
     */
    var loadFacebookSDK = function() {

        library.log('# loadFacebookSDK');

        window.fbAsyncInit = function() {
            FB.init({
                appId: '424957510901747', // App ID";
                status: true, // check login status
                cookie: true, // enable cookies to allow the server to access the session
                xfbml: true  // parse XFBML
            });
            FB.XFBML.parse();
        };

        // Load the SDK Asynchronously
        (function(d) {
            var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement('script');
            js.id = id;
            js.async = true;
            js.src = '//connect.facebook.net/en_US/all.js';
            ref.parentNode.insertBefore(js, ref);
        }(document));

    };

    /**
     * 
     * initialize google analytics
     * is off on admin pages
     * record page view on history api url change by using the location hash
     * 
     * @returns {undefined}
     */
    var initializeGoogleAnalytics = function() {

        library.log('# initializeGoogleAnalytics 1');

        // using typeof instead of $.type, because $.type triggers undefined
        // error, while this is what we want to check
        if (typeof(_gaq) !== 'undefined') {

            _gaq.push(['_setAccount', 'UA-16705563-1']);

            hash = location.hash;

            library.log('found hash: ' + hash);

            if (hash) {
                _gaq.push(['_trackPageview', hash.substr(1)]);
            } else {
                _gaq.push(['_trackPageview']);
            }

        }

    };

    /**
     * 
     * admin helper that request a a page read from server based on a given url
     * 
     * @returns {undefined}
     */
    var autoPopulateReadinglistForm = function() {

        library.log('# autoPopulateReadinglistForm');

        var articleUrl = $('#url').val();

        library.log(articleUrl);

        // empty the messages box before very new request
        $('#alert_box').empty();

        if (articleUrl) {

            $.post('/readinglist/admin/ajax/getwebsitedata?format=json',
                    {
                        'articleurl': articleUrl
                    },
            function(data) {

                library.log(data.websiteData, 'data.websiteData');
                library.log(data.websiteData['description'], 'description');
                library.log(data.websiteData['title'], 'title');
                library.log(data.websiteData['favicon'], 'favicon');
                library.log(data.websiteData['image'], 'image');
                library.log(data.websiteData['domain'], 'domain');

                if (!data.websiteData['error']) {

                    $('#title').val(data.websiteData['title']);
                    $('#headline').val(data.websiteData['description']);
                    $('#imageUrl').val(data.websiteData['image']);
                    $('#favicon').val(data.websiteData['favicon']);
                    $('#domain').val(data.websiteData['domain']);

                } else {

                    var alert = $('<div>', {
                        class: 'alert alert-error',
                        text: data.websiteData['error']
                    });

                    // display error message above form
                    // in twitter boostrap info box
                    $('#alert_box').html(alert);

                }

            },
                    'json'
                    );

        } else {

            var alert = $('<div>', {
                class: 'alert alert-error',
                text: 'No URL given'
            });

            $('#alert_box').html(alert);

        }

    };

    return {
        /**
         * export these methods only
         */
        initializeApp: function() {

            // nothing here yet
            library.log('app got intialized');

        },
        initializeReadinglist: initializeReadinglist,
        initializeBookmarks: initializeBookmarks,
        loadFacebookSDK: loadFacebookSDK,
        initializeGoogleAnalytics: initializeGoogleAnalytics,
        autoPopulateReadinglistForm: autoPopulateReadinglistForm,
        cssModifier: cssModifier

    };

});