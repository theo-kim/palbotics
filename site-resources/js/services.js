app.service("httpPro", function ($http, $httpParamSerializerJQLike) {
    return {
        getJSONArray (url, params) {
            return new Promise((resolve, reject) => {
                const request = { method: "GET", url: url, params: params };
                const response = function (res) {
                    const data = res.data;
                    if (data.constructor === Array) {
                        resolve(data);
                    }
                    else {
                        reject(data);
                    }
                };

                $http(request).then(response);
            });
        },
        getJSON(url, params) {
            return new Promise((resolve, reject) => {
                const request = { method: "GET", url: url, params: params };
                const response = function (res) {
                    const data = res.data;
                    if (typeof data === "object") resolve(data);
                    else reject(data);
                };
                $http(request).then(response);
            });
        },
        getSuccess (url, params) {
            return new Promise((resolve, reject) => {
                const request = { method: 'GET', url: url, params: params, };
                const response = function (res) {
                    const data = res.data;
                    if (data !== 'success') reject(data);
                    else resolve();
                }
                $http(request).then(response);
            });
        },
        postSuccessPHP (url, params) {
            return new Promise((resolve, reject) => {
                const request = { url: url, params: $httpParamSerializerJQLike(params) };
                const response = function (res) {
                    const data = res.data;
                    if (data !== 'success') reject(data);
                    else resolve();
                }
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http.post(request.url, request.params).then(response);
            });
        },
        postIdPHP (url, params) {
            return new Promise((resolve, reject) => {
                const request = { url: url, params: $httpParamSerializerJQLike(params) };
                const response = function (res) {
                    const data = parseInt(res.data);
                    if (typeof data !== "number") reject(data);
                    else resolve(data);
                }
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http.post(request.url, request.params).then(response);
            });
        }
    }
});

app.service("redirect", function ($rootScope, $window, $timeout) {
    return function (to, from) {
        const keep = $rootScope.back;
        $rootScope.back = function () {
            $rootScope.open = false;
            $timeout(function () {
                $rootScope.open = true;
                $rootScope.back = keep;
                $window.location.hash = from;
            }, 1000);
        }
        $window.location.hash = to;
    }
});
