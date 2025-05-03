// Availability of `window.PublicKeyCredential` means WebAuthn is usable.
// `isUserVerifyingPlatformAuthenticatorAvailable` means the feature detection is usable.
// `isConditionalMediationAvailable` means the feature detection is usable.
if (window.PublicKeyCredential &&
    PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable &&
    PublicKeyCredential.isConditionalMediationAvailable) {
  // Check if user verifying platform authenticator is available.
  Promise.all([
    PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable(),
    PublicKeyCredential.isConditionalMediationAvailable(),
  ]).then(results => {
    if (results.every(r => r === true)) {

        const button = document.querySelector("#createPasskey");
        button.style.display = "block";
        button.addEventListener("click", function() {

            // Request creation of passkey
            // Deserialize and decode the `PublicKeyCredentialCreationOptions`.
            const decoded_options = JSON.parse( document.querySelector('#passkey').getAttribute('data-passkey-config') );
            const options = PublicKeyCredential.parseCreationOptionsFromJSON(decoded_options);

            navigator.credentials.create({
                publicKey: options
            }).then(function(credential) {
                const _result = credential.toJSON();
                const result = JSON.stringify(_result);
                console.log(result);

                document.querySelector("#passkeyValue").value = result;
                // document.querySelector("#passKey").submit();

            });

        });

    } else {

        alert("Passkey is not supported in your browser. Sorry!")

    }
  });
} else {
    alert("Passkey is not supported in your browser. Sorry!");
}
