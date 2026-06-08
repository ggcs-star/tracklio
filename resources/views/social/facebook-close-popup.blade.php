<script>
    if (window.opener) {
        window.opener.postMessage({ type: 'facebook_connected' }, '*');
        window.close();
    } else {
        window.location.href = '{{ route("accounts") }}';
    }
</script>