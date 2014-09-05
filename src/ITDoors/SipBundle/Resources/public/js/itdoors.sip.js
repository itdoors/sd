var ITDoorsSip = (function() {

    var defaults = {
        io: {
            host: '',
            port: ''
        },
        sip: {
            host: '',
            portUdp: '',
            portWs: ''
        },
        audio: {
            id: 'audio-remote',
            sounds: {
                ringtoneSrc: '',
                ringbacktoneSrc: '',
                dtmfToneSrc: ''
            }
        }
    };

    function ITDoorsSip() {
        this.params = {};
        this.modelName = "contact";
        this.modelId = null;
        this.callSession = null;
        this.stack = null;
        this.ringbacktone = null;
        this.ringtone = null;
        this.phone = '';
        this.callbackHangup = null;
        this.uniqueId = null;
    };

    ITDoorsSip.prototype.init = function(options)
    {
        this.params = $.extend(defaults, options);
    };

    ITDoorsSip.prototype.initIO = function(peerId, modelName, modelId)
    {
        var self = this;

        this.modelName = modelName;
        this.modelId = modelId;

        if (self.params.io.host && self.params.io.port && io) {
            var socket = io(self.params.io.host + ':' + self.params.io.port);

            socket.on('ami_' + peerId, function(clientGet) {

                if (!clientGet.event) {
                    return;
                }

                self.uniqueId = clientGet.uniqueId;

                console.log('AMI ', clientGet);

                switch (clientGet.event) {
                    case "Dial":
                        socket.emit('ami', {
                            modelName: self.modelName,
                            modelId: self.modelId,
                            uniqueId: clientGet.uniqueId
                        });
                        break;
                    case "Hangup":
                        break;
                }
            });
        }
    };

    ITDoorsSip.prototype.getUniqueId = function()
    {
        if (!window['ITDoorsSip']) {
            return null;
        }

        if (!window['ITDoorsSip'].uniqueId) {
            return null;
        }

        return window['ITDoorsSip'].uniqueId;
    };

    ITDoorsSip.prototype.initAudioTag = function()
    {
        var html = '';

        if (!document.getElementById(this.params.audio.id)) {
            html += '<audio id="'+ this.params.audio.id + '" autoplay="autoplay" />';
        }

        if (!document.getElementById('ringtone')) {
            html += '<audio id="ringtone" loop src="' + this.params.audio.sounds.ringtoneSrc + '" />';
        }

        if (!document.getElementById('ringbacktone')) {
            html += '<audio id="ringbacktone" loop src="' + this.params.audio.sounds.ringbacktoneSrc + '" />';
        }

        if (!document.getElementById('ringbacktone')) {
            html += '<audio id="dtmfTone" loop src="' + this.params.audio.sounds.dtmfToneSrc + '" />';
        }

        if (!document.getElementById('video-remote')) {
            html += '<video id="video-remote" class="video" height="100%" width="100%" style="opacity: 0; background-color: #000000; -webkit-transition-property: opacity; -webkit-transition-duration: 2s;" autoplay="autoplay"> </video>';
        }

        if (!document.getElementById('video-local')) {
            html += '<video id="video-local" class="video" height="72px" width="88px" style="opacity: 0; margin-top: -80px; margin-left: 5px; background-color: #000000; -webkit-transition-property: opacity; -webkit-transition-duration: 2s;" muted="true" autoplay="autoplay"> </video>';
        }

        $('body').append(html);

        this.ringtone = document.getElementById('ringtone');
        this.ringbacktone = document.getElementById('ringbacktone');
    };

    ITDoorsSip.prototype.startRingTone = function()
    {
        try { this.ringtone.play(); }
        catch (e) { }
    };

    ITDoorsSip.prototype.stopRingTone = function()
    {
        try { this.ringtone.pause(); }
        catch (e) { }
    };

    ITDoorsSip.prototype.startRingbackTone = function()
    {
        try { this.ringbacktone.play(); }
        catch (e) { }
    };

    ITDoorsSip.prototype.stopRingbackTone = function()
    {
        try { this.ringbacktone.pause(); }
        catch (e) { }
    };

    ITDoorsSip.prototype.onEventFired = function(e)
    {
        switch (e.type) {
            case "started" :
                console.log('ITDoorsSip.prototype.onEventFired STARTED');
                try {
                    // LogIn (REGISTER) as soon as the stack finish starting
                    oSipSessionRegister = this.newSession('register', {
                        expires: 200,
                        events_listener: { events: '*', listener: window['ITDoorsSip']['onSipEventSession'] },
                        sip_caps: [
                            { name: '+g.oma.sip-im', value: null },
                            { name: '+audio', value: null },
                            { name: 'language', value: '\"en,fr\"' }
                        ]
                    });
                    oSipSessionRegister.register();

                    window['ITDoorsSip']['callSession'] = window['ITDoorsSip']['stack'].newSession('call-audio', {
                        video_local: document.getElementById('video-local'),
                        video_remote: document.getElementById('video-remote'),
                        audio_remote: document.getElementById(window['ITDoorsSip'].params.audio.id),
                        bandwidth: { audio:undefined, video:undefined },
                        video_size: { minWidth:undefined, minHeight:undefined, maxWidth:undefined, maxHeight:undefined },
                        events_listener: { events: '*', listener: window['ITDoorsSip']['onSipEventSession'] },
                        sip_caps: [
                            { name: '+g.oma.sip-im' },
                            { name: '+sip.ice' },
                            { name: 'language', value: '\"en,fr\"' }
                        ]
                    });

                    console.log(window['ITDoorsSip']['callSession']);

                    window['ITDoorsSip']['callSession'].call(window['ITDoorsSip']['phone'].replace(/ /g, "").replace(/-/g, ""));
                }
                catch (e) {
                    console.log('ITDoorsSip.prototype.onEventFired ERROR', e);
                }
                break;
            case "m_permission_accepted":
                //stopRingTone();
                break;
        }
    };

    ITDoorsSip.prototype.onSipEventSession = function(e) {

        switch (e.type) {
            case 'connecting': case 'connected':
            {
                window['ITDoorsSip']['stopRingbackTone']();
                window['ITDoorsSip']['stopRingTone']();
                //ITDoorsSip.stopRingTone();

                break;
            } // 'connecting' | 'connected'
            case 'i_ao_request':
            {
                window['ITDoorsSip']['startRingbackTone']();

                break;
            } // i_ao_request
            case 'terminating': case 'terminated':
            {
                window['ITDoorsSip']['stopRingbackTone']();
                window['ITDoorsSip']['stopRingTone']();

                if (window['ITDoorsSip']['callbackHangup']) {
                    window['ITDoorsSip']['callbackHangup'](window['ITDoorsSip'].getUniqueId());
                }

                window['ITDoorsSip']['uniqueId'] = null;
                window['ITDoorsSip']['phone'] = null;
                window['ITDoorsSip']['callbackHangup'] = null;
                window['ITDoorsSip']['callSession'] = null;
                break;
            }
        }
    };

    ITDoorsSip.prototype.initSIP = function(peerId, peerPassword, phone, callbackHangup)
    {
        this.initAudioTag();

        this.phone = phone;

        if (callbackHangup) {
            this.callbackHangup = callbackHangup;
        }

        var self = this;

        if (!SIPml) {
            return;
        }

        $(document).ready(function(){

            SIPml.init(
                function(e){
                    self.stack =  new SIPml.Stack({
                        //realm: '95.67.67.170',
                        realm: self.params.sip.host,
                        impi: peerId,
                        //impu: 'sip:8005@95.67.67.170',
                        impu: 'sip:' + peerId + '@' + self.params.sip.host,
                        password: peerPassword,
                        websocket_proxy_url: 'ws://' + self.params.sip.host + ':' + self.params.sip.portWs + '/ws', // optional
                        outbound_proxy_url: 'udp://' + self.params.sip.host + ':' + self.params.sip.portUdp, // optional
                        ice_servers: null,
                        enable_early_ims: true,
                        enable_rtcweb_breaker: true, // optional
                        enable_media_stream_cache: false,
                        bandwidth: null,
                        video_size: null,
                        sip_headers: [
                            { name: 'User-Agent', value: 'IM-client/OMA1.0 sipML5-v1.2014.04.18' },
                            { name: 'Organization', value: 'Doubango Telecom' }
                        ],
                        events_listener: { events: '*', listener: self.onEventFired }
                    });

                    self.stack.start();
                }
            );

            $('.hangup-btn').live('click', function(e) {

                e.preventDefault();
                if (self.callSession) {
                    self.callSession.hangup({events_listener: { events: '*', listener: self.onSipEventSession }});
                    $('.hangup-btn').hide();
                }
            });
        });
    };

    return new ITDoorsSip();
})();