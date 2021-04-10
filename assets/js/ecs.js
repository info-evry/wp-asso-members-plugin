function ecs(esf, ...esr) {
    let es;
    if ( esf === null || esf === undefined) { return ''; }
    if ( esr.length > 0 ) {
        es = [esf,...esr];
        const a = document.createElement('div');
        for ( const e of es ) { a.appendChild( ecs( e ) ); }
        return a;
    }
    else { es = esf; }
    if (es instanceof Element) {
        const a = document.createElement('div');
        a.appendChild(es);
        return a;
    }
    const { a, c, d, i, id, n, s, sf, t, x } = es;
    if (id || c || t) {
        if (n) { es = document.createElementNS(n, t); }
        else { es = document.createElement(t !== '' && t !== undefined ? t : 'div'); }
        if (id) { es.id = id; }
        if (c) { es.classList.add(...c); }
    }
    else { es = document.createElement('div'); }
    if (a) { for (const [k, v] of Object.entries(a)) { es.setAttribute(k, v); } }
    if (d) { for (const [k, v] of Object.entries(d)) { es.dataset[k] = v; } }
    if (s) { for (const [k, v] of Object.entries(s)) { es.style[k] = v; } }
    if (i) {
        for (const j of i) {
            if (j instanceof Element) { es.appendChild(j); }
            else if (['string', 'number', 'bigint', 'boolean', 'symbol'].indexOf(typeof j) !== -1) { es.innerHTML += '' + j; }
            else {
                try { es.appendChild(ecs(j)); }
                catch ( error ) {}
            }
        }
    }
    if (sf) { for (let chainScoped of sf) { console.log('scope:', chainScoped); } }
    if (x) {
        for (const [k, v] of Object.entries(x)) {
            const ka = k.split(/\_\$/);
            if (ka.length > 1) { es[ka[0]](...v); }
            else { es[k](...v); }
        }
    }
    return es;
}

function ecs_s(...ecson) {
    const current = document.currentScript;
    if (![document.head, document.documentElement].includes(current.parentElement)) {
        for (const _es of ecson) { current.insertAdjacentElement('beforebegin', ecs(_es)); }
        current.remove();
    }
}

const jsonToURL = function (data) {
    let objects = [data];
    let string = '';
    for (let object of objects) {
        for (let prop in object) {
            if (object.hasOwnProperty(prop) && ![[], '', 0, false, undefined, null, {}].includes(object[prop])) {
                if ( object[prop] instanceof Object ) {
                    objects.push( object[prop] );
                }
                else {
                    if (string.length > 1) {
                        string += '&' + prop + '=' + encodeURIComponent((object[prop]).toString());
                    } else {
                        string += prop + '=' + encodeURIComponent((object[prop]).toString());
                    }
                }
            }
        }
    }
    return string;
};

async function ecs_fetch({
    url = '',
    type = 'GET',
    mode = 'no-cors',
    cache = 'no-cache',
    credentials = 'same-origin',
    headers = {
        'Content-Type': 'application/x-www-form-urlencoded',
        //'Content-Type': 'application/json'
    },
    redirect = 'follow',
    referrer = '',
    data = {},
    success = data => ({ data }),
    error = data => ({ data }),
    callback = data => ({ data }),
    jsonify = true
}) {
    const options = {
        cache: cache,
        credentials: credentials,
        headers: new Headers(headers),
        method: type,
        mode: mode,
        redirect: redirect,
        referrer: referrer
    };
    options.body = {
        'application/x-www-form-urlencoded': ( data ) => jsonToURL( data ),
        'application/json': ( data ) => JSON.stringify( data )
    }[ options.headers.get( 'Content-Type' ) ]( data ) || data ;
    return await fetch(url, options)
        .then(async r => {
            let a;
            if ( jsonify ) {
                const { ok: b, success: c } = r;
                a = await r.json();
                return (b === true || c === 'success') ? success(a) : error(a);
            }
            else {
                a = await r.text();
                if ( a.length > 0 ) {
                    return success( a );
                }
                else {
                    return error( a );
                }
            }
        }).then( data => callback( data ) );
}