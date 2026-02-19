// Force all AJAX requests to use HTTPS
(function() {
    var originalFetch = window.fetch;
    window.fetch = function() {
        var url = arguments[0];
        if (typeof url === 'string' && url.startsWith('http://')) {
            arguments[0] = url.replace('http://', 'https://');
        }
        return originalFetch.apply(this, arguments);
    };
    
    var originalXHROpen = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function() {
        var url = arguments[1];
        if (typeof url === 'string' && url.startsWith('http://')) {
            arguments[1] = url.replace('http://', 'https://');
        }
        originalXHROpen.apply(this, arguments);
    };
})();
