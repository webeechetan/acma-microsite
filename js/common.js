const snackbar = m => {
    let x = document.getElementById("snackbar")
    x.innerHTML = m
    x.className = "show"
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000)
}

const commonAjax = (o) => {
    const {
        page, params, type, header, cors, parse
    } = o;
    const customHeader = new Headers();
    customHeader.append("Accept", "application/json");
    const method = type || 'POST';
    const request = page;
    const raw = params || '';
    const myHeaders = header || customHeader;
    const cors_type = cors || 'cors';
    const parse_data = parse || 'json';
    var requestOptions = {
        method: method,
        headers: myHeaders,
        body: raw,
        mode: cors_type
    };
    if(method === 'GET') {
        delete requestOptions['body'];
    }
    const myPromise = new Promise(function(myResolve, myReject) {
        fetch(request, requestOptions).then(response => response.json()).then(response => myResolve(response)).catch(error => {
            console.log(error);
            return myReject({
                "success": false
            });
        });
    });
    return myPromise;
}