<?php
/**
 * Cookie Consent Banner
 */
?>

<div
    id="cookie-consent"
    class="fixed inset-x-0 bottom-0 z-[9999] hidden px-4 pb-4 sm:px-6"
>
    <div
        class="mx-auto max-w-5xl rounded-2xl border border-gray-200 bg-white p-5 shadow-2xl sm:p-6"
    >
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

            <div class="max-w-3xl">
                <h2 class="text-lg font-semibold text-gray-900">
                   Cookie Consent
                </h2>

                <p class="mt-2 text-sm leading-6 text-gray-600">
                    This website uses cookies or similar technologies to enhance your browsing experience and provide personalized recommendations.
                </p>

                <a
                    href="<?= url('privacy-policy') ?>"
                    class="mt-2 inline-block text-sm font-medium text-blue-600 underline hover:text-blue-700"
                >
                    Privacy Policy
                </a>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:shrink-0">
                <button
                    type="button"
                    id="cookie-reject"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Reject
                </button>

                <button
                    type="button"
                    id="cookie-accept"
                    class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700"
                >
                    Accept All
                </button>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const banner = document.getElementById('cookie-consent');
    const acceptButton = document.getElementById('cookie-accept');
    const rejectButton = document.getElementById('cookie-reject');

    if (!banner || !acceptButton || !rejectButton) {
        return;
    }

    const cookieConsent = localStorage.getItem('cookie-consent');

    // Already selected
    if (cookieConsent) {
        return;
    }

    // Show banner
    banner.classList.remove('hidden');

    // Accept
    acceptButton.addEventListener('click', function () {
        localStorage.setItem('cookie-consent', 'accepted');

        banner.classList.add('hidden');
    });

    // Reject
    rejectButton.addEventListener('click', function () {
        localStorage.setItem('cookie-consent', 'rejected');

        banner.classList.add('hidden');
    });

});
</script>