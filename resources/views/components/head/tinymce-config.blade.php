<style>
    .tox-promotion {
        display: none !important;
    }

    .tox-statusbar__branding {
        display: none !important;
    }
</style>
<div>
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script>
        // Function to initialize TinyMCE
        function initTinyMCE() {
            if (window.tinymce) {
                tinymce.init({
                    selector: '.tinymce-editor',
                    language: 'en',
                    skin: "oxide",

                    plugins: 'code table lists link autolink autosave preview save wordcount fullscreen searchreplace visualblocks visualchars nonbreaking pagebreak charmap anchor insertdatetime advlist help',

                    toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | charmap emoticons | fullscreen preview | ltr rtl | code',

                    paste_data_images: false,

                    valid_elements: 'p,br,b,strong,i,em,u,ul,ol,li,span,h1,h2,h3,h4,h5,h6',

                    invalid_elements: 'img,iframe,video,audio,object,embed,source',

                    forced_root_block: 'p',

                    setup: function (editor) {
                        editor.on('change keyup', function () {
                            const editorId = editor.id;
                            const inputId = editorId + '-input';

                            document.getElementById(inputId).value = editor.getContent();
                            document.getElementById(inputId).dispatchEvent(
                                new Event('input', { bubbles: true })
                            );
                        });
                    }
                });
            }
        }
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            initTinyMCE();

            Livewire.on('tinymce-reinit', () => {
                // Small delay to ensure DOM is updated
                initTinyMCE();
                setTimeout(function () {
                    initTinyMCE();
                }, 100);
            });
        });

        // Initialize after Livewire navigation
        document.addEventListener('livewire:navigated', function () {
            // Small delay to ensure DOM is updated
            setTimeout(function () {
                initTinyMCE();
            }, 100);
        });


    </script>
</div>
