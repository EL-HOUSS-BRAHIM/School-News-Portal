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
                            <form action="/dashboard/article/update" method="POST" enctype="multipart/form-data"
                                id="articleForm">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                <div class="row p-4">
                                    <div class="col-md-8">
                                        <div class="form-group mb-4">
                                            <label>Language</label>
                                            <select name="language" class="form-control" id="languageSelect" required>
                                                <option value="fr"
                                                    <?php echo $article['language'] === 'fr' ? 'selected' : ''; ?>>
                                                    Français</option>
                                                <option value="ar"
                                                    <?php echo $article['language'] === 'ar' ? 'selected' : ''; ?>>
                                                    العربية</option>
                                                <option value="en"
                                                    <?php echo $article['language'] === 'en' ? 'selected' : ''; ?>>
                                                    English</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label>Title</label>
                                            <input type="text" name="title" class="form-control" required
                                                dir="<?php echo $article['language'] === 'ar' ? 'rtl' : 'ltr'; ?>"
                                                value="<?php echo htmlspecialchars($article['title']); ?>">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label>Content</label>
                                            <div id="editor"
                                                dir="<?php echo $article['language'] === 'ar' ? 'rtl' : 'ltr'; ?>">
                                                <?php echo htmlspecialchars_decode($article['content']); ?>
                                            </div>
                                            <textarea name="content" id="content" style="display: none"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <!-- Category -->
                                        <div class="form-group">
                                            <label class="form-control-label">Category</label>
                                            <select class="form-control" name="category_id" required>
                                                <?php foreach($categories as $category): ?>
                                                <option value="<?php echo $category['id']; ?>"
                                                    <?php echo $category['id'] == $article['category_id'] ? 'selected' : ''; ?>>
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
                                                <option value="<?php echo $value; ?>"
                                                    <?php echo $value == $article['status'] ? 'selected' : ''; ?>>
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
                                                        name="featured" value="1"
                                                        <?php echo $article['featured'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="featuredToggle">Featured
                                                        Article</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input" id="breakingToggle"
                                                        name="breaking" value="1"
                                                        <?php echo $article['breaking'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="breakingToggle">Breaking
                                                        News</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Image Upload -->
                                        <div class="form-group mt-3">
                                            <label class="form-control-label">Featured Image</label>
                                            <input type="file" class="form-control" name="image" id="imageUpload"
                                                onchange="previewImage(this);">
                                            <img id="imagePreview"
                                                src="<?php echo htmlspecialchars($article['image'] ?? '../assets/img/default-article.jpg'); ?>"
                                                alt="Image Preview" style="width: 100%; margin-top: 10px;">
                                        </div>

                                        <!-- Submit Buttons -->
                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn-primary w-100 mb-2">Update
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
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

    <!-- Add this style in the head section -->
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

    <!-- Replace the existing editor initialization script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CKEditor with enhanced configuration
        ClassicEditor
            .create(document.querySelector('#editor'), {
                language: 'en',
                height: '400px',
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'bold',
                        'italic',
                        'underline',
                        'strikethrough',
                        '|',
                        'link',
                        'bulletedList',
                        'numberedList',
                        '|',
                        'alignment',
                        'indent',
                        'outdent',
                        '|',
                        'blockQuote',
                        'insertTable',
                        'mediaEmbed',
                        'imageUpload',
                        '|',
                        'fontSize',
                        'fontColor',
                        'fontBackgroundColor',
                        '|',
                        'undo',
                        'redo'
                    ],
                    shouldNotGroupWhenFull: true
                },
                placeholder: 'Start writing your article here...',
                removePlugins: ['MediaEmbedToolbar'],
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:full',
                        'imageStyle:side'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn',
                        'tableRow',
                        'mergeTableCells'
                    ]
                }
            })
            .then(editor => {
                window.editor = editor;
                editor.model.document.on('change:data', () => {
                    document.querySelector('#content').value = editor.getData();
                });

                // Set initial content if exists
                const initialContent = document.querySelector('#content').value;
                if (initialContent) {
                    editor.setData(initialContent);
                }
            })
            .catch(error => {
                console.error('CKEditor initialization error:', error);
                document.querySelector('#editor').innerHTML = 
                    '<p class="text-danger">Error loading editor. Please refresh the page.</p>';
            });

        // Form submit handler with validation
        document.getElementById('articleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!window.editor || !window.editor.getData().trim()) {
                alert('Please add some content to the article');
                return;
            }
            document.getElementById('content').value = window.editor.getData();
            this.submit();
        });
    });
    </script>
</body>

</html>