@if($errors->any())
    <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
        <strong style="display: block; margin-bottom: 0.5rem;">Please fix the following errors:</strong>
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <label for="title" class="required-field">Title</label>
    <input type="text" id="title" name="title" class="form-control" 
           value="{{ old('title', isset($post) ? $post->title : '') }}" required
           placeholder="Enter a compelling title for your post">
</div>

<div class="form-group">
    <label for="slug">Slug (URL)</label>
    <input type="text" id="slug" name="slug" class="form-control" 
           value="{{ old('slug', isset($post) ? $post->slug : '') }}"
           placeholder="post-title-url (leave blank to auto-generate)">
    <small style="color: #666; display: block; margin-top: 5px;">
        SEO-friendly URL for this post. Auto-generated from title if empty.
    </small>
</div>

<div class="form-group">
    <label for="description" class="required-field">Description</label>
    <textarea id="description" name="description" class="form-control" rows="3" required
              placeholder="Brief summary of the post (appears in blog listings)">{{ old('description', isset($post) ? $post->description : '') }}</textarea>
    <small style="color: #666; display: block; margin-top: 5px;">
        Max 500 characters. This appears in blog listings and search results.
    </small>
    <div id="desc-char-count" style="color: #666; font-size: 0.85rem; text-align: right; margin-top: 5px;"></div>
</div>

<div class="form-group">
    <label for="featured_image">Featured Image</label>
    
    @if(isset($post) && $post->featured_image)
        <div class="image-preview-container">
            <div class="current-image-label">Current Image:</div>
            <img src="{{ $post->featured_image_url }}" alt="Current featured image" 
                 class="image-preview" id="current-image-preview">
            <div style="margin-top: 10px;">
                <small style="color: #666; display: block; margin-top: 5px;">
                    To change the image, select a new file below. The current image will be replaced.
                </small>
            </div>
        </div>
    @endif
    
    <input type="file" id="featured_image" name="featured_image" class="form-control"
           accept="image/jpeg,image/png,image/jpg,image/gif">
    
    <div id="new-image-preview-container" style="{{ isset($post) && $post->featured_image ? 'display: none;' : '' }}">
        <img id="new-image-preview" src="#" alt="New image preview" class="image-preview" style="display: none;">
    </div>
    
    <small style="color: #666; display: block; margin-top: 5px;">
        Recommended size: 1200×630px (2:1 ratio). Max file size: 2MB. Formats: JPG, PNG, GIF.
    </small>
</div>

<div class="form-group">
    <label for="content" class="required-field">Content</label>
    <textarea id="content" name="content" class="form-control" rows="12" required
              placeholder="Write your blog post content here...">{{ old('content', isset($post) ? $post->content : '') }}</textarea>
    <small style="color: #666; display: block; margin-top: 5px;">
        You can use basic HTML tags like &lt;p&gt;, &lt;h2&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;img&gt;, &lt;a&gt;
    </small>
    <div id="content-char-count" style="color: #666; font-size: 0.85rem; text-align: right; margin-top: 5px;"></div>
</div>

<div class="form-group">
    <label class="form-check">
        <input type="checkbox" id="is_published" name="is_published" value="1" 
               class="form-check-input" {{ old('is_published', isset($post) ? $post->is_published : false) ? 'checked' : '' }}>
        <span class="form-check-label">Publish this post</span>
    </label>
    <small style="color: #666; display: block; margin-top: 5px;">
        When checked, the post will be visible to the public. Uncheck to save as draft.
    </small>
</div>

<script>
    // Character count for description
    const descriptionTextarea = document.getElementById('description');
    const descCharCount = document.getElementById('desc-char-count');
    
    if (descriptionTextarea && descCharCount) {
        descriptionTextarea.addEventListener('input', function() {
            const count = this.value.length;
            descCharCount.textContent = `${count}/500 characters`;
            
            if (count > 500) {
                descCharCount.style.color = '#dc3545';
            } else if (count > 400) {
                descCharCount.style.color = '#ffc107';
            } else {
                descCharCount.style.color = '#28a745';
            }
        });
        
        // Initial count
        if (descriptionTextarea.value) {
            descriptionTextarea.dispatchEvent(new Event('input'));
        }
    }
    
    // Character count for content
    const contentTextarea = document.getElementById('content');
    const contentCharCount = document.getElementById('content-char-count');
    
    if (contentTextarea && contentCharCount) {
        contentTextarea.addEventListener('input', function() {
            const count = this.value.length;
            contentCharCount.textContent = `${count} characters`;
            
            if (count > 10000) {
                contentCharCount.style.color = '#dc3545';
            } else if (count > 5000) {
                contentCharCount.style.color = '#ffc107';
            } else {
                contentCharCount.style.color = '#28a745';
            }
        });
        
        // Initial count
        if (contentTextarea.value) {
            contentTextarea.dispatchEvent(new Event('input'));
        }
    }
    
    // Image preview for new image
    const featuredImageInput = document.getElementById('featured_image');
    const newImagePreview = document.getElementById('new-image-preview');
    const currentImagePreview = document.getElementById('current-image-preview');
    const newImagePreviewContainer = document.getElementById('new-image-preview-container');
    
    if (featuredImageInput) {
        featuredImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (newImagePreview) {
                        newImagePreview.src = e.target.result;
                        newImagePreview.style.display = 'block';
                    }
                    
                    // Show new image preview container
                    if (newImagePreviewContainer) {
                        newImagePreviewContainer.style.display = 'block';
                    }
                    
                    // If there's a current image preview, hide it
                    if (currentImagePreview) {
                        currentImagePreview.style.display = 'none';
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }
</script>