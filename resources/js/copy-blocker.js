document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('copy', function (e) {
        e.preventDefault();
        return false;
    });

    document.addEventListener('cut', function (e) {
        e.preventDefault();
        return false;
    });

    document.addEventListener('contextmenu', function (e) {
        e.preventDefault();
        return false;
    });

    document.addEventListener('dragstart', function (e) {
        e.preventDefault();
        return false;
    });

    Livewire.on('preventCopy', () => {
        document.body.style.userSelect = 'none';
        document.body.style.webkitUserSelect = 'none';

        document.oncopy = function (e) {
            e.preventDefault();
            return false;
        }
        document.oncut = function (e) {
            e.preventDefault();
            return false;
        }
        document.oncontextmenu = function (e) {
            e.preventDefault();
            return false;
        }
    });
});
(function secureExam() {
    function clearPage() {
        document.body.innerHTML = `
                <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: #f3f4f6; display: flex; align-items: center; justify-content: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; z-index: 99999;">
                    <div style="background: white; border-radius: 8px; padding: 32px; max-width: 400px; text-align: center; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
                        <div style="background: #fee2e2; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
                                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h1 style="font-size: 24px; font-weight: 600; margin-bottom: 12px; color: #111827;">Access Denied</h1>
                        <p style="color: #6b7280; margin-bottom: 24px; line-height: 1.5;">The page has been closed due to unauthorized activity.</p>
                        <button onclick="location.reload()" style="width: 100%; padding: 10px; background: #2563eb; color: white; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; margin-bottom: 12px;">Reload Page</button>
                        <a href="{{ route('dashboard') }}" style="display: block; color: #6b7280; text-decoration: none; font-size: 14px;">Go Back Home</a>
                    </div>
                </div>
            `;
        document.title = "ACCESS FORBIDDEN";
    }

    document.addEventListener('keydown', function (e) {
        if (e.keyCode === 123 ||
            (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) ||
            ((e.ctrlKey || e.metaKey) && e.keyCode === 85)) {
            e.preventDefault();
            clearPage();
            return false;
        }
    });

    document.addEventListener('contextmenu', function (e) {
        e.preventDefault();
        return false;
    });

    let lastWidth = window.outerWidth;
    let lastHeight = window.outerHeight;

    setInterval(function () {
        const widthDiff = Math.abs(window.outerWidth - window.innerWidth);
        const heightDiff = Math.abs(window.outerHeight - window.innerHeight);

        if (widthDiff > 200 || heightDiff > 200) {
            clearPage();
        }

        if (lastWidth !== window.outerWidth || lastHeight !== window.outerHeight) {
            lastWidth = window.outerWidth;
            lastHeight = window.outerHeight;
        }
    }, 500);

    document.body.style.userSelect = 'none';
    document.body.style.webkitUserSelect = 'none';

    document.addEventListener('copy', function (e) {
        e.preventDefault();
        return false;
    });

    document.addEventListener('cut', function (e) {
        e.preventDefault();
        return false;
    });
})();
