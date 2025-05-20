import { ref } from 'vue';

export default function mobile() {
    const is_mobile = ref(false);

    function isMobileDevice() {
        const isUserAgentMobile = /Mobi|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        const isScreenMobile = window.innerWidth <= 768;
        return isUserAgentMobile || isScreenMobile;
    }

    const isServer = typeof window === 'undefined';

    if (!isServer) {
        const updateMobileStatus = () => {
            is_mobile.value = isMobileDevice();
        };

        // Call once to set the initial state
        updateMobileStatus();

        // Add event listener for window resize
        window.addEventListener("resize", updateMobileStatus);
    }

    return is_mobile;
}
