<?php 
include __DIR__ . '/../layouts/dash_header.php'; ?>

<body class="g-sidenav-show bg-gray-100">
    <?php include __DIR__ . '/../layouts/dash_sidenav.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <?php include __DIR__ . '/../layouts/dash_nav.php'; ?>
        <!-- End Navbar -->

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body px-0 pt-0 pb-2">
                            <form action="/dashboard/article/store" method="POST" enctype="multipart/form-data"
                                id="articleForm">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <div class="row p-4">
                                    <div class="col-md-8">
                                        <!-- Update the form fields -->
                                        <div class="form-group mb-4">
                                            <label>Language</label>
                                            <select name="language" class="form-control" id="languageSelect" required>
                                                <option value="fr">Français</option>
                                                <option value="ar">العربية</option>
                                                <option value="en">English</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label>Title</label>
                                            <input type="text" name="title" class="form-control" required dir="auto">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label>Content</label>
                                            <div id="editor" dir="auto"></div>
                                            <textarea name="content" id="content" style="display: none"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <!-- Category -->
                                        <div class="form-group">
                                            <label class="form-control-label">Category</label>
                                            <select class="form-control" name="category_id" required>
                                                <?php foreach($categories as $category): ?>
                                                <option value="<?php echo $category['id']; ?>">
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Status -->
                                        <div class="form-group mt-3">
                                            <label class="form-control-label">Status</label>
                                            <select name="status" class="form-select form-select-sm d-inline w-auto">
                                                <?php foreach(Article::getAvailableStatuses($_SESSION['user_role']) as $value => $label): ?>
                                                <option value="<?php echo $value; ?>">
                                                    <?php echo htmlspecialchars($label); ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>


                                        <div class="form-group mt-3">
                                            <label class="form-control-label d-block mb-2">Article Options</label>
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input" id="featuredToggle"
                                                        name="featured" value="1">
                                                    <label class="form-check-label" for="featuredToggle">Featured
                                                        Article</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input" id="breakingToggle"
                                                        name="breaking" value="1">
                                                    <label class="form-check-label" for="breakingToggle">Breaking
                                                        News</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Image Upload -->
                                        <div class="form-group mt-3">
                                            <label class="form-control-label">Featured Image</label>
                                            <input type="file" class="form-control" name="image" id="imageUpload"
                                                onchange="previewImage(this);" required>
                                            <img id="imagePreview" src="#" alt="Image Preview"
                                                style="display: none; width: 100%; margin-top: 10px;">
                                        </div>

                                        <!-- Submit Buttons -->
                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn-primary w-100 mb-2">Publish
                                                Article</button>
                                            <a href="/dashboard/articles" class="btn btn-light w-100">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Start -->
        <footer class="footer mt-auto py-4 px-sm-3 px-md-5" style="background: #111111;">
            <p class="m-0 text-center" style="color: white;">
                © <?php echo date('Y'); ?>
                <a href="#" style="color: orange;">
                    <?php echo htmlspecialchars($app['app_name'] ?? 'School News Portal'); ?>
                </a>.
                Tous droits réservés.
                <br>
                Développé par <a href="https://github.com/EL-HOUSS-BRAHIM/" target="_blank"
                    style="color: orange;">Brahim Elhouss</a>.
            </p>
        </footer>
        <!-- Footer End -->
    </main>

    <?php include __DIR__ . '/../layouts/dash_footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/js/bootstrap-toggle.min.js"></script>
    <style>
    .ck-editor__editable {
        min-height: 300px;
    }

    .custom-switch {
        padding-left: 2.25rem;
    }

    .custom-control-input:checked~.custom-control-label::before {
        border-color: #2dce89;
        background-color: #2dce89;
    }

    .toggle.btn {
        min-width: 100px;
    }
    </style>

    <!-- Replace existing CKEditor script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
    class MyUploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file
                .then(file => new Promise((resolve, reject) => {
                    this._initRequest();
                    this._initListeners(resolve, reject, file);
                    this._sendRequest(file);
                }));
        }

        abort() {
            if (this.xhr) {
                this.xhr.abort();
            }
        }

        _initRequest() {
            const xhr = this.xhr = new XMLHttpRequest();
            xhr.open('POST', '/upload/image', true);
            xhr.responseType = 'json';

            // Get CSRF token
            const csrfToken = document.querySelector('input[name="csrf_token"]').value;
            xhr.setRequestHeader('X-CSRF-Token', csrfToken);
        }

        _sendRequest(file) {
            // Create FormData and append both file and CSRF token
            const data = new FormData();
            data.append('upload', file);
            data.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);
            this.xhr.send(data);
        }

        _initListeners(resolve, reject, file) {
            const xhr = this.xhr;
            const loader = this.loader;
            const genericErrorText = `Couldn't upload file: ${file.name}.`;

            xhr.addEventListener('error', () => reject(genericErrorText));
            xhr.addEventListener('abort', () => reject());
            xhr.addEventListener('load', () => {
                const response = xhr.response;

                if (!response || response.error) {
                    return reject(response && response.error ? response.error.message : genericErrorText);
                }

                resolve({
                    default: response.url
                });
            });

            if (xhr.upload) {
                xhr.upload.addEventListener('progress', evt => {
                    if (evt.lengthComputable) {
                        loader.uploadTotal = evt.total;
                        loader.uploaded = evt.loaded;
                    }
                });
            }
        }
    }

    function MyCustomUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new MyUploadAdapter(loader);
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('articleForm');
        const languageSelect = document.getElementById('languageSelect');
        const titleInput = document.querySelector('input[name="title"]');
        const csrfToken = document.querySelector('input[name="csrf_token"]').value;

        ClassicEditor
            .create(document.querySelector('#editor'), {
                language: languageSelect.value,
                extraPlugins: [MyCustomUploadAdapterPlugin],
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'link', 'bulletedList', 'numberedList', '|',
                        'indent', 'outdent', '|',
                        'imageUpload', 'blockQuote', '|',
                        'undo', 'redo'
                    ],
                    shouldNotGroupWhenFull: true
                },
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:inline',
                        'imageStyle:block',
                        'imageStyle:side'
                    ]
                }
            })
            .then(editor => {
                window.editor = editor;
                updateDirection(languageSelect.value);

                editor.model.document.on('change:data', () => {
                    document.querySelector('#content').value = editor.getData();
                });
            })
            .catch(error => {
                console.error('CKEditor error:', error);
            });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            document.querySelector('#content').value = window.editor.getData();
            this.submit();
        });

        languageSelect.addEventListener('change', function() {
            updateDirection(this.value);
        });

        function updateDirection(lang) {
            const isRTL = lang === 'ar';
            const direction = isRTL ? 'rtl' : 'ltr';

            titleInput.dir = direction;
            titleInput.style.textAlign = isRTL ? 'right' : 'left';

            if (window.editor) {
                window.editor.editing.view.change(writer => {
                    writer.setAttribute('dir', direction, window.editor.editing.view.document
                        .getRoot());
                });
            }
        }
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
</body>

</html>