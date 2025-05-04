// Availability of `window.PublicKeyCredential` means WebAuthn is usable.
if (window.PublicKeyCredential &&
    PublicKeyCredential.isConditionalMediationAvailable) {
  // Check if conditional mediation is available.
  PublicKeyCredential.isConditionalMediationAvailable().then(function(isCMA) {
    if (isCMA) {

        // To abort a WebAuthn call, instantiate an `AbortController`.
        const abortController = new AbortController();

        const b64tobuffer = function(str) {
            let binary_string = window.atob(str);
            let len = binary_string.length;
            let bytes = new Uint8Array(len);
            for (let i = 0; i < len; i++)        {
                bytes[i] = binary_string.charCodeAt(i);
            }
            return bytes.buffer;
        }

        const arrayBufferToBase64 = function(buffer) {
            let binary = '';
            let bytes = new Uint8Array(buffer);
            let len = bytes.byteLength;
            for (let i = 0; i < len; i++) {
                binary += String.fromCharCode( bytes[ i ] );
            }
            return window.btoa(binary);
        }

        const publicKeyCredentialRequestOptions = JSON.parse( document.querySelector('#passkey').getAttribute('data-passkey-request') );
        publicKeyCredentialRequestOptions.challenge = b64tobuffer( publicKeyCredentialRequestOptions.challenge );

        navigator.credentials.get({
            publicKey: publicKeyCredentialRequestOptions,
            signal: abortController.signal,
            // Specify 'conditional' to activate conditional UI
            mediation: 'conditional'
        }).then(function(cred) {

            // create object for transmission to server
            const authenticatorAttestationResponse = {
                id: cred.rawId ? arrayBufferToBase64(cred.rawId) : null,
                clientDataJSON: cred.response.clientDataJSON  ? arrayBufferToBase64(cred.response.clientDataJSON) : null,
                authenticatorData: cred.response.authenticatorData ? arrayBufferToBase64(cred.response.authenticatorData) : null,
                signature: cred.response.signature ? arrayBufferToBase64(cred.response.signature) : null,
                userHandle: cred.response.userHandle ? arrayBufferToBase64(cred.response.userHandle) : null
            };

        });

      }
  });
}

