'use strict';
app.factory('$requestFactory', function ($http) {
    var sub = null;
    var query = null;
    var method = 'GET';
    var res = null;
    return {
        setSub: function (s) {
            sub = s;
        },
        setQuery: function (q) {
            query = q;
        },
        setMethod: function (m) {
            method = m;
        },
        makeRequest: function () {
            var ret = null;
            switch (method) {
                case 'GET':
                    var append = '?';
                    for (var key in query) {
                        append += key + '=' + query[key];
                        append += '&';
                    }
                    ret = $http.get(base + sub + append).then(function (data) {
                        if (typeof data.data != typeof undefined) {
                            res = data.data;
                        }
                        else {
                            res = data;
                        }
                    });
                    break;
                case 'POST':
                    ret = $http.post(base + sub, query).then(function (data) {
                        if (typeof data.data !== typeof undefined) {
                            res = data.data;
                        }
                        else {
                            res = data;
                        }
                    });
                    break;
            }
            return ret;
        },
        setResults: function (data) {
            res = data;
        },
        getResults: function () {
            return res;
        }
    };
});