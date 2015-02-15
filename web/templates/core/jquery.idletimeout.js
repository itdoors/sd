(function($, win){
	
	var idleTimeout = {
		init: function( element, resume, options ){
			var self = this, elem;

			this.warning = elem = $(element);
			this.resume = $(resume);
			this.options = options;
			this.countdownOpen = false;
			this.failedRequests = options.failedRequests;
			this._startTimer();
      		this.title = document.title;
      		
			// expose obj to data cache so peeps can call internal methods
			$.data( elem[0], 'idletimeout', this );
			
			// start the idle timer
			$.idleTimer(options.idleAfter * 1000);
			
			// once the user becomes idle
			$(document).bind("idle.idleTimer", function(){
//		        console.warn('idle.idleTimer event');
		        
				// if the user is idle and a countdown isn't already running
				if( $.data(document, 'idleTimer') === 'idle' && !self.countdownOpen){
					self._stopTimer();
					self.countdownOpen = true;
					localStorage.setItem('countdownOpen', true)
					self._idle();
				}
			});
			
			$(window).bind('storage', function() {
				if (localStorage.getItem('idleTimerLoggedOut') === 'true'){
					options.onTimeout.call(this.warning);
				}
				self.countdownOpen = localStorage.getItem('countdownOpen') === 'true';
				$.data(document,'idleTimer',"active");
//				console.warn('storage event');
            });
			
			// bind continue link
			this.resume.bind("click", function(e){
				e.preventDefault();
//				console.log('resume onClick');
				
				win.clearInterval(self.countdown); // stop the countdown
				
				self.countdownOpen = false;
				localStorage.setItem('countdownOpen', false);
				$.data(document,'idleTimer',"active");
				
				self._startTimer(); // start up the timer again
				self._keepAlive( false ); // ping server
				options.onResume.call( self.warning ); // call the resume callback
			});
		},
		
		_idle: function(){
			var self = this,
				options = this.options,
				warning = this.warning[0],
				counter = options.warningLength;
			
//			console.warn('_idle');
			// fire the onIdle function
			options.onIdle.call(warning);
			
			// set inital value in the countdown placeholder
			options.onCountdown.call(warning, counter);
			
			// create a timer that runs every second
			this.countdown = win.setInterval(function(){
				if(--counter === 0){
					window.clearInterval(self.countdown);//debugger;
					localStorage.setItem('countdownOpen', false);
					options.onTimeout.call(warning);
					localStorage.setItem('idleTimerLoggedOut', true);
				} else {
					options.onCountdown.call(warning, counter);
					document.title = options.titleMessage.replace('%s', counter);
				}
			}, 1000);
		},
		
		_startTimer: function(){
			var self = this;
			localStorage.setItem('idleTimerLastActivity', +new Date);
			this.timer = win.setTimeout(function(){
				self._keepAlive();
			}, this.options.pollingInterval * 1000);
//			console.info('_startTimer: ' + this.timer);
		},
		
		_stopTimer: function(){
			// reset the failed requests counter
			this.failedRequests = this.options.failedRequests;
			win.clearTimeout(this.timer);
//			console.info('_stopTimer: ' + this.timer);
		},
		
		_keepAlive: function( recurse ){
			var self = this,
				options = this.options;
				
			//Reset the title to what it was.
			document.title = self.title;
			
			// assume a startTimer/keepAlive loop unless told otherwise
			if( typeof recurse === "undefined" ){
				recurse = true;
			}
			
			// if too many requests failed, abort
			if( !this.failedRequests ){
				this._stopTimer();
				options.onAbort.call( this.warning[0] );
				return;
			}
			
//			console.log('_keepAlive ajax');
			$.ajax({
				timeout: options.AJAXTimeout,
				url: options.keepAliveURL,
				error: function(){
					self.failedRequests--;
				},
				success: function(response){
					if($.trim(response) !== options.serverResponseEquals){
						self.failedRequests--;
					}
				},
				complete: function(){
					if( recurse ){
						self._startTimer();
					}
//					console.log('_keepAlive complete');
				}
			});
		}
	};
	
	// expose
	$.idleTimeout = function(element, resume, options){
		idleTimeout.init( element, resume, $.extend($.idleTimeout.options, options) );
		return this;
	};
	
	// options
	$.idleTimeout.options = {
		// number of seconds after user is idle to show the warning
		warningLength: 30,
		
		// url to call to keep the session alive while the user is active
		keepAliveURL: "",
		
		// the response from keepAliveURL must equal this text:
		serverResponseEquals: "OK",
		
		// user is considered idle after this many seconds.  10 minutes default
		idleAfter: 600,
		
		// a polling request will be sent to the server every X seconds
		pollingInterval: 60,
		
		// number of failed polling requests until we abort this script
		failedRequests: 5,
		
		// the $.ajax timeout in MILLISECONDS!
		AJAXTimeout: 250,
		
		// %s will be replaced by the counter value
    	titleMessage: 'Warning: %s seconds until log out | ',
		
		/*
			Callbacks
			"this" refers to the element found by the first selector passed to $.idleTimeout.
		*/
		// callback to fire when the session times out
		onTimeout: $.noop,
		
		// fires when the user becomes idle
		onIdle: $.noop,
		
		// fires during each second of warningLength
		onCountdown: $.noop,
		
		// fires when the user resumes the session
		onResume: $.noop,
		
		// callback to fire when the script is aborted due to too many failed requests
		onAbort: $.noop
	};
	
})(jQuery, window);