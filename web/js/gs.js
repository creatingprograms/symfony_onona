/*
  Task 125038. Gdeslon 3.0
*/
if (!window.gdeslon_q || window.gdeslon_q instanceof Array) {

    var hasPerformance = "undefined" !== typeof performance && "function" === typeof performance.now;
    var perf = hasPerformance ? performance.now() : null;

    var oldQueue = window.gdeslon_q || [];
    window.gdeslon_q = function() {

        var _exceptions = [],
            _state = {},

            appendScript = function(url) {
                var gss = document.createElement("script");
                gss.type = "text/javascript";
                gss.async = true;
                gss.src = url;
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(gss, s);
            },

            serializeObject = function serializeObject(obj) {
                return Object.keys(obj).map(function(key) {
                    return encodeURIComponent(key) + "=" + encodeURIComponent(obj[key]);
                }).join("&");
            },

            deserializeObject = function deserializeObject(str, pairsSeparator, keyValueSeparator) {
                var result = {},
                    pairs,
                    pair,
                    key, value, i, l;

                if (!keyValueSeparator) {
                    keyValueSeparator = "=";
                }

                if (!str) {
                    return result;
                }

                pairs = str.split(pairsSeparator);
                for (i = 0, l = pairs.length; i < l; i++) {
                    pair = pairs[i]
                        .replace(/^\s+|\s+$/g, "")
                        .split(keyValueSeparator);
                    try {
                        key = decodeURIComponent(pair[0]);
                        value = decodeURIComponent(pair[1]);
                        result[key] = value;
                    } catch (e) {}
                }

                return result;
            },

            func = function(a, b) {
                try {
                    return void 0 === b ? a() : a.apply(this, b);
                } catch (c) {
                    _exceptions.push(c);
                    var url = "https://gdeslon.ru/error.js?" + serializeObject({message: c.message});
                    appendScript(url);
                }
            },

            location = function() {
                return document.location;
            }(),

            domain = function domain() {
                var domain = location.hostname || location.host.split(":")[0];
                var domainParts = domain.split(".");
                var l = domainParts.length;

                if (l > 1) {
                    domain = domainParts[l - 2] + "." + domainParts[l - 1];
                }
                return domain;
            }(),

            queryParams = function () {
                return deserializeObject(location.search.slice(1), "&");
            }(),

            cookieTtl = function() {
                var cookieTtl = parseInt(queryParams._gs_cttl, 10);
                if (!cookieTtl || isNaN(cookieTtl)) {
                    cookieTtl = 180;
                }
                return cookieTtl;
            }(),

            writeCookie = function writeCookie(name, value, ttlSeconds) {
                if (!(name && value)) {
                    return;
                }

                value = encodeURIComponent(value);
                var ttl = ttlSeconds || cookieTtl * 24 * 60 * 60;

                var date = new Date();
                date.setTime(date.getTime() + ttl * 1000);
                var expires = "; expires=" + date.toGMTString();
                var domainParam = "domain=" + domain + "; ";

                document.cookie = name + "=" + value + expires + "; " + domainParam + "path=/";
            },

            cookies = function cookies(key) {
                return deserializeObject(document.cookie, ";")[key];
            },

            token = function() {
                return cookies("gdeslon.ru.__arc_token");
            },

            affiliate_id = function() {
                return cookies("gdeslon.ru.__arc_aid");
            },

            track_domain = function() {
                return cookies("gdeslon.ru.__arc_domain");
            },

            _push = function _push() {
                func(function() {

                    _state.pushStartedAt = Date.now();

                    var pixel = [];
                    var track = [];

                    if(arguments.length === 0) {
                        return;
                    }

                    var obj = arguments[0];
                    var shouldInvokeTrack = false;

                    Object.keys(obj).forEach(function(key) {
                        var val = obj[key];
                        var same = "";
                        switch (key) {
                        case "page_type" :
                            pixel.mode = val;
                            break;
                        case "merchant_id" :
                            pixel.mid = val;
                            track.merchant_id = val;
                            break;
                        case "order_id" :
                            pixel.order_id = val;
                            track.order_id = val;
                            break;
                        case "category_id" :
                            pixel.cat_id = val;
                            break;
                        case "products" :
                            same = val.map( function(l) {
                                var repeats = [];
                                for(var i = 0; i < parseFloat(l["quantity"], 10); i++) {
                                    repeats.push(l["id"] + ":" + parseFloat(l["price"], 10));
                                }
                                return repeats.join(",");
                            });
                            pixel.codes = same;
                            track.codes = same;
                            break;
                        }
                    });

                    if(obj.page_type === "thanks") {
                        if (obj.hasOwnProperty("deduplication")) {
                            if (Object.prototype.toString.call(obj.deduplication) === "[object String]") {
                                var trueArr = ["gdeslon", "gde slon", "", "undefined", "null", "true", "1"];
                                if (trueArr.indexOf(obj.deduplication.toLowerCase()) > -1) {
                                    shouldInvokeTrack = true;
                                } else {
                                    shouldInvokeTrack = false;
                                }
                            } else {
                                shouldInvokeTrack = true;
                            }
                        } else {
                            shouldInvokeTrack = true;
                        }
                    }

                    pixel.perf = parseInt(perf, 10);
                    track.perf = parseInt(perf, 10);

                    pixel.source = window.location.href;

                    var url = "//gdeslon.ru/gsp.js?" + serializeObject(pixel);
                    appendScript(url);

                    if(shouldInvokeTrack) {
                        _state.shouldInvokeTrack = true;
                        track.affiliate_id = affiliate_id();
                        track.token = token();
                        url = "//" + track_domain() + "/purchase.js?" + serializeObject(track);
                        var xhr = new XMLHttpRequest();
                        xhr.open("GET", url, true);
                        xhr.send();
                    } else {
                        _state.shouldInvokeTrack = false;
                    }
                    _state.pushFinishedAt = Date.now();
                }, arguments);
            };

        if(queryParams.gsaid) {
            writeCookie("gdeslon.ru.__arc_aid", queryParams.gsaid);
        }

        if(queryParams._gs_ref) {
            writeCookie("gdeslon.ru.__arc_token", queryParams._gs_ref);
        }

        if(queryParams._gs_vm) {
            writeCookie("gdeslon.ru.__arc_domain", queryParams._gs_vm);
        }

        return {
            push: _push,
            exceptions: _exceptions,
            state: _state
        };
    }();

    window.gdeslon_q.push.apply(window.gdeslon_q, oldQueue);
}
