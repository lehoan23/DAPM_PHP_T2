let _apiLinkGlobal = "http://127.0.0.1:8000";
let _tokenLoginGlobal = localStorage.getItem("mytokelogin");

var callApiGlobal = (function () {
    var obj = {};

    obj.postRequestGlobalDelayNoToken = function (urlRequest, info) {
        var url = _apiLinkGlobal + urlRequest;
        let ajaxCall = jQuery.ajax({
            type: "POST",
            url: url,
            data: JSON.stringify(info),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
        });
        return ajaxCall;
    }

    obj.postRequestGlobal = function (urlRequest, info) {
        var url = _apiLinkGlobal + urlRequest;
        let ajaxCall = jQuery.ajax({
            type: "POST",
            url: url,
            data: JSON.stringify(info),
            headers: { 'Authorization': 'Bearer ' + _tokenLoginGlobal },
            contentType: "application/json; charset=utf-8",
            dataType: "json",
        });
        return ajaxCall;
    }

    obj.postRequestGlobalWithCrsf = function (urlRequest, info) {
        var url = _apiLinkGlobal + urlRequest;
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Lấy token từ meta tag
        let ajaxCall = jQuery.ajax({
            type: "POST",
            url: url,
            data: JSON.stringify(info),
            xhrFields: {
                withCredentials: true  // Cho phép gửi cookie
            },
            headers: {
                'Authorization': 'Bearer ' + _tokenLoginGlobal,
                'X-CSRF-TOKEN': csrfToken // Thêm CSRF token vào header
            },
            contentType: "application/json; charset=utf-8",
            dataType: "json",
        });
        return ajaxCall;
    }

    obj.putRequestGlobal = function (urlRequest, info) {
        var url = _apiLinkGlobal + urlRequest;
        let ajaxCall = jQuery.ajax({
            type: "PUT",
            url: url,
            data: JSON.stringify(info),
            headers: { 'Authorization': 'Bearer ' + _tokenLoginGlobal },
            contentType: "application/json; charset=utf-8",
            dataType: "json",
        });
        return ajaxCall;
    }

    obj.deleteRequestGlobal = function (urlRequest, info) {
        var url = _apiLinkGlobal + urlRequest;
        let ajaxCall = jQuery.ajax({
            type: "delete",
            url: url,
            data: JSON.stringify(info),
            headers: { 'Authorization': 'Bearer ' + _tokenLoginGlobal },
            contentType: "application/json; charset=utf-8",
            dataType: "json",
        });
        return ajaxCall;
    }


    obj.getRequestGlobal = function (urlRequest) {
        var url = _apiLinkGlobal + urlRequest;
        let ajaxCall = jQuery.ajax({
            type: "GET",
            url: url,
            headers: { 'Authorization': 'Bearer ' + _tokenLoginGlobal },
            contentType: "application/json; charset=utf-8",
            dataType: "json",
        });
        return ajaxCall;
    }

    obj.getRequestGlobalDelayNoToken = function (urlRequest) {
        var url = _apiLinkGlobal + urlRequest;
        let ajaxCall = jQuery.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
        });
        return ajaxCall;
    }

    return obj;
})();