<!-- Scripts -->
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script type="text/javascript" src="/js/jquery.fancybox.pack.js"></script>

<script type="text/javascript" src="/js/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="/js/toastr/toastr.min.js"></script>
<!--<script src="/js/RecordRTC.js"></script>-->


<!-- for Edige/FF/Chrome/Opera/etc. getUserMedia support -->
<!--<script src="/js/gumadapter.js"></script>-->

<script src="/js/adapter.js"></script>

<script src="/js/device.min.js"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var REC = {};
    var RECo = {};
    var mediaSource = {};
    var mediaRecorder;
    var recordedBlobs;
    var sourceBuffer;
    var modalWindowFlag = '0';
    var answerForQuestionId = '0';
    recordingDIV = null;
    var SPEAK = "{{trans('messages.speak')}}";
    var SHUTUP = "{{trans('messages.shut-up')}}";
    var START = "{{trans('messages.start-recording')}}";
    var RECORDING = "{{trans('messages.recording')}}";

    var ERROR = "{{trans('messages.error')}}";
    var WARNING = "{{trans('messages.warning')}}";
    var SUCCESS = "{{trans('messages.success')}}";
    var PROMPT = "{{trans('messages.prompt')}}";
    var EnterTitleQ = "{{trans('messages.enter-title-q')}}";
    var WrongData = "{{trans('messages.wrong-data')}}";
    var EnterTitleA = "{{trans('messages.enter-title-a')}}";
    var WindowsBrowserWarning = "{{trans('messages.win-browser')}}";
    var AndroidBrowserWarning = "{{trans('messages.android-browser')}}";
    var CameraAndMicrophoneWarning = "{{trans('messages.cam-and-mic')}}";

    recordingPlayer = null;

    mediaRecorder = null;
    flagAnswer = 0;
    flagLoadOne = false;
    (function (factory) {
        if (typeof define === 'function' && define.amd) {
// AMD. Register as an anonymous module.
            define(['jquery'], function ($) {
                return factory($);
            });
        } else if (typeof module === 'object' && typeof module.exports === 'object') {
// Node-like environment
            module.exports = factory(require('jquery'));
        } else {
// Browser globals
            factory(window.jQuery);
        }
    }(function (jQuery) {
        "use strict";

        function uaMatch(ua) {
// If an UA is not provided, default to the current browser UA.
            if (ua === undefined) {
                ua = window.navigator.userAgent;
            }
            ua = ua.toLowerCase();

            var match = /(edge)\/([\w.]+)/.exec(ua) ||
                    /(opr)[\/]([\w.]+)/.exec(ua) ||
                    /(chrome)[ \/]([\w.]+)/.exec(ua) ||
                    /(version)(applewebkit)[ \/]([\w.]+).*(safari)[ \/]([\w.]+)/.exec(ua) ||
                    /(webkit)[ \/]([\w.]+).*(version)[ \/]([\w.]+).*(safari)[ \/]([\w.]+)/.exec(ua) ||
                    /(webkit)[ \/]([\w.]+)/.exec(ua) ||
                    /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
                    /(msie) ([\w.]+)/.exec(ua) ||
                    ua.indexOf("trident") >= 0 && /(rv)(?::| )([\w.]+)/.exec(ua) ||
                    ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
                    [];

            var platform_match = /(ipad)/.exec(ua) ||
                    /(ipod)/.exec(ua) ||
                    /(iphone)/.exec(ua) ||
                    /(kindle)/.exec(ua) ||
                    /(silk)/.exec(ua) ||
                    /(android)/.exec(ua) ||
                    /(windows phone)/.exec(ua) ||
                    /(win)/.exec(ua) ||
                    /(mac)/.exec(ua) ||
                    /(linux)/.exec(ua) ||
                    /(cros)/.exec(ua) ||
                    /(playbook)/.exec(ua) ||
                    /(bb)/.exec(ua) ||
                    /(blackberry)/.exec(ua) ||
                    [];

            var browser = {},
                    matched = {
                        browser: match[ 5 ] || match[ 3 ] || match[ 1 ] || "",
                        version: match[ 2 ] || match[ 4 ] || "0",
                        versionNumber: match[ 4 ] || match[ 2 ] || "0",
                        platform: platform_match[ 0 ] || ""
                    };

            if (matched.browser) {
                browser[ matched.browser ] = true;
                browser.version = matched.version;
                browser.versionNumber = parseInt(matched.versionNumber, 10);
            }

            if (matched.platform) {
                browser[ matched.platform ] = true;
            }

// These are all considered mobile platforms, meaning they run a mobile browser
            if (browser.android || browser.bb || browser.blackberry || browser.ipad || browser.iphone ||
                    browser.ipod || browser.kindle || browser.playbook || browser.silk || browser[ "windows phone" ]) {
                browser.mobile = true;
            }

// These are all considered desktop platforms, meaning they run a desktop browser
            if (browser.cros || browser.mac || browser.linux || browser.win) {
                browser.desktop = true;
            }

// Chrome, Opera 15+ and Safari are webkit based browsers
            if (browser.chrome || browser.opr || browser.safari) {
                browser.webkit = true;
            }

// IE11 has a new token so we will assign it msie to avoid breaking changes
// IE12 disguises itself as Chrome, but adds a new Edge token.
            if (browser.rv || browser.edge) {
                var ie = "msie";

                matched.browser = ie;
                browser[ie] = true;
            }

// Blackberry browsers are marked as Safari on BlackBerry
            if (browser.safari && browser.blackberry) {
                var blackberry = "blackberry";

                matched.browser = blackberry;
                browser[blackberry] = true;
            }

// Playbook browsers are marked as Safari on Playbook
            if (browser.safari && browser.playbook) {
                var playbook = "playbook";

                matched.browser = playbook;
                browser[playbook] = true;
            }

// BB10 is a newer OS version of BlackBerry
            if (browser.bb) {
                var bb = "blackberry";

                matched.browser = bb;
                browser[bb] = true;
            }

// Opera 15+ are identified as opr
            if (browser.opr) {
                var opera = "opera";

                matched.browser = opera;
                browser[opera] = true;
            }

// Stock Android browsers are marked as Safari on Android.
            if (browser.safari && browser.android) {
                var android = "android";

                matched.browser = android;
                browser[android] = true;
            }

// Kindle browsers are marked as Safari on Kindle
            if (browser.safari && browser.kindle) {
                var kindle = "kindle";

                matched.browser = kindle;
                browser[kindle] = true;
            }

// Kindle Silk browsers are marked as Safari on Kindle
            if (browser.safari && browser.silk) {
                var silk = "silk";

                matched.browser = silk;
                browser[silk] = true;
            }

// Assign the name and platform variable
            browser.name = matched.browser;
            browser.platform = matched.platform;
            return browser;
        }

// Run the matching process, also assign the function to the returned object
// for manual, jQuery-free use if desired
        window.jQBrowser = uaMatch(window.navigator.userAgent);
        window.jQBrowser.uaMatch = uaMatch;

// Only assign to jQuery.browser if jQuery is loaded
        if (jQuery) {
            jQuery.browser = window.jQBrowser;
        }

        return window.jQBrowser;
    }));
////console.log(window.jQBrowser.name);
    var browserName = window.jQBrowser.name;

</script>
<script type="text/javascript">
    toastr.options = {
        positionClass: "toast-top-center"
    };

    function timer() {

        var obj = document.querySelector('.timerInp[data-id="' + answerForQuestionId + '"]');
        obj.innerHTML--;
        if (obj.innerHTML <= 0) {

            obj.innerHTML = 12;
            if (modalWindowFlag == '1') {
                $('.tooltipMy[data-id="' + answerForQuestionId + '"]').text(SHUTUP);
                $('.tooltipMy[data-id="' + answerForQuestionId + '"]').css('color', 'red');

            } else {
                $('.tooltipMy[data-id="' + answerForQuestionId + '"]').text(SPEAK);
                $('.tooltipMy[data-id="' + answerForQuestionId + '"]').css('color', 'greenyellow');

            }
            $('.timer[data-id="' + answerForQuestionId + '"]').hide();

            return true;
        }
        else {
            setTimeout(timer, 1000);
            if (obj.innerHTML <= 6) {
                if (modalWindowFlag == '0') {
                    $('.tooltipMy[data-id="' + answerForQuestionId + '"]').text(SHUTUP);
                    $('.tooltipMy[data-id="' + answerForQuestionId + '"]').css('color', 'red');
                } else {
                    $('.tooltipMy[data-id="' + answerForQuestionId + '"]').text(SPEAK);
                    $('.tooltipMy[data-id="' + answerForQuestionId + '"]').css('color', 'greenyellow');

                }
            }
        }
        return true;
    }

    function PauseAll() {
        var videos = document.querySelectorAll('video');
        var index, len;
        for (index = 0, len = videos.length; index < len; ++index) {

            videos[index].pause();
        }
    }

    $(document).ready(function () {


        $(document).on("click", ".answers-more", function () {

            var id = $(this).attr('data-id');
            var offset = $(this).attr('data-offset');

            $.post('/answers/more', {
                question_id: id,
                offset: offset
            }, function (res) {
                if (res.error) {
                    toastr.error(res.error, ERROR);
                } else if (res.nextOffset != '0') {
                    $('#answers' + id + '').append(res.content);
                    $(".answers-more[data-id='" + id + "']").attr('data-offset', res.nextOffset);
                } else if ((res.nextOffset == '0') && (res.content)) {
                    $('#answers' + id + '').append(res.content);
                    $('#moreAnswer' + id + '').hide();
                    $(".answers-more[data-id='" + id + "']").attr('data-offset', res.nextOffset);
                } else if ((res.nextOffset == '0') && (!res.content)) {
                    $('#moreAnswer' + id + '').hide();
                    $(".answers-more[data-id='" + id + "']").attr('data-offset', res.nextOffset);
                }
            });

            return false;

        });

        $(document).on("click", ".rating", function () {

            var action = $(this).data('action');
            var ratingId = $(this).data('id');

            $.post('/rating', {
                rating_id: ratingId,
                action: action
            }, function (res) {
                if (res.error) {
                    toastr.warning(res.error, WARNING);

                } else if (res.success) {

                    toastr.success(res.success, SUCCESS);
                    var newRating = res.newRating;

                    $(".rating[data-id='" + ratingId + "'][data-action='" + action + "'] span").text(newRating);
                }
            });
            return false;
        });

        $(document).on("click", '.publishQuestion[data-id="0"]', function () {
            var title = $.trim($('.videoTitle[data-id="0"]').val());
            var voice = $('input[name=voiceChange]:checked').val();
            var videoFilter = $('input[name=videoChange]:checked').val();
            var videoFilterMore = $('#moreFiltres :selected').val();

            if (title == '') {
                toastr.warning(EnterTitleQ, PROMPT);
                $('.titleVideo[data-id="0"]').addClass('has-warning');
                $('.videoTitle[data-id="0"]').focus();
                return false;
            } else if ((voice < 0) || (voice > 2)) {
                toastr.error(WrongData, ERROR);
                return false;
            } else {

                var blob = RECo instanceof Blob ? RECo : RECo.blob;
                var fileType = blob.type.split('/')[0] || 'video';
                var fileName = title;

                fileName += '.webm';

                var formData = new FormData();
                formData.append(fileType + '-filename', fileName);
                formData.append(fileType + '-blob', blob);
                formData.append('title', title);
                formData.append('voice', voice);
                formData.append('videoFilter', videoFilter);
                formData.append('videoFilterMore', videoFilterMore);

                $.ajax({
                    url: '/question-publish',
                    data: formData,
                    processData: false,
                    type: 'POST',
                    contentType: false,
                    success: function (data) {

                        if (data.error) {
                            toastr.warning(data.error, WARNING);
                        } else if (data.success) {
                            toastr.options = {
                                positionClass: "toast-top-full-width"
                            };
                            toastr.success(data.success, SUCCESS);
                            recordingPlayer.muted = true;
                            recordingPlayer.controls = false;

                            recordingPlayer.src = '';
                            $('.publish[data-id="' + answerForQuestionId + '"]').hide();
                            $('.videoTitle[data-id="' + answerForQuestionId + '"]').val('');
                            $('#make-question').modal('hide');
                            toastr.options = {
                                positionClass: "toast-top-center"
                            };
                            delete(RECo);

                        }
                    }
                });

            }
            return false;
        });

        $(document).on("click", '.publishAnswer', function () {

            var title = $.trim($('.videoTitle[data-id="' + answerForQuestionId + '"]').val());
            var voice = $('input[name=voiceChange' + answerForQuestionId + ']:checked').val();
            var videoFilter = $('input[name=videoChange' + answerForQuestionId + ']:checked').val();
            var videoFilterMore = $('#moreFiltres' + answerForQuestionId + ' :selected').val();
            var questionId = answerForQuestionId;

            if (title == '') {

                toastr.warning(EnterTitleA, PROMPT);
                $('.titleVideo[data-id="' + answerForQuestionId + '"]').addClass('has-warning');
                $('.videoTitle[data-id="' + answerForQuestionId + '"]').focus();
                return false;
            } else if ((voice < 0) || (voice > 2)) {
                toastr.error(WrangData, ERROR);
                return false;
            } else {

                var blob = RECo instanceof Blob ? RECo : RECo.blob;
                var fileType = blob.type.split('/')[0] || 'video';
                var fileName = title;

                fileName += '.webm';

                var formData = new FormData();
                formData.append(fileType + '-filename', fileName);
                formData.append(fileType + '-blob', blob);
                formData.append('title', title);
                formData.append('voice', voice);
                formData.append('videoFilter', videoFilter);
                formData.append('questionId', questionId);
                formData.append('videoFilterMore', videoFilterMore);

                $.ajax({
                    url: '/answer-publish',
                    data: formData,
                    processData: false,
                    type: 'POST',
                    contentType: false,
                    success: function (data) {
                        if (data.error) {
                            toastr.warning(data.error, WARNING);
                        } else if (data.success) {
                            toastr.options = {
                                positionClass: "toast-top-full-width"
                            };
                            toastr.success(data.success, SUCCESS);
                            recordingPlayer.muted = true;
                            recordingPlayer.controls = false;
                            recordingPlayer.src = '';
                            $('.publishAnswer[data-id="' + answerForQuestionId + '"]').hide();
                            $('.videoTitle[data-id="' + answerForQuestionId + '"]').val('');
                            $('#make-answer' + answerForQuestionId + '').modal('hide');
                            toastr.options = {
                                positionClass: "toast-top-center"
                            };
                            delete(RECo);
                        }
                    }
                });

            }
            return false;
        });

        $('#make-answer' + answerForQuestionId + '').on('hidden.bs.modal', function () {
            $(document).off("click", '.publishAnswer[data-id="' + answerForQuestionId + '"]');
        });

        $(document).on("keypress", '.videoTitle[data-id="' + answerForQuestionId + '"]', function () {
            $('.titleVideo[data-id="' + answerForQuestionId + '"]').removeClass('has-warning');
            return true;
        });

        $('.answ').on('hidden.bs.modal', function () {

            var button = recordingDIV.querySelector('button');
            function stopStream(button) {
                if (button.stream && button.stream.stop) {
                    button.stream.stop();
                    button.stream = null;
                }
            }
            stopStream(button);
            $('.resetAnswer[data-id="' + answerForQuestionId + '"]').hide();

            recordingPlayer.pause();
            recordingPlayer2.pause();
            recordingPlayer.removeEventListener('timeupdate', function () {
                return;
            });
            delete(mediaRecorder);
            delete(recordingPlayer);
            delete(recordingPlayer2);

        });

        $('#make-question').on('hidden.bs.modal', function () {
            var button = recordingDIV.querySelector('button');
            function stopStream(button) {
                if (button.stream && button.stream.stop) {
                    button.stream.stop();
                    button.stream = null;
                }
            }
            $('.resetQuestion[data-id="' + answerForQuestionId + '"]').hide();
            stopStream(button);

            recordingPlayer.removeEventListener('timeupdate', function () {
                return;
            });
            recordingPlayer.pause();
            delete(mediaRecorder);
            delete(recordingPlayer);

        });

    });

    function getModalWindowFlag() {
        modalWindowFlag = $('.modalWindowFlag').val();

        $('.modalWindowFlag').attr('data-id');
        if ((modalWindowFlag == '0') || (modalWindowFlag == null) || (modalWindowFlag == '')) {
            modalWindowFlag = '0';
            answerForQuestionId = '0';
            recordingDIV = document.querySelector('.recordrtc[data-id="0"]');

            recordingPlayer = recordingDIV.querySelector('video[data-id="0"]');
            recordingPlayer.controls = false;
            $('.publishQuestion[data-id="0"]').hide();
            $('#make-question button.close').show();
            $('#make-question button[data-dismiss="modal"]').show();


        }
        else if (modalWindowFlag == '1') {

            recordingDIV = document.querySelector('.recordrtc[data-id="' + answerForQuestionId + '"]');
            recordingPlayer = recordingDIV.querySelector('video[data-id="' + answerForQuestionId + '"]');
            recordingPlayer2 = recordingDIV.querySelector('video[id="questVideoId' + answerForQuestionId + '"]');
            $('.publishAnswer[data-id="' + answerForQuestionId + '"]').hide();

            recordingPlayer.controls = false;
            recordingPlayer2.controls = false;
            $('#make-answer' + answerForQuestionId + ' button.close').show();
            $('#make-answer' + answerForQuestionId + ' button[data-dismiss="modal"]').show();

        }
        return modalWindowFlag;
    }


    var useragent = navigator.userAgent;

    if (useragent.indexOf('YaBrowser') != -1) {
        browserName = "yandex";
    }

    if ((device.windows() === true) && ((browserName != 'chrome') && (browserName != 'yandex'))) {
        toastr.options = {
            positionClass: "toast-top-full-width"
        };
        toastr.warning(WindowsBrowserWarning, WARNING);

        toastr.options = {
            positionClass: "toast-top-center"
        };
    } else if (((device.android() === true) || (device.androidPhone() === true)) && ((browserName != 'chrome') && (browserName != 'opera'))) {
        toastr.options = {
            positionClass: "toast-top-full-width"
        };
        toastr.warning(AndroidBrowserWarning, WARNING);

        toastr.options = {
            positionClass: "toast-top-center"
        };

    }

    $(document).ready(function () {

        $(document).on("click", ".makeQuestion", function () {

            PauseAll();
            $('.modalWindowFlag').val('0');
            getModalWindowFlag();
            superPuperMobile();

            return false;
        });

        $(document).on("click", ".makeAnswer", function () {

            PauseAll();
            var id = $(this).data('id');

            $('.modalWindowFlag').val('1');
            $('.modalWindowFlag').attr('data-id', id);
            answerForQuestionId = id;
            getModalWindowFlag();
            superPuperMobile();

            return false;
        });

    });

    //alert (browserName);
    'use strict';

    /* globals MediaRecorder */

// This code is adapted from
// https://rawgit.com/Miguelao/demos/master/mediarecorder.html

    'use strict';

    /* globals MediaRecorder */
    getModalWindowFlag();

    function superPuperMobile() {

        var button = recordingDIV.querySelector('button');
        button.onclick = toggleRecording;

        button.style.visibility = 'visible';
        button.style.width = 'auto';
        button.style.padding = '5px';
        button.disabled = false;

        mediaSource = new MediaSource();
        mediaSource.addEventListener('sourceopen', handleSourceOpen, false);

        window.onbeforeunload = function () {
            recordingDIV.querySelector('button').disabled = false;

        };

        recordingPlayer.addEventListener('timeupdate', function () {

            // don't have set the startTime yet? set it to our currentTime
            if (!this._startTime)
                this._startTime = this.currentTime;

            var playedTime = this.currentTime - this._startTime;

            timeOut = 12;

            if (playedTime >= timeOut) {
                this.pause();
                var button = recordingDIV.querySelector('button');
                button.disabled = true;
                button.disableStateWaiting = true;
                setTimeout(function () {

                    button.disableStateWaiting = false;
                    button.style.visibility = 'hidden';
                    button.style.padding = '0';
                    button.style.width = '0';

                    if (modalWindowFlag == '1') {
                        $('.publishAnswer[data-id="' + answerForQuestionId + '"]').show();
                        $('.resetAnswer[data-id="' + answerForQuestionId + '"]').show();

                    } else {
                        $('.publishQuestion[data-id="' + answerForQuestionId + '"]').show();
                        $('.resetQuestion[data-id="' + answerForQuestionId + '"]').show();

                    }

                }, 2 * 1000);
                recordingPlayer.currentTime = 0;
                this._startTime = 0;
                button.innerHTML = START;

                stopRecording();
                this.muted = true;
                return;
            }
        });

        recordingPlayer.addEventListener('seeking', function () {
            this._startTime = undefined;
        });


// Use old-style gUM to avoid requirement to enable the
// Enable experimental Web Platform features flag in Chrome 49

        function handleError(error) {

        }

        function handleSuccess(stream) {
            window.stream = stream;
            if (window.URL) {
                recordingPlayer.src = window.URL.createObjectURL(stream);
            } else {
                recordingPlayer.src = stream;
            }
        }


        function handleSourceOpen(event) {

            sourceBuffer = mediaSource.addSourceBuffer('video/webm; codecs="vp8"');

        }

        function handleDataAvailable(event) {
            if (event.data && event.data.size > 0) {
                recordedBlobs.push(event.data);
            }
        }

        function handleStop(event) {

        }

        var constraints = {
            audio: true,
            video: true
        };


        navigator.mediaDevices.getUserMedia(constraints).
                then(handleSuccess).catch(handleError);


        function toggleRecording() {

            if (button.innerHTML == START) {

                startRecording();
            }
        }

// The nested try blocks will be simplified when Chrome 47 moves to Stable
        function startRecording() {
            recordedBlobs = [];
            var options = {mimeType: 'video/webm;codecs=vp9'};
            if (!MediaRecorder.isTypeSupported(options.mimeType)) {

                options = {mimeType: 'video/webm;codecs=vp8'};
                if (!MediaRecorder.isTypeSupported(options.mimeType)) {

                    options = {mimeType: 'video/webm'};
                    if (!MediaRecorder.isTypeSupported(options.mimeType)) {

                        options = {mimeType: ''};
                    }
                }
            }
            try {

                mediaRecorder = new MediaRecorder(window.stream, options);
            } catch (e) {

                toastr.warning(CameraAndMicrophoneWarning, WARNING);

            }

            mediaRecorder.onstop = handleStop;
            mediaRecorder.ondataavailable = handleDataAvailable;
            mediaRecorder.start(10); // collect 10ms of data
            recordingPlayer.play();
            button.innerHTML = RECORDING;
            button.disabled = true;
            recordingPlayer.muted = true;
            recordingPlayer.controls = false;

            if (modalWindowFlag == '1') {
                recordingPlayer2.play();
                recordingPlayer2.muted = true;
            }
            $('.timer[data-id="' + answerForQuestionId + '"]').show();
            setTimeout(timer, 1000);
            if (modalWindowFlag == '1') {
                $('.publishAnswer[data-id="' + answerForQuestionId + '"]').hide();
                $('#make-answer' + answerForQuestionId + ' button.close').hide();
                $('#make-answer' + answerForQuestionId + ' button[data-dismiss="modal"]').hide();
            } else {
                $('.publishQuestion[data-id="' + answerForQuestionId + '"]').hide();
                $('#make-question button.close').hide();
                $('#make-question button[data-dismiss="modal"]').hide();
            }

        }

        function stopRecording() {
            mediaRecorder.stop();
            RECo = new Blob(recordedBlobs, {type: 'video/webm'});
            recordingPlayer.pause();
            recordingPlayer.currentTime = 0;
            recordingPlayer.src = window.URL.createObjectURL(RECo);
            recordingPlayer.removeEventListener('timeupdate', function () {
            });
            delete (mediaRecorder);
        }

    }

</script>
