<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>
    <style>body{font-family:system-ui,Segoe UI,Helvetica,Arial,sans-serif;margin:16px}</style>
    @stack('head')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>
<body class="admin-body">
    <header>
        <h1>Admin</h1>
    </header>
    <main>
        @yield('content')
    </main>
    @stack('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Initialize CKEditor on textareas with class `rich-editor`
                document.querySelectorAll('.rich-editor').forEach((textarea) => {
                    if (textarea._ckEditor) return;
                    ClassicEditor.create(textarea).then(editor => {
                        textarea._ckEditor = editor;
                    }).catch(err => console.error('CKEditor init error', err));
                });

                // Add small "Clear" buttons next to media preview elements
                document.querySelectorAll('input[type="hidden"][id$="_id"]').forEach((input) => {
                    const preview = document.getElementById(input.id + '_preview');
                    if (! preview) return;

                    // avoid adding multiple buttons
                    if (preview.nextElementSibling && preview.nextElementSibling.classList && preview.nextElementSibling.classList.contains('js-media-clear')) return;

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn btn-sm btn-outline-danger mt-2 js-media-clear';
                    btn.textContent = 'Clear';
                    btn.addEventListener('click', () => {
                        input.value = '';
                        if (preview.tagName === 'IMG') {
                            preview.src = '';
                            preview.classList.add('d-none');
                        } else {
                            preview.textContent = '';
                        }
                    });

                    preview.insertAdjacentElement('afterend', btn);
                });
            });
        </script>
</body>
</html>
