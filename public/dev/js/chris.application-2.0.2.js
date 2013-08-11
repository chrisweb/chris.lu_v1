/**
 * 
 */
define(['jquery', 'chris.library', 'blocksit', 'prettify', 'jquery.hoverdir'], function(jQuery, library) {

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
		
        if (containerWidth < 727) numberOfColumns = 2;
        if (containerWidth < 654) numberOfColumns = 1;

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
     * @returns {undefined}
     */
    var loadFacebookSDK = function() {
	
        library.log('# loadFacebookSDK');
	
        window.fbAsyncInit = function() {
            FB.init({
                appId      : '424957510901747', // App ID";
                status     : true, // check login status
                cookie     : true, // enable cookies to allow the server to access the session
                xfbml      : true  // parse XFBML
            });
            FB.XFBML.parse();
        };

        // Load the SDK Asynchronously
        (function(d){
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
         * 
         */
        initializeApp: function() {
                
            // nothing here yet
            library.log('app got intialized');
	
        },
        initializeReadinglist: initializeReadinglist,
        loadFacebookSDK: loadFacebookSDK,
        initializeGoogleAnalytics: initializeGoogleAnalytics,
        autoPopulateReadinglistForm: autoPopulateReadinglistForm,
		cssModifier: cssModifier
        
    };
	
});