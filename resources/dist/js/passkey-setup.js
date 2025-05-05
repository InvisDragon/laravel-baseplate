const { startRegistration, browserSupportsWebAuthn } = SimpleWebAuthnBrowser;

if(browserSupportsWebAuthn()) {

    const button = document.querySelector("#createPasskey");
    button.style.display = "block";
    button.addEventListener("click", function() {

        const options = JSON.parse( document.querySelector('#passkey').getAttribute('data-passkey-config') );
        startRegistration({ optionsJSON: options }).then(function(cred) {
            console.log(cred);
            document.querySelector("#passkeyValue").value = JSON.stringify( cred );
            document.querySelector("#passkey").submit();
        });

    });

}
