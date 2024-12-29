<?php 
include '../views/layouts/dash_header.php'; ?>

<body class="g-sidenav-show bg-gray-100">
    <?php include '../views/layouts/dash_sidenav.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="/dashboard">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Edit Article</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Edit Article</h6>
                </nav>
            </div>
        </nav>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body px-0 pt-0 pb-2">
                            <form action="/dashboard/article/update" method="POST" enctype="multipart/form-data" id="articleForm">
                                <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                <div class="row p-4">
                                    <div class="col-md-8">
                                        <!-- Title -->
                                        <div class="form-group">
                                            <label class="form-control-label">Title</label>
                                            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
                                        </div>
                                        
                                        <!-- Content Editor -->
                                        <div class="form-group mt-3">
                                            <label class="form-control-label">Content</label>
                                            <div id="editor"><?php echo htmlspecialchars($article['content']); ?></div>
                                            <textarea name="content" id="content" style="display: none"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <!-- Category -->
                                        <div class="form-group">
                                            <label class="form-control-label">Category</label>
                                            <select class="form-control" name="category_id" required>
                                                <?php foreach($categories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $article['category_id'] ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($category['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Status -->
                                        <div class="form-group mt-3">
                                            <label class="form-control-label">Status</label>
                                            <select class="form-control" name="status">
                                                <?php foreach(Article::getAllStatuses() as $value => $label): ?>
                                                    <option value="<?php echo $value; ?>" <?php echo $value == $article['status'] ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($label); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Image Upload -->
                                        <div class="form-group mt-3">
                                            <label class="form-control-label">Featured Image</label>
                                            <input type="file" class="form-control" name="image" id="imageUpload" onchange="previewImage(this);">
                                            <img id="imagePreview" src="<?php echo htmlspecialchars($article['image'] ?? '../assets/img/default-article.jpg'); ?>" alt="Image Preview" style="width: 100%; margin-top: 10px;">
                                        </div>

                                        <!-- Submit Buttons -->
                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn-primary w-100 mb-2">Update Article</button>
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
    </main>

    <?php include '../views/layouts/dash_footer.php'; ?>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                // Update hidden content field before form submission
                document.getElementById('articleForm').addEventListener('submit', function() {
                    document.getElementById('content').value = editor.getData();
                });
            })
            .catch(error => {
                console.error(error);
            });

        // Image preview function
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