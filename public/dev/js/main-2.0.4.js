require.config({
    
    baseUrl: '/dev',

    paths: {
        'jquery' : './vendor/jQuery/jquery-2.0.3',
        'jquery.mobile' : './vendor/jQuery_mobile/jquery.mobile-1.4.0-alpha.1',
        'chris.application' : './js/chris.application-2.0.7',
        'chris.library' : './js/chris.library-1.0.7',
        'chris.configuration' : './js/chris.configuration-1.0.4',
        'blocksit': './vendor/blocksit/blocksit',
        'jquery.hoverdir': './vendor/DirectionAwareHoverEffect/js/jquery.hoverdir',
        'prettify' : './vendor/google-code-prettify/prettify',
        'collapse' : './vendor/bootstrap/js/collapse',
        'modernizr' : './vendor/DirectionAwareHoverEffect/js/modernizr.custom.97074'
    },
    
    shim: {
        'blocksit': {
            deps: ['jquery']
        },
        'collapse': {
            deps: ['jquery']
        },
        'modernizr': {
            export: 'Modernizr'
        },
        'jquery.hoverdir': {
            deps: ['modernizr']
        }
    }

});

require(['jquery', 'chris.application', 'chris.library', 'collapse'], function($, application, library) {

    library.log('jquery got loaded');
	
    $(document).on('mobileinit', function(event, ui) {
	
        library.log('mobileinit event');
		
        $.mobile.page.prototype.options.domCache = true;
	
    });
	
    $(document).on('pagecreate','[data-role=page]', function(event, ui) {
	
        library.log('on pagecreate of role page');
	  
    });
	
    $(document).on('pageinit','[data-role=page]', function(event, ui) {
	
        library.log('on pageinit of role page');
	  
    });
	
    $(document).on('pagebeforeshow','[data-role=page]', function(event, ui) {
	
        library.log('on pagebeforeshow of role page');
	  
    });
	
    // add event listeners before loading jquery mobile
    $(document).on('pageshow', '[data-role=page]', function(event, ui) {
	
        library.log('on pageshow of role page');
		
        var context = $.mobile.activePage;
		
        library.log('context: ');
        library.log(context);

        if ($.type(context) !== 'undefined') {
		
            contextId = context.attr('id');
			
            library.log('contextId: ' + contextId);
            
            // bookmarks page
            if (contextId.substr(0, 13) === 'bookmarkindex') {
                
                try {

                    application.initializeBookmarks();

                } catch(error) {
				
                    library.log('bookmarks page error: ' + error);
				
                }
                
            }

            // code google pretty print
            if (contextId.substr(0, 16) === 'articleindexread' || contextId.substr(0, 15) === 'articleindextag' || contextId === 'default') {
			
                try {
				
                    prettyPrint();
					
                } catch(error) {
				
                    library.log('google prettify error: ' + error);
				
                }
				
            }
			
            if (contextId.substr(0, 16) === 'articleindexread') {
			
                try {

                    application.loadFacebookSDK();

                } catch(error) {
				
                    library.log('facebook like error: ' + error);
				
                }
				
            }

            // readinglist intialization
            if (contextId.substr(0, 16) === 'readinglistindex') {
				
                try {
				
                    application.initializeReadinglist(contextId);
					
                } catch(error) {
			
                    library.log('jquery blocksit error: ' + error);
			
                }
				
                library.log('window resize listener');
				
                // listen for windows resize events
                $(window).resize(function() {

                    // readinglist initialization
                    try {

                        application.initializeReadinglist(contextId);

                    } catch(error) {

                        library.log('jquery masonery error: ' + error);

                    }
				
                });
			
            }
			
            if (contextId.substr(0, 22) === 'readinglistadminmanage') {			
						
                library.log('autoPopulateReadinglistFormButton listener');
						
                // readinglist admin ajax information loader
                $('#autoPopulateReadinglistFormButton').on('click', function(event) {

                    application.autoPopulateReadinglistForm();

                });
				
            }	
			
            // google analytics for ajax pages
            // http://www.jongales.com/blog/2011/01/10/google-analytics-and-jquery-mobile/
            try {
			
                application.initializeGoogleAnalytics();
                
                application.cssModifier();
				
            } catch(error) {
			
                library.log('google analytics error: ' + error);
				
            }
			
        } else {
		
            library.log('context undefined');
		
        }
	
    });
	
    // only call jquery mobile after intializing event listeners
    require(['jquery.mobile'], function() {
	
        library.log('jquery mobile got loaded');
		
    });

});