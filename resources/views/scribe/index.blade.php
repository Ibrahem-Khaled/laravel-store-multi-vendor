<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Laravel API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.2.1.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.2.1.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-login">
                                <a href="#endpoints-POSTapi-login">POST api/login</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-register">
                                <a href="#endpoints-POSTapi-register">POST api/register</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-update-profile">
                                <a href="#endpoints-POSTapi-update-profile">POST api/update-profile</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-change-password">
                                <a href="#endpoints-POSTapi-change-password">POST api/change-password</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-me">
                                <a href="#endpoints-GETapi-me">GET api/me</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-user--user_id-">
                                <a href="#endpoints-GETapi-user--user_id-">GET api/user/{user_id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-addExpoPushToken">
                                <a href="#endpoints-POSTapi-addExpoPushToken">POST api/addExpoPushToken</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-delete-account">
                                <a href="#endpoints-POSTapi-delete-account">POST api/delete-account</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-categories">
                                <a href="#endpoints-GETapi-categories">GET api/categories</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-sub-categories">
                                <a href="#endpoints-GETapi-sub-categories">GET api/sub-categories</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-categories--category_id--sub-categories">
                                <a href="#endpoints-GETapi-categories--category_id--sub-categories">GET api/categories/{category_id}/sub-categories</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-notifications--type--">
                                <a href="#endpoints-GETapi-notifications--type--">GET api/notifications/{type?}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-notifications--id--mark-as-read">
                                <a href="#endpoints-POSTapi-notifications--id--mark-as-read">POST api/notifications/{id}/mark-as-read</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-notifications--id-">
                                <a href="#endpoints-DELETEapi-notifications--id-">DELETE api/notifications/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-products">
                                <a href="#endpoints-GETapi-products">GET api/products</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-products--product_id-">
                                <a href="#endpoints-GETapi-products--product_id-">GET api/products/{product_id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-featured-products">
                                <a href="#endpoints-GETapi-featured-products">GET api/featured/products</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-products--product_id--similars">
                                <a href="#endpoints-GETapi-products--product_id--similars">GET api/products/{product_id}/similars</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-products--product_id--reviews">
                                <a href="#endpoints-POSTapi-products--product_id--reviews">POST api/products/{product_id}/reviews</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-products--product_id--reviews">
                                <a href="#endpoints-DELETEapi-products--product_id--reviews">DELETE api/products/{product_id}/reviews</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-user-favorites-products">
                                <a href="#endpoints-GETapi-user-favorites-products">GET api/user/favorites/products</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-favorites-products--product_id-">
                                <a href="#endpoints-POSTapi-favorites-products--product_id-">POST api/favorites/products/{product_id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-reservations">
                                <a href="#endpoints-GETapi-reservations">GET api/reservations</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-reservations">
                                <a href="#endpoints-POSTapi-reservations">POST api/reservations</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-PUTapi-reservations--id-">
                                <a href="#endpoints-PUTapi-reservations--id-">PUT api/reservations/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-reservations--id-">
                                <a href="#endpoints-DELETEapi-reservations--id-">DELETE api/reservations/{id}</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: June 24, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-POSTapi-login">POST api/login</h2>

<p>
</p>



<span id="example-requests-POSTapi-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"qkunze@example.com\",
    \"password\": \"Z5ij-e\\/dl4m{o,\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "qkunze@example.com",
    "password": "Z5ij-e\/dl4m{o,"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-login">
</span>
<span id="execution-results-POSTapi-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-login" data-method="POST"
      data-path="api/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-login"
                    onclick="tryItOut('POSTapi-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-login"
                    onclick="cancelTryOut('POSTapi-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-login"
               value="qkunze@example.com"
               data-component="body">
    <br>
<p>Must be a valid email address. The <code>email</code> of an existing record in the users table. Example: <code>qkunze@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-login"
               value="Z5ij-e/dl4m{o,"
               data-component="body">
    <br>
<p>Must be at least 6 characters. Example: <code>Z5ij-e/dl4m{o,</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-register">POST api/register</h2>

<p>
</p>



<span id="example-requests-POSTapi-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"vmqeopfuudtdsufvyvddq\",
    \"email\": \"kunde.eloisa@example.com\",
    \"phone\": \"consequatur\",
    \"gender\": \"female\",
    \"role\": \"user\",
    \"password\": \"[2UZ5ij-e\\/dl4\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "vmqeopfuudtdsufvyvddq",
    "email": "kunde.eloisa@example.com",
    "phone": "consequatur",
    "gender": "female",
    "role": "user",
    "password": "[2UZ5ij-e\/dl4"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-register">
</span>
<span id="execution-results-POSTapi-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-register" data-method="POST"
      data-path="api/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-register"
                    onclick="tryItOut('POSTapi-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-register"
                    onclick="cancelTryOut('POSTapi-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-register"
               value="vmqeopfuudtdsufvyvddq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>vmqeopfuudtdsufvyvddq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-register"
               value="kunde.eloisa@example.com"
               data-component="body">
    <br>
<p>Must be a valid email address. Must not be greater than 255 characters. Example: <code>kunde.eloisa@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-register"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>gender</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="gender"                data-endpoint="POSTapi-register"
               value="female"
               data-component="body">
    <br>
<p>Example: <code>female</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>male</code></li> <li><code>female</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="role"                data-endpoint="POSTapi-register"
               value="user"
               data-component="body">
    <br>
<p>Example: <code>user</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>user</code></li> <li><code>trader</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-register"
               value="[2UZ5ij-e/dl4"
               data-component="body">
    <br>
<p>Must be at least 6 characters. Example: <code>[2UZ5ij-e/dl4</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-update-profile">POST api/update-profile</h2>

<p>
</p>



<span id="example-requests-POSTapi-update-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/update-profile" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "name=vmqeopfuudtdsufvyvddq"\
    --form "gender=male"\
    --form "bio=amniihfqcoynlazghdtqt"\
    --form "address=qxbajwbpilpmufinllwlo"\
    --form "country=auydlsmsjuryvojcybzvr"\
    --form "birth_date=2025-06-24T04:43:24"\
    --form "avatar=@C:\Users\10\AppData\Local\Temp\php82F3.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/update-profile"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('name', 'vmqeopfuudtdsufvyvddq');
body.append('gender', 'male');
body.append('bio', 'amniihfqcoynlazghdtqt');
body.append('address', 'qxbajwbpilpmufinllwlo');
body.append('country', 'auydlsmsjuryvojcybzvr');
body.append('birth_date', '2025-06-24T04:43:24');
body.append('avatar', document.querySelector('input[name="avatar"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-update-profile">
</span>
<span id="execution-results-POSTapi-update-profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-update-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-update-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-update-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-update-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-update-profile" data-method="POST"
      data-path="api/update-profile"
      data-authed="0"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-update-profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-update-profile"
                    onclick="tryItOut('POSTapi-update-profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-update-profile"
                    onclick="cancelTryOut('POSTapi-update-profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-update-profile"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/update-profile</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-update-profile"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-update-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-update-profile"
               value="vmqeopfuudtdsufvyvddq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>vmqeopfuudtdsufvyvddq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-update-profile"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-update-profile"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>gender</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="gender"                data-endpoint="POSTapi-update-profile"
               value="male"
               data-component="body">
    <br>
<p>Example: <code>male</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>male</code></li> <li><code>female</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>avatar</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="file" style="display: none"
                              name="avatar"                data-endpoint="POSTapi-update-profile"
               value=""
               data-component="body">
    <br>
<p>Must be an image. Must not be greater than 2048 kilobytes. Example: <code>C:\Users\10\AppData\Local\Temp\php82F3.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>bio</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="bio"                data-endpoint="POSTapi-update-profile"
               value="amniihfqcoynlazghdtqt"
               data-component="body">
    <br>
<p>Must not be greater than 500 characters. Example: <code>amniihfqcoynlazghdtqt</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="POSTapi-update-profile"
               value="qxbajwbpilpmufinllwlo"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>qxbajwbpilpmufinllwlo</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="country"                data-endpoint="POSTapi-update-profile"
               value="auydlsmsjuryvojcybzvr"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>auydlsmsjuryvojcybzvr</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>birth_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="birth_date"                data-endpoint="POSTapi-update-profile"
               value="2025-06-24T04:43:24"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2025-06-24T04:43:24</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-change-password">POST api/change-password</h2>

<p>
</p>



<span id="example-requests-POSTapi-change-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/change-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"current_password\": \"consequatur\",
    \"new_password\": \"mqeopfuudtdsufvyvddqamniihfqcoynlazghdtqtqxbajwbpilpmufinllwloauydlsmsjury\",
    \"confirm_password\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/change-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "current_password": "consequatur",
    "new_password": "mqeopfuudtdsufvyvddqamniihfqcoynlazghdtqtqxbajwbpilpmufinllwloauydlsmsjury",
    "confirm_password": "consequatur"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-change-password">
</span>
<span id="execution-results-POSTapi-change-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-change-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-change-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-change-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-change-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-change-password" data-method="POST"
      data-path="api/change-password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-change-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-change-password"
                    onclick="tryItOut('POSTapi-change-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-change-password"
                    onclick="cancelTryOut('POSTapi-change-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-change-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/change-password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-change-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-change-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>current_password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="current_password"                data-endpoint="POSTapi-change-password"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>new_password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="new_password"                data-endpoint="POSTapi-change-password"
               value="mqeopfuudtdsufvyvddqamniihfqcoynlazghdtqtqxbajwbpilpmufinllwloauydlsmsjury"
               data-component="body">
    <br>
<p>Must be at least 6 characters. Example: <code>mqeopfuudtdsufvyvddqamniihfqcoynlazghdtqtqxbajwbpilpmufinllwloauydlsmsjury</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>confirm_password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="confirm_password"                data-endpoint="POSTapi-change-password"
               value="consequatur"
               data-component="body">
    <br>
<p>The value and <code>new_password</code> must match. Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-me">GET api/me</h2>

<p>
</p>



<span id="example-requests-GETapi-me">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/me" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/me"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-me">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: &quot;غير مصرح به&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-me" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-me"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-me"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-me" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-me">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-me" data-method="GET"
      data-path="api/me"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-me', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-me"
                    onclick="tryItOut('GETapi-me');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-me"
                    onclick="cancelTryOut('GETapi-me');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-me"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/me</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-user--user_id-">GET api/user/{user_id}</h2>

<p>
</p>



<span id="example-requests-GETapi-user--user_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/user/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/user/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-user--user_id-">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-user--user_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-user--user_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user--user_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-user--user_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user--user_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-user--user_id-" data-method="GET"
      data-path="api/user/{user_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-user--user_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-user--user_id-"
                    onclick="tryItOut('GETapi-user--user_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-user--user_id-"
                    onclick="cancelTryOut('GETapi-user--user_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-user--user_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/user/{user_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-user--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-user--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="GETapi-user--user_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-addExpoPushToken">POST api/addExpoPushToken</h2>

<p>
</p>



<span id="example-requests-POSTapi-addExpoPushToken">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/addExpoPushToken" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/addExpoPushToken"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-addExpoPushToken">
</span>
<span id="execution-results-POSTapi-addExpoPushToken" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-addExpoPushToken"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-addExpoPushToken"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-addExpoPushToken" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-addExpoPushToken">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-addExpoPushToken" data-method="POST"
      data-path="api/addExpoPushToken"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-addExpoPushToken', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-addExpoPushToken"
                    onclick="tryItOut('POSTapi-addExpoPushToken');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-addExpoPushToken"
                    onclick="cancelTryOut('POSTapi-addExpoPushToken');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-addExpoPushToken"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/addExpoPushToken</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-addExpoPushToken"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-addExpoPushToken"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-delete-account">POST api/delete-account</h2>

<p>
</p>



<span id="example-requests-POSTapi-delete-account">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/delete-account" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/delete-account"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-delete-account">
</span>
<span id="execution-results-POSTapi-delete-account" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-delete-account"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-delete-account"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-delete-account" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-delete-account">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-delete-account" data-method="POST"
      data-path="api/delete-account"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-delete-account', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-delete-account"
                    onclick="tryItOut('POSTapi-delete-account');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-delete-account"
                    onclick="cancelTryOut('POSTapi-delete-account');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-delete-account"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/delete-account</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-delete-account"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-delete-account"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-categories">GET api/categories</h2>

<p>
</p>



<span id="example-requests-GETapi-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/categories" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/categories"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-categories">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;تصنيف 1&quot;,
        &quot;description&quot;: &quot;Qui voluptatibus totam saepe totam voluptatum.&quot;,
        &quot;image&quot;: &quot;category_1.jpg&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
    },
    {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;تصنيف 2&quot;,
        &quot;description&quot;: &quot;Eveniet totam voluptas accusantium.&quot;,
        &quot;image&quot;: &quot;category_2.jpg&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
    },
    {
        &quot;id&quot;: 3,
        &quot;name&quot;: &quot;تصنيف 3&quot;,
        &quot;description&quot;: &quot;Non id officiis et aliquid alias.&quot;,
        &quot;image&quot;: &quot;category_3.jpg&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
    },
    {
        &quot;id&quot;: 4,
        &quot;name&quot;: &quot;تصنيف 4&quot;,
        &quot;description&quot;: &quot;Voluptatem ipsa rerum harum.&quot;,
        &quot;image&quot;: &quot;category_4.jpg&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
    },
    {
        &quot;id&quot;: 5,
        &quot;name&quot;: &quot;تصنيف 5&quot;,
        &quot;description&quot;: &quot;Molestiae quia quia blanditiis enim.&quot;,
        &quot;image&quot;: &quot;category_5.jpg&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-categories" data-method="GET"
      data-path="api/categories"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-categories"
                    onclick="tryItOut('GETapi-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-categories"
                    onclick="cancelTryOut('GETapi-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-sub-categories">GET api/sub-categories</h2>

<p>
</p>



<span id="example-requests-GETapi-sub-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/sub-categories" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/sub-categories"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sub-categories">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 1,
        &quot;category_id&quot;: 1,
        &quot;name&quot;: &quot;فئة فرعية 1 - تصنيف 1&quot;,
        &quot;description&quot;: &quot;Et libero eos sit hic aliquam aut qui deserunt.&quot;,
        &quot;image&quot;: &quot;subcategory_1.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;تصنيف 1&quot;,
            &quot;description&quot;: &quot;Qui voluptatibus totam saepe totam voluptatum.&quot;,
            &quot;image&quot;: &quot;category_1.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 2,
        &quot;category_id&quot;: 1,
        &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 1&quot;,
        &quot;description&quot;: &quot;Dolor sed blanditiis est rerum facilis optio saepe.&quot;,
        &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
        &quot;type&quot;: &quot;periods&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:44:17.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;تصنيف 1&quot;,
            &quot;description&quot;: &quot;Qui voluptatibus totam saepe totam voluptatum.&quot;,
            &quot;image&quot;: &quot;category_1.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 3,
        &quot;category_id&quot;: 1,
        &quot;name&quot;: &quot;فئة فرعية 3 - تصنيف 1&quot;,
        &quot;description&quot;: &quot;Beatae blanditiis quia omnis dignissimos ipsam distinctio incidunt.&quot;,
        &quot;image&quot;: &quot;subcategory_3.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;تصنيف 1&quot;,
            &quot;description&quot;: &quot;Qui voluptatibus totam saepe totam voluptatum.&quot;,
            &quot;image&quot;: &quot;category_1.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 4,
        &quot;category_id&quot;: 2,
        &quot;name&quot;: &quot;فئة فرعية 1 - تصنيف 2&quot;,
        &quot;description&quot;: &quot;Quia optio fuga fugiat aut ut quod.&quot;,
        &quot;image&quot;: &quot;subcategory_1.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;تصنيف 2&quot;,
            &quot;description&quot;: &quot;Eveniet totam voluptas accusantium.&quot;,
            &quot;image&quot;: &quot;category_2.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 5,
        &quot;category_id&quot;: 2,
        &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 2&quot;,
        &quot;description&quot;: &quot;Accusamus eum et voluptas modi vero perspiciatis rerum.&quot;,
        &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;تصنيف 2&quot;,
            &quot;description&quot;: &quot;Eveniet totam voluptas accusantium.&quot;,
            &quot;image&quot;: &quot;category_2.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 6,
        &quot;category_id&quot;: 2,
        &quot;name&quot;: &quot;فئة فرعية 3 - تصنيف 2&quot;,
        &quot;description&quot;: &quot;Quaerat sed dolores quod non voluptas ipsam.&quot;,
        &quot;image&quot;: &quot;subcategory_3.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;تصنيف 2&quot;,
            &quot;description&quot;: &quot;Eveniet totam voluptas accusantium.&quot;,
            &quot;image&quot;: &quot;category_2.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 7,
        &quot;category_id&quot;: 3,
        &quot;name&quot;: &quot;فئة فرعية 1 - تصنيف 3&quot;,
        &quot;description&quot;: &quot;Ea nihil omnis velit consequatur porro placeat.&quot;,
        &quot;image&quot;: &quot;subcategory_1.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;تصنيف 3&quot;,
            &quot;description&quot;: &quot;Non id officiis et aliquid alias.&quot;,
            &quot;image&quot;: &quot;category_3.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 8,
        &quot;category_id&quot;: 3,
        &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 3&quot;,
        &quot;description&quot;: &quot;Aperiam tempore in in facilis consequatur eius.&quot;,
        &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;تصنيف 3&quot;,
            &quot;description&quot;: &quot;Non id officiis et aliquid alias.&quot;,
            &quot;image&quot;: &quot;category_3.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 9,
        &quot;category_id&quot;: 3,
        &quot;name&quot;: &quot;فئة فرعية 3 - تصنيف 3&quot;,
        &quot;description&quot;: &quot;Est facere occaecati placeat.&quot;,
        &quot;image&quot;: &quot;subcategory_3.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;تصنيف 3&quot;,
            &quot;description&quot;: &quot;Non id officiis et aliquid alias.&quot;,
            &quot;image&quot;: &quot;category_3.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 10,
        &quot;category_id&quot;: 4,
        &quot;name&quot;: &quot;فئة فرعية 1 - تصنيف 4&quot;,
        &quot;description&quot;: &quot;Accusantium sapiente maxime rem sint.&quot;,
        &quot;image&quot;: &quot;subcategory_1.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 4,
            &quot;name&quot;: &quot;تصنيف 4&quot;,
            &quot;description&quot;: &quot;Voluptatem ipsa rerum harum.&quot;,
            &quot;image&quot;: &quot;category_4.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 11,
        &quot;category_id&quot;: 4,
        &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 4&quot;,
        &quot;description&quot;: &quot;Quos rerum minus ratione doloremque ea qui.&quot;,
        &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 4,
            &quot;name&quot;: &quot;تصنيف 4&quot;,
            &quot;description&quot;: &quot;Voluptatem ipsa rerum harum.&quot;,
            &quot;image&quot;: &quot;category_4.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 12,
        &quot;category_id&quot;: 4,
        &quot;name&quot;: &quot;فئة فرعية 3 - تصنيف 4&quot;,
        &quot;description&quot;: &quot;Eos eos at eligendi harum.&quot;,
        &quot;image&quot;: &quot;subcategory_3.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 4,
            &quot;name&quot;: &quot;تصنيف 4&quot;,
            &quot;description&quot;: &quot;Voluptatem ipsa rerum harum.&quot;,
            &quot;image&quot;: &quot;category_4.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 13,
        &quot;category_id&quot;: 5,
        &quot;name&quot;: &quot;فئة فرعية 1 - تصنيف 5&quot;,
        &quot;description&quot;: &quot;Consequatur sint ut aut quae placeat eligendi.&quot;,
        &quot;image&quot;: &quot;subcategory_1.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 5,
            &quot;name&quot;: &quot;تصنيف 5&quot;,
            &quot;description&quot;: &quot;Molestiae quia quia blanditiis enim.&quot;,
            &quot;image&quot;: &quot;category_5.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 14,
        &quot;category_id&quot;: 5,
        &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 5&quot;,
        &quot;description&quot;: &quot;Odit commodi rem quo aperiam.&quot;,
        &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 5,
            &quot;name&quot;: &quot;تصنيف 5&quot;,
            &quot;description&quot;: &quot;Molestiae quia quia blanditiis enim.&quot;,
            &quot;image&quot;: &quot;category_5.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 15,
        &quot;category_id&quot;: 5,
        &quot;name&quot;: &quot;فئة فرعية 3 - تصنيف 5&quot;,
        &quot;description&quot;: &quot;Ex sint explicabo laudantium nihil.&quot;,
        &quot;image&quot;: &quot;subcategory_3.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 5,
            &quot;name&quot;: &quot;تصنيف 5&quot;,
            &quot;description&quot;: &quot;Molestiae quia quia blanditiis enim.&quot;,
            &quot;image&quot;: &quot;category_5.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 16,
        &quot;category_id&quot;: 1,
        &quot;name&quot;: &quot;فندق الاسكندرية&quot;,
        &quot;description&quot;: &quot;sssssssss&quot;,
        &quot;image&quot;: null,
        &quot;type&quot;: &quot;periods&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:43:30.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:43:30.000000Z&quot;,
        &quot;category&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;تصنيف 1&quot;,
            &quot;description&quot;: &quot;Qui voluptatibus totam saepe totam voluptatum.&quot;,
            &quot;image&quot;: &quot;category_1.jpg&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
        }
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sub-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sub-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sub-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sub-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sub-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sub-categories" data-method="GET"
      data-path="api/sub-categories"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sub-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sub-categories"
                    onclick="tryItOut('GETapi-sub-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sub-categories"
                    onclick="cancelTryOut('GETapi-sub-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sub-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sub-categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sub-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sub-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-categories--category_id--sub-categories">GET api/categories/{category_id}/sub-categories</h2>

<p>
</p>



<span id="example-requests-GETapi-categories--category_id--sub-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/categories/1/sub-categories" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/categories/1/sub-categories"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-categories--category_id--sub-categories">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 1,
        &quot;category_id&quot;: 1,
        &quot;name&quot;: &quot;فئة فرعية 1 - تصنيف 1&quot;,
        &quot;description&quot;: &quot;Et libero eos sit hic aliquam aut qui deserunt.&quot;,
        &quot;image&quot;: &quot;subcategory_1.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
    },
    {
        &quot;id&quot;: 2,
        &quot;category_id&quot;: 1,
        &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 1&quot;,
        &quot;description&quot;: &quot;Dolor sed blanditiis est rerum facilis optio saepe.&quot;,
        &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
        &quot;type&quot;: &quot;periods&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:44:17.000000Z&quot;
    },
    {
        &quot;id&quot;: 3,
        &quot;category_id&quot;: 1,
        &quot;name&quot;: &quot;فئة فرعية 3 - تصنيف 1&quot;,
        &quot;description&quot;: &quot;Beatae blanditiis quia omnis dignissimos ipsam distinctio incidunt.&quot;,
        &quot;image&quot;: &quot;subcategory_3.jpg&quot;,
        &quot;type&quot;: &quot;daily&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
    },
    {
        &quot;id&quot;: 16,
        &quot;category_id&quot;: 1,
        &quot;name&quot;: &quot;فندق الاسكندرية&quot;,
        &quot;description&quot;: &quot;sssssssss&quot;,
        &quot;image&quot;: null,
        &quot;type&quot;: &quot;periods&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:43:30.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:43:30.000000Z&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-categories--category_id--sub-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-categories--category_id--sub-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-categories--category_id--sub-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-categories--category_id--sub-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-categories--category_id--sub-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-categories--category_id--sub-categories" data-method="GET"
      data-path="api/categories/{category_id}/sub-categories"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-categories--category_id--sub-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-categories--category_id--sub-categories"
                    onclick="tryItOut('GETapi-categories--category_id--sub-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-categories--category_id--sub-categories"
                    onclick="cancelTryOut('GETapi-categories--category_id--sub-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-categories--category_id--sub-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/categories/{category_id}/sub-categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-categories--category_id--sub-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-categories--category_id--sub-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>category_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_id"                data-endpoint="GETapi-categories--category_id--sub-categories"
               value="1"
               data-component="url">
    <br>
<p>The ID of the category. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-notifications--type--">GET api/notifications/{type?}</h2>

<p>
</p>



<span id="example-requests-GETapi-notifications--type--">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/notifications/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/notifications/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-notifications--type--">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-notifications--type--" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-notifications--type--"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-notifications--type--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-notifications--type--" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-notifications--type--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-notifications--type--" data-method="GET"
      data-path="api/notifications/{type?}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-notifications--type--', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-notifications--type--"
                    onclick="tryItOut('GETapi-notifications--type--');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-notifications--type--"
                    onclick="cancelTryOut('GETapi-notifications--type--');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-notifications--type--"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/notifications/{type?}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-notifications--type--"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-notifications--type--"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="GETapi-notifications--type--"
               value="consequatur"
               data-component="url">
    <br>
<p>Example: <code>consequatur</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-notifications--id--mark-as-read">POST api/notifications/{id}/mark-as-read</h2>

<p>
</p>



<span id="example-requests-POSTapi-notifications--id--mark-as-read">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/notifications/17/mark-as-read" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/notifications/17/mark-as-read"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-notifications--id--mark-as-read">
</span>
<span id="execution-results-POSTapi-notifications--id--mark-as-read" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-notifications--id--mark-as-read"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-notifications--id--mark-as-read"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-notifications--id--mark-as-read" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-notifications--id--mark-as-read">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-notifications--id--mark-as-read" data-method="POST"
      data-path="api/notifications/{id}/mark-as-read"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-notifications--id--mark-as-read', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-notifications--id--mark-as-read"
                    onclick="tryItOut('POSTapi-notifications--id--mark-as-read');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-notifications--id--mark-as-read"
                    onclick="cancelTryOut('POSTapi-notifications--id--mark-as-read');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-notifications--id--mark-as-read"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/notifications/{id}/mark-as-read</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-notifications--id--mark-as-read"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-notifications--id--mark-as-read"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-notifications--id--mark-as-read"
               value="17"
               data-component="url">
    <br>
<p>The ID of the notification. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-DELETEapi-notifications--id-">DELETE api/notifications/{id}</h2>

<p>
</p>



<span id="example-requests-DELETEapi-notifications--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/notifications/17" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/notifications/17"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-notifications--id-">
</span>
<span id="execution-results-DELETEapi-notifications--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-notifications--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-notifications--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-notifications--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-notifications--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-notifications--id-" data-method="DELETE"
      data-path="api/notifications/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-notifications--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-notifications--id-"
                    onclick="tryItOut('DELETEapi-notifications--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-notifications--id-"
                    onclick="cancelTryOut('DELETEapi-notifications--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-notifications--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/notifications/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-notifications--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-notifications--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-notifications--id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the notification. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-products">GET api/products</h2>

<p>
</p>



<span id="example-requests-GETapi-products">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/products" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/products"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-products">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;current_page&quot;: 1,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;sub_category_id&quot;: 2,
            &quot;brand_id&quot;: 2,
            &quot;city_id&quot;: 10,
            &quot;neighborhood_id&quot;: 29,
            &quot;name&quot;: &quot;منتج رقم 1&quot;,
            &quot;description&quot;: &quot;هذا وصف لمنتج رقم 1 ويحتوي على تفاصيل وهمية.&quot;,
            &quot;price&quot;: &quot;278.00&quot;,
            &quot;discount_percent&quot;: 39,
            &quot;video_url&quot;: &quot;https://www.youtube.com/watch?v=1qH0jmeTexp&quot;,
            &quot;latitude&quot;: &quot;31.9039670&quot;,
            &quot;longitude&quot;: &quot;11.2879610&quot;,
            &quot;is_active&quot;: 1,
            &quot;is_approved&quot;: 0,
            &quot;is_featured&quot;: 0,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;reservation_type&quot;: &quot;periods&quot;,
            &quot;sub_category&quot;: {
                &quot;id&quot;: 2,
                &quot;category_id&quot;: 1,
                &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 1&quot;,
                &quot;description&quot;: &quot;Dolor sed blanditiis est rerum facilis optio saepe.&quot;,
                &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
                &quot;type&quot;: &quot;periods&quot;,
                &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-06-19T17:44:17.000000Z&quot;
            }
        },
        {
            &quot;id&quot;: 2,
            &quot;sub_category_id&quot;: 7,
            &quot;brand_id&quot;: 8,
            &quot;city_id&quot;: 7,
            &quot;neighborhood_id&quot;: 21,
            &quot;name&quot;: &quot;منتج رقم 2&quot;,
            &quot;description&quot;: &quot;هذا وصف لمنتج رقم 2 ويحتوي على تفاصيل وهمية.&quot;,
            &quot;price&quot;: &quot;780.00&quot;,
            &quot;discount_percent&quot;: 48,
            &quot;video_url&quot;: &quot;https://www.youtube.com/watch?v=v4A9BZ4IOSP&quot;,
            &quot;latitude&quot;: &quot;32.0062660&quot;,
            &quot;longitude&quot;: &quot;12.8212000&quot;,
            &quot;is_active&quot;: 1,
            &quot;is_approved&quot;: 0,
            &quot;is_featured&quot;: 0,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;reservation_type&quot;: &quot;daily&quot;,
            &quot;sub_category&quot;: {
                &quot;id&quot;: 7,
                &quot;category_id&quot;: 3,
                &quot;name&quot;: &quot;فئة فرعية 1 - تصنيف 3&quot;,
                &quot;description&quot;: &quot;Ea nihil omnis velit consequatur porro placeat.&quot;,
                &quot;image&quot;: &quot;subcategory_1.jpg&quot;,
                &quot;type&quot;: &quot;daily&quot;,
                &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
            }
        },
        {
            &quot;id&quot;: 3,
            &quot;sub_category_id&quot;: 4,
            &quot;brand_id&quot;: 2,
            &quot;city_id&quot;: 2,
            &quot;neighborhood_id&quot;: 6,
            &quot;name&quot;: &quot;منتج رقم 3&quot;,
            &quot;description&quot;: &quot;هذا وصف لمنتج رقم 3 ويحتوي على تفاصيل وهمية.&quot;,
            &quot;price&quot;: &quot;594.00&quot;,
            &quot;discount_percent&quot;: 12,
            &quot;video_url&quot;: &quot;https://www.youtube.com/watch?v=7kAKkV48e0m&quot;,
            &quot;latitude&quot;: &quot;32.1632060&quot;,
            &quot;longitude&quot;: &quot;12.8497220&quot;,
            &quot;is_active&quot;: 1,
            &quot;is_approved&quot;: 0,
            &quot;is_featured&quot;: 0,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;reservation_type&quot;: &quot;daily&quot;,
            &quot;sub_category&quot;: {
                &quot;id&quot;: 4,
                &quot;category_id&quot;: 2,
                &quot;name&quot;: &quot;فئة فرعية 1 - تصنيف 2&quot;,
                &quot;description&quot;: &quot;Quia optio fuga fugiat aut ut quod.&quot;,
                &quot;image&quot;: &quot;subcategory_1.jpg&quot;,
                &quot;type&quot;: &quot;daily&quot;,
                &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
            }
        },
        {
            &quot;id&quot;: 4,
            &quot;sub_category_id&quot;: 8,
            &quot;brand_id&quot;: 5,
            &quot;city_id&quot;: 8,
            &quot;neighborhood_id&quot;: 24,
            &quot;name&quot;: &quot;منتج رقم 4&quot;,
            &quot;description&quot;: &quot;هذا وصف لمنتج رقم 4 ويحتوي على تفاصيل وهمية.&quot;,
            &quot;price&quot;: &quot;455.00&quot;,
            &quot;discount_percent&quot;: 21,
            &quot;video_url&quot;: &quot;https://www.youtube.com/watch?v=A6gqOmtIfFe&quot;,
            &quot;latitude&quot;: &quot;32.5628920&quot;,
            &quot;longitude&quot;: &quot;10.2718980&quot;,
            &quot;is_active&quot;: 1,
            &quot;is_approved&quot;: 0,
            &quot;is_featured&quot;: 0,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;reservation_type&quot;: &quot;daily&quot;,
            &quot;sub_category&quot;: {
                &quot;id&quot;: 8,
                &quot;category_id&quot;: 3,
                &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 3&quot;,
                &quot;description&quot;: &quot;Aperiam tempore in in facilis consequatur eius.&quot;,
                &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
                &quot;type&quot;: &quot;daily&quot;,
                &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
            }
        },
        {
            &quot;id&quot;: 5,
            &quot;sub_category_id&quot;: 11,
            &quot;brand_id&quot;: 5,
            &quot;city_id&quot;: 4,
            &quot;neighborhood_id&quot;: 10,
            &quot;name&quot;: &quot;منتج رقم 5&quot;,
            &quot;description&quot;: &quot;هذا وصف لمنتج رقم 5 ويحتوي على تفاصيل وهمية.&quot;,
            &quot;price&quot;: &quot;457.00&quot;,
            &quot;discount_percent&quot;: 46,
            &quot;video_url&quot;: &quot;https://www.youtube.com/watch?v=N1vNq7mOPs0&quot;,
            &quot;latitude&quot;: &quot;31.2708620&quot;,
            &quot;longitude&quot;: &quot;11.0187210&quot;,
            &quot;is_active&quot;: 1,
            &quot;is_approved&quot;: 0,
            &quot;is_featured&quot;: 0,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;reservation_type&quot;: &quot;daily&quot;,
            &quot;sub_category&quot;: {
                &quot;id&quot;: 11,
                &quot;category_id&quot;: 4,
                &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 4&quot;,
                &quot;description&quot;: &quot;Quos rerum minus ratione doloremque ea qui.&quot;,
                &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
                &quot;type&quot;: &quot;daily&quot;,
                &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
            }
        },
        {
            &quot;id&quot;: 6,
            &quot;sub_category_id&quot;: 1,
            &quot;brand_id&quot;: 1,
            &quot;city_id&quot;: 1,
            &quot;neighborhood_id&quot;: 2,
            &quot;name&quot;: &quot;منتج رقم 6&quot;,
            &quot;description&quot;: &quot;هذا وصف لمنتج رقم 6 ويحتوي على تفاصيل وهمية.&quot;,
            &quot;price&quot;: &quot;746.00&quot;,
            &quot;discount_percent&quot;: 22,
            &quot;video_url&quot;: &quot;https://www.youtube.com/watch?v=DYgdX4bVZlk&quot;,
            &quot;latitude&quot;: &quot;30.7127710&quot;,
            &quot;longitude&quot;: &quot;12.8102930&quot;,
            &quot;is_active&quot;: 1,
            &quot;is_approved&quot;: 0,
            &quot;is_featured&quot;: 0,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;reservation_type&quot;: &quot;daily&quot;,
            &quot;sub_category&quot;: {
                &quot;id&quot;: 1,
                &quot;category_id&quot;: 1,
                &quot;name&quot;: &quot;فئة فرعية 1 - تصنيف 1&quot;,
                &quot;description&quot;: &quot;Et libero eos sit hic aliquam aut qui deserunt.&quot;,
                &quot;image&quot;: &quot;subcategory_1.jpg&quot;,
                &quot;type&quot;: &quot;daily&quot;,
                &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
            }
        },
        {
            &quot;id&quot;: 7,
            &quot;sub_category_id&quot;: 10,
            &quot;brand_id&quot;: 4,
            &quot;city_id&quot;: 10,
            &quot;neighborhood_id&quot;: 30,
            &quot;name&quot;: &quot;منتج رقم 7&quot;,
            &quot;description&quot;: &quot;هذا وصف لمنتج رقم 7 ويحتوي على تفاصيل وهمية.&quot;,
            &quot;price&quot;: &quot;186.00&quot;,
            &quot;discount_percent&quot;: 1,
            &quot;video_url&quot;: &quot;https://www.youtube.com/watch?v=6XVHYJIPLFD&quot;,
            &quot;latitude&quot;: &quot;32.7904150&quot;,
            &quot;longitude&quot;: &quot;12.9393200&quot;,
            &quot;is_active&quot;: 1,
            &quot;is_approved&quot;: 0,
            &quot;is_featured&quot;: 0,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;reservation_type&quot;: &quot;daily&quot;,
            &quot;sub_category&quot;: {
                &quot;id&quot;: 10,
                &quot;category_id&quot;: 4,
                &quot;name&quot;: &quot;فئة فرعية 1 - تصنيف 4&quot;,
                &quot;description&quot;: &quot;Accusantium sapiente maxime rem sint.&quot;,
                &quot;image&quot;: &quot;subcategory_1.jpg&quot;,
                &quot;type&quot;: &quot;daily&quot;,
                &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
            }
        },
        {
            &quot;id&quot;: 8,
            &quot;sub_category_id&quot;: 14,
            &quot;brand_id&quot;: 7,
            &quot;city_id&quot;: 6,
            &quot;neighborhood_id&quot;: 18,
            &quot;name&quot;: &quot;منتج رقم 8&quot;,
            &quot;description&quot;: &quot;هذا وصف لمنتج رقم 8 ويحتوي على تفاصيل وهمية.&quot;,
            &quot;price&quot;: &quot;733.00&quot;,
            &quot;discount_percent&quot;: 37,
            &quot;video_url&quot;: &quot;https://www.youtube.com/watch?v=F1sVDoVcrwj&quot;,
            &quot;latitude&quot;: &quot;31.7842550&quot;,
            &quot;longitude&quot;: &quot;11.3356930&quot;,
            &quot;is_active&quot;: 1,
            &quot;is_approved&quot;: 0,
            &quot;is_featured&quot;: 0,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;reservation_type&quot;: &quot;daily&quot;,
            &quot;sub_category&quot;: {
                &quot;id&quot;: 14,
                &quot;category_id&quot;: 5,
                &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 5&quot;,
                &quot;description&quot;: &quot;Odit commodi rem quo aperiam.&quot;,
                &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
                &quot;type&quot;: &quot;daily&quot;,
                &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
            }
        },
        {
            &quot;id&quot;: 9,
            &quot;sub_category_id&quot;: 2,
            &quot;brand_id&quot;: 5,
            &quot;city_id&quot;: 4,
            &quot;neighborhood_id&quot;: 11,
            &quot;name&quot;: &quot;منتج رقم 9&quot;,
            &quot;description&quot;: &quot;هذا وصف لمنتج رقم 9 ويحتوي على تفاصيل وهمية.&quot;,
            &quot;price&quot;: &quot;373.00&quot;,
            &quot;discount_percent&quot;: 10,
            &quot;video_url&quot;: &quot;https://www.youtube.com/watch?v=SHzIPm6ZDL6&quot;,
            &quot;latitude&quot;: &quot;30.4220950&quot;,
            &quot;longitude&quot;: &quot;10.8028760&quot;,
            &quot;is_active&quot;: 1,
            &quot;is_approved&quot;: 0,
            &quot;is_featured&quot;: 0,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:28.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:28.000000Z&quot;,
            &quot;reservation_type&quot;: &quot;periods&quot;,
            &quot;sub_category&quot;: {
                &quot;id&quot;: 2,
                &quot;category_id&quot;: 1,
                &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 1&quot;,
                &quot;description&quot;: &quot;Dolor sed blanditiis est rerum facilis optio saepe.&quot;,
                &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
                &quot;type&quot;: &quot;periods&quot;,
                &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-06-19T17:44:17.000000Z&quot;
            }
        },
        {
            &quot;id&quot;: 10,
            &quot;sub_category_id&quot;: 8,
            &quot;brand_id&quot;: 3,
            &quot;city_id&quot;: 10,
            &quot;neighborhood_id&quot;: 28,
            &quot;name&quot;: &quot;منتج رقم 10&quot;,
            &quot;description&quot;: &quot;هذا وصف لمنتج رقم 10 ويحتوي على تفاصيل وهمية.&quot;,
            &quot;price&quot;: &quot;834.00&quot;,
            &quot;discount_percent&quot;: 12,
            &quot;video_url&quot;: &quot;https://www.youtube.com/watch?v=nfTVdcQWXQg&quot;,
            &quot;latitude&quot;: &quot;32.0229370&quot;,
            &quot;longitude&quot;: &quot;10.9177020&quot;,
            &quot;is_active&quot;: 1,
            &quot;is_approved&quot;: 0,
            &quot;is_featured&quot;: 0,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:28.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:42:28.000000Z&quot;,
            &quot;reservation_type&quot;: &quot;daily&quot;,
            &quot;sub_category&quot;: {
                &quot;id&quot;: 8,
                &quot;category_id&quot;: 3,
                &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 3&quot;,
                &quot;description&quot;: &quot;Aperiam tempore in in facilis consequatur eius.&quot;,
                &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
                &quot;type&quot;: &quot;daily&quot;,
                &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
            }
        }
    ],
    &quot;first_page_url&quot;: &quot;http://localhost/api/products?page=1&quot;,
    &quot;from&quot;: 1,
    &quot;last_page&quot;: 5,
    &quot;last_page_url&quot;: &quot;http://localhost/api/products?page=5&quot;,
    &quot;links&quot;: [
        {
            &quot;url&quot;: null,
            &quot;label&quot;: &quot;&amp;laquo; Previous&quot;,
            &quot;active&quot;: false
        },
        {
            &quot;url&quot;: &quot;http://localhost/api/products?page=1&quot;,
            &quot;label&quot;: &quot;1&quot;,
            &quot;active&quot;: true
        },
        {
            &quot;url&quot;: &quot;http://localhost/api/products?page=2&quot;,
            &quot;label&quot;: &quot;2&quot;,
            &quot;active&quot;: false
        },
        {
            &quot;url&quot;: &quot;http://localhost/api/products?page=3&quot;,
            &quot;label&quot;: &quot;3&quot;,
            &quot;active&quot;: false
        },
        {
            &quot;url&quot;: &quot;http://localhost/api/products?page=4&quot;,
            &quot;label&quot;: &quot;4&quot;,
            &quot;active&quot;: false
        },
        {
            &quot;url&quot;: &quot;http://localhost/api/products?page=5&quot;,
            &quot;label&quot;: &quot;5&quot;,
            &quot;active&quot;: false
        },
        {
            &quot;url&quot;: &quot;http://localhost/api/products?page=2&quot;,
            &quot;label&quot;: &quot;Next &amp;raquo;&quot;,
            &quot;active&quot;: false
        }
    ],
    &quot;next_page_url&quot;: &quot;http://localhost/api/products?page=2&quot;,
    &quot;path&quot;: &quot;http://localhost/api/products&quot;,
    &quot;per_page&quot;: 10,
    &quot;prev_page_url&quot;: null,
    &quot;to&quot;: 10,
    &quot;total&quot;: 50
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-products" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-products"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-products"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-products" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-products">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-products" data-method="GET"
      data-path="api/products"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-products', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-products"
                    onclick="tryItOut('GETapi-products');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-products"
                    onclick="cancelTryOut('GETapi-products');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-products"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/products</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-products"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-products"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-products--product_id-">GET api/products/{product_id}</h2>

<p>
</p>



<span id="example-requests-GETapi-products--product_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/products/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/products/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-products--product_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;id&quot;: 1,
    &quot;sub_category_id&quot;: 2,
    &quot;brand_id&quot;: 2,
    &quot;city_id&quot;: 10,
    &quot;neighborhood_id&quot;: 29,
    &quot;name&quot;: &quot;منتج رقم 1&quot;,
    &quot;description&quot;: &quot;هذا وصف لمنتج رقم 1 ويحتوي على تفاصيل وهمية.&quot;,
    &quot;price&quot;: &quot;278.00&quot;,
    &quot;discount_percent&quot;: 39,
    &quot;video_url&quot;: &quot;https://www.youtube.com/watch?v=1qH0jmeTexp&quot;,
    &quot;latitude&quot;: &quot;31.9039670&quot;,
    &quot;longitude&quot;: &quot;11.2879610&quot;,
    &quot;is_active&quot;: 1,
    &quot;is_approved&quot;: 0,
    &quot;is_featured&quot;: 0,
    &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
    &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
    &quot;reservation_type&quot;: &quot;periods&quot;,
    &quot;features&quot;: [],
    &quot;images&quot;: [],
    &quot;sub_category&quot;: {
        &quot;id&quot;: 2,
        &quot;category_id&quot;: 1,
        &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 1&quot;,
        &quot;description&quot;: &quot;Dolor sed blanditiis est rerum facilis optio saepe.&quot;,
        &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
        &quot;type&quot;: &quot;periods&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:44:17.000000Z&quot;
    },
    &quot;brand&quot;: {
        &quot;id&quot;: 2,
        &quot;user_id&quot;: 52,
        &quot;name&quot;: &quot;علامة تجارية 2&quot;,
        &quot;description&quot;: &quot;Facere iure fugiat voluptates officiis sit suscipit aut perferendis.&quot;,
        &quot;image&quot;: &quot;brand_2.jpg&quot;,
        &quot;link&quot;: &quot;https://example.com/brand2&quot;,
        &quot;order&quot;: 2,
        &quot;latitude&quot;: &quot;70.285918&quot;,
        &quot;longitude&quot;: &quot;-74.197073&quot;,
        &quot;is_active&quot;: 1,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
    },
    &quot;city&quot;: {
        &quot;id&quot;: 10,
        &quot;name&quot;: &quot;طبرق&quot;,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
    },
    &quot;neighborhood&quot;: {
        &quot;id&quot;: 29,
        &quot;name&quot;: &quot;الحي الصناعي&quot;,
        &quot;city_id&quot;: 10,
        &quot;active&quot;: 1,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;
    },
    &quot;reviews&quot;: [],
    &quot;reservations&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-products--product_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-products--product_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-products--product_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-products--product_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-products--product_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-products--product_id-" data-method="GET"
      data-path="api/products/{product_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-products--product_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-products--product_id-"
                    onclick="tryItOut('GETapi-products--product_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-products--product_id-"
                    onclick="cancelTryOut('GETapi-products--product_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-products--product_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/products/{product_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-products--product_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-products--product_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>product_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="product_id"                data-endpoint="GETapi-products--product_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the product. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-featured-products">GET api/featured/products</h2>

<p>
</p>



<span id="example-requests-GETapi-featured-products">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/featured/products" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/featured/products"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-featured-products">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-featured-products" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-featured-products"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-featured-products"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-featured-products" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-featured-products">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-featured-products" data-method="GET"
      data-path="api/featured/products"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-featured-products', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-featured-products"
                    onclick="tryItOut('GETapi-featured-products');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-featured-products"
                    onclick="cancelTryOut('GETapi-featured-products');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-featured-products"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/featured/products</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-featured-products"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-featured-products"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-products--product_id--similars">GET api/products/{product_id}/similars</h2>

<p>
</p>



<span id="example-requests-GETapi-products--product_id--similars">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/products/1/similars" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/products/1/similars"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-products--product_id--similars">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 9,
        &quot;sub_category_id&quot;: 2,
        &quot;brand_id&quot;: 5,
        &quot;city_id&quot;: 4,
        &quot;neighborhood_id&quot;: 11,
        &quot;name&quot;: &quot;منتج رقم 9&quot;,
        &quot;description&quot;: &quot;هذا وصف لمنتج رقم 9 ويحتوي على تفاصيل وهمية.&quot;,
        &quot;price&quot;: &quot;373.00&quot;,
        &quot;discount_percent&quot;: 10,
        &quot;video_url&quot;: &quot;https://www.youtube.com/watch?v=SHzIPm6ZDL6&quot;,
        &quot;latitude&quot;: &quot;30.4220950&quot;,
        &quot;longitude&quot;: &quot;10.8028760&quot;,
        &quot;is_active&quot;: 1,
        &quot;is_approved&quot;: 0,
        &quot;is_featured&quot;: 0,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:28.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:28.000000Z&quot;,
        &quot;reservation_type&quot;: &quot;periods&quot;,
        &quot;sub_category&quot;: {
            &quot;id&quot;: 2,
            &quot;category_id&quot;: 1,
            &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 1&quot;,
            &quot;description&quot;: &quot;Dolor sed blanditiis est rerum facilis optio saepe.&quot;,
            &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
            &quot;type&quot;: &quot;periods&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:44:17.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 33,
        &quot;sub_category_id&quot;: 2,
        &quot;brand_id&quot;: 2,
        &quot;city_id&quot;: 10,
        &quot;neighborhood_id&quot;: 29,
        &quot;name&quot;: &quot;منتج رقم 33&quot;,
        &quot;description&quot;: &quot;هذا وصف لمنتج رقم 33 ويحتوي على تفاصيل وهمية.&quot;,
        &quot;price&quot;: &quot;401.00&quot;,
        &quot;discount_percent&quot;: 29,
        &quot;video_url&quot;: &quot;https://www.youtube.com/watch?v=DybEZm1I0kv&quot;,
        &quot;latitude&quot;: &quot;31.9495710&quot;,
        &quot;longitude&quot;: &quot;11.2095430&quot;,
        &quot;is_active&quot;: 1,
        &quot;is_approved&quot;: 0,
        &quot;is_featured&quot;: 0,
        &quot;created_at&quot;: &quot;2025-06-19T17:42:28.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-06-19T17:42:28.000000Z&quot;,
        &quot;reservation_type&quot;: &quot;periods&quot;,
        &quot;sub_category&quot;: {
            &quot;id&quot;: 2,
            &quot;category_id&quot;: 1,
            &quot;name&quot;: &quot;فئة فرعية 2 - تصنيف 1&quot;,
            &quot;description&quot;: &quot;Dolor sed blanditiis est rerum facilis optio saepe.&quot;,
            &quot;image&quot;: &quot;subcategory_2.jpg&quot;,
            &quot;type&quot;: &quot;periods&quot;,
            &quot;created_at&quot;: &quot;2025-06-19T17:42:27.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-06-19T17:44:17.000000Z&quot;
        }
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-products--product_id--similars" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-products--product_id--similars"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-products--product_id--similars"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-products--product_id--similars" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-products--product_id--similars">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-products--product_id--similars" data-method="GET"
      data-path="api/products/{product_id}/similars"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-products--product_id--similars', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-products--product_id--similars"
                    onclick="tryItOut('GETapi-products--product_id--similars');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-products--product_id--similars"
                    onclick="cancelTryOut('GETapi-products--product_id--similars');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-products--product_id--similars"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/products/{product_id}/similars</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-products--product_id--similars"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-products--product_id--similars"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>product_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="product_id"                data-endpoint="GETapi-products--product_id--similars"
               value="1"
               data-component="url">
    <br>
<p>The ID of the product. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-products--product_id--reviews">POST api/products/{product_id}/reviews</h2>

<p>
</p>



<span id="example-requests-POSTapi-products--product_id--reviews">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/products/1/reviews" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"product_id\": \"consequatur\",
    \"rate\": 3,
    \"comment\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/products/1/reviews"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "product_id": "consequatur",
    "rate": 3,
    "comment": "consequatur"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-products--product_id--reviews">
</span>
<span id="execution-results-POSTapi-products--product_id--reviews" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-products--product_id--reviews"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-products--product_id--reviews"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-products--product_id--reviews" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-products--product_id--reviews">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-products--product_id--reviews" data-method="POST"
      data-path="api/products/{product_id}/reviews"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-products--product_id--reviews', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-products--product_id--reviews"
                    onclick="tryItOut('POSTapi-products--product_id--reviews');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-products--product_id--reviews"
                    onclick="cancelTryOut('POSTapi-products--product_id--reviews');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-products--product_id--reviews"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/products/{product_id}/reviews</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-products--product_id--reviews"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-products--product_id--reviews"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>product_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="product_id"                data-endpoint="POSTapi-products--product_id--reviews"
               value="1"
               data-component="url">
    <br>
<p>The ID of the product. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>product_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="product_id"                data-endpoint="POSTapi-products--product_id--reviews"
               value="consequatur"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the products table. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>rate</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="rate"                data-endpoint="POSTapi-products--product_id--reviews"
               value="3"
               data-component="body">
    <br>
<p>Must be at least 1. Must not be greater than 5. Example: <code>3</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>comment</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="comment"                data-endpoint="POSTapi-products--product_id--reviews"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="endpoints-DELETEapi-products--product_id--reviews">DELETE api/products/{product_id}/reviews</h2>

<p>
</p>



<span id="example-requests-DELETEapi-products--product_id--reviews">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/products/1/reviews" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/products/1/reviews"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-products--product_id--reviews">
</span>
<span id="execution-results-DELETEapi-products--product_id--reviews" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-products--product_id--reviews"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-products--product_id--reviews"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-products--product_id--reviews" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-products--product_id--reviews">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-products--product_id--reviews" data-method="DELETE"
      data-path="api/products/{product_id}/reviews"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-products--product_id--reviews', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-products--product_id--reviews"
                    onclick="tryItOut('DELETEapi-products--product_id--reviews');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-products--product_id--reviews"
                    onclick="cancelTryOut('DELETEapi-products--product_id--reviews');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-products--product_id--reviews"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/products/{product_id}/reviews</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-products--product_id--reviews"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-products--product_id--reviews"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>product_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="product_id"                data-endpoint="DELETEapi-products--product_id--reviews"
               value="1"
               data-component="url">
    <br>
<p>The ID of the product. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-user-favorites-products">GET api/user/favorites/products</h2>

<p>
</p>



<span id="example-requests-GETapi-user-favorites-products">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/user/favorites/products" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/user/favorites/products"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-user-favorites-products">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-user-favorites-products" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-user-favorites-products"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user-favorites-products"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-user-favorites-products" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user-favorites-products">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-user-favorites-products" data-method="GET"
      data-path="api/user/favorites/products"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-user-favorites-products', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-user-favorites-products"
                    onclick="tryItOut('GETapi-user-favorites-products');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-user-favorites-products"
                    onclick="cancelTryOut('GETapi-user-favorites-products');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-user-favorites-products"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/user/favorites/products</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-user-favorites-products"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-user-favorites-products"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-favorites-products--product_id-">POST api/favorites/products/{product_id}</h2>

<p>
</p>



<span id="example-requests-POSTapi-favorites-products--product_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/favorites/products/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/favorites/products/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-favorites-products--product_id-">
</span>
<span id="execution-results-POSTapi-favorites-products--product_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-favorites-products--product_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-favorites-products--product_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-favorites-products--product_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-favorites-products--product_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-favorites-products--product_id-" data-method="POST"
      data-path="api/favorites/products/{product_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-favorites-products--product_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-favorites-products--product_id-"
                    onclick="tryItOut('POSTapi-favorites-products--product_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-favorites-products--product_id-"
                    onclick="cancelTryOut('POSTapi-favorites-products--product_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-favorites-products--product_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/favorites/products/{product_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-favorites-products--product_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-favorites-products--product_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>product_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="product_id"                data-endpoint="POSTapi-favorites-products--product_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the product. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-reservations">GET api/reservations</h2>

<p>
</p>



<span id="example-requests-GETapi-reservations">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/reservations" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/reservations"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-reservations">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-reservations" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-reservations"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-reservations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-reservations" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-reservations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-reservations" data-method="GET"
      data-path="api/reservations"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-reservations', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-reservations"
                    onclick="tryItOut('GETapi-reservations');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-reservations"
                    onclick="cancelTryOut('GETapi-reservations');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-reservations"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/reservations</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-reservations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-reservations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-reservations">POST api/reservations</h2>

<p>
</p>



<span id="example-requests-POSTapi-reservations">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/reservations" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"product_id\": \"consequatur\",
    \"type\": \"daily\",
    \"start_date\": \"2106-07-23\",
    \"end_date\": \"2106-07-23\",
    \"total_price\": 45
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/reservations"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "product_id": "consequatur",
    "type": "daily",
    "start_date": "2106-07-23",
    "end_date": "2106-07-23",
    "total_price": 45
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-reservations">
</span>
<span id="execution-results-POSTapi-reservations" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-reservations"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-reservations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-reservations" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-reservations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-reservations" data-method="POST"
      data-path="api/reservations"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-reservations', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-reservations"
                    onclick="tryItOut('POSTapi-reservations');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-reservations"
                    onclick="cancelTryOut('POSTapi-reservations');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-reservations"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/reservations</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-reservations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-reservations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>product_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="product_id"                data-endpoint="POSTapi-reservations"
               value="consequatur"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the products table. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="POSTapi-reservations"
               value="daily"
               data-component="body">
    <br>
<p>Example: <code>daily</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>daily</code></li> <li><code>morning</code></li> <li><code>evening</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>start_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_date"                data-endpoint="POSTapi-reservations"
               value="2106-07-23"
               data-component="body">
    <br>
<p>Must be a valid date. Must be a date after or equal to <code>today</code>. Example: <code>2106-07-23</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>end_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="end_date"                data-endpoint="POSTapi-reservations"
               value="2106-07-23"
               data-component="body">
    <br>
<p>Must be a valid date. Must be a date after or equal to <code>start_date</code>. Example: <code>2106-07-23</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>total_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="total_price"                data-endpoint="POSTapi-reservations"
               value="45"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>45</code></p>
        </div>
        </form>

                    <h2 id="endpoints-PUTapi-reservations--id-">PUT api/reservations/{id}</h2>

<p>
</p>



<span id="example-requests-PUTapi-reservations--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/reservations/17" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"type\": \"morning\",
    \"reservation_date\": \"2106-07-23\",
    \"status\": \"partial_refund\",
    \"total_price\": 45
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/reservations/17"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": "morning",
    "reservation_date": "2106-07-23",
    "status": "partial_refund",
    "total_price": 45
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-reservations--id-">
</span>
<span id="execution-results-PUTapi-reservations--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-reservations--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-reservations--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-reservations--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-reservations--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-reservations--id-" data-method="PUT"
      data-path="api/reservations/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-reservations--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-reservations--id-"
                    onclick="tryItOut('PUTapi-reservations--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-reservations--id-"
                    onclick="cancelTryOut('PUTapi-reservations--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-reservations--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/reservations/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-reservations--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-reservations--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-reservations--id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the reservation. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="PUTapi-reservations--id-"
               value="morning"
               data-component="body">
    <br>
<p>Example: <code>morning</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>daily</code></li> <li><code>morning</code></li> <li><code>evening</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reservation_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="reservation_date"                data-endpoint="PUTapi-reservations--id-"
               value="2106-07-23"
               data-component="body">
    <br>
<p>Must be a valid date. Must be a date after or equal to <code>today</code>. Example: <code>2106-07-23</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="PUTapi-reservations--id-"
               value="partial_refund"
               data-component="body">
    <br>
<p>Example: <code>partial_refund</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>active</code></li> <li><code>returned</code></li> <li><code>partial_refund</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>total_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="total_price"                data-endpoint="PUTapi-reservations--id-"
               value="45"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>45</code></p>
        </div>
        </form>

                    <h2 id="endpoints-DELETEapi-reservations--id-">DELETE api/reservations/{id}</h2>

<p>
</p>



<span id="example-requests-DELETEapi-reservations--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/reservations/17" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/reservations/17"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-reservations--id-">
</span>
<span id="execution-results-DELETEapi-reservations--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-reservations--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-reservations--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-reservations--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-reservations--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-reservations--id-" data-method="DELETE"
      data-path="api/reservations/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-reservations--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-reservations--id-"
                    onclick="tryItOut('DELETEapi-reservations--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-reservations--id-"
                    onclick="cancelTryOut('DELETEapi-reservations--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-reservations--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/reservations/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-reservations--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-reservations--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-reservations--id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the reservation. Example: <code>17</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
