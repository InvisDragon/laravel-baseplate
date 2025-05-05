const { startAuthentication } = SimpleWebAuthnBrowser;

const options = JSON.parse( document.querySelector('#passkey').getAttribute('data-passkey-request') );
startAuthentication({ optionsJSON: options, useBrowserAutofill: true })
    .then(authResp => {
        let json = JSON.stringify(authResp);
        document.querySelector('#passkey').value = json;
        document.querySelector('#auth_method').value = "passkey";
        document.querySelector('#passkey').form.submit();
    })
    .catch(err => {
        // TODO?!
        console.log(err);
        alert('An error ocurred. Please try again');
    });
