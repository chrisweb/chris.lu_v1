/**
 * 
 */
define(['jquery', 'chris.configuration'], function($, configuration) {

    var currentlyActivePage;

    return {

        /**
         * 
         */
        setActivePage: function(activePage) {

            currentlyActivePage = activePage;

        },

        /**
         * 
         */
        getActivePage: function() {

            if (currentlyActivePage === undefined) {

                var applicationActivePage = 'homepage';

            } else {

                var applicationActivePage = currentlyActivePage;

            }

            return applicationActivePage;

        },

        /**
         * 
         */
        writeInDebuggerDiv: function(logInformations, logMessage) {

            var debuggerOutputContent = $('#' + this.getActivePage() + ' #debuggerOutput').html();

            if (logMessage === undefined) { 

                debuggerOutputContent += logInformations + '<br />';

            } else {

                debuggerOutputContent += logMessage + ': ' + logInformations + '<br />';

            }

            $('#' + this.getActivePage() + ' #debuggerOutput').html(debuggerOutputContent);

        },

        /**
         * 
         */
        writeInConsoleLog: function(logInformations, logMessage) {

            if (typeof console != 'undefined') {

                if (logMessage === undefined) { 

                    console.log(logInformations);

                } else {

                    console.log(logInformations, logMessage);

                }

            }

        },
		
		isTouchDevice: function() {

			return !!('ontouchstart' in window) // works on most browsers 
				|| !!('onmsgesturechange' in window); // works on ie10

		},

        /**
         * 
         */
        log: function(logInformations, logMessage) {

            if (configuration.enableLoggingInDebuggerDiv == true) {

                this.writeInDebuggerDiv(logInformations, logMessage);

            }

            if (configuration.enableLoggingInConsoleLog == true) {

                this.writeInConsoleLog(logInformations, logMessage);

            }

        }

    }
		
});