// Availability of `window.PublicKeyCredential` means WebAuthn is usable.
if (window.PublicKeyCredential &&
    PublicKeyCredential.​​isConditionalMediationAvailable) {
  // Check if conditional mediation is available.
  const isCMA = await PublicKeyCredential.​​isConditionalMediationAvailable();
  if (isCMA) {

    // To abort a WebAuthn call, instantiate an `AbortController`.
    const abortController = new AbortController();

    const publicKeyCredentialRequestOptions = {
        // Server generated challenge
        challenge: ****,
        // The same RP ID as used during registration
        rpId: 'example.com',
    };

    const credential = await navigator.credentials.get({
        publicKey: publicKeyCredentialRequestOptions,
        signal: abortController.signal,
        // Specify 'conditional' to activate conditional UI
        mediation: 'conditional'
    });

  }
}
