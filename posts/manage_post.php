<?php 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `post_list` where id= '{$_GET['id']}' and user_id = '{$_settings->userdata('id')}'");
    if($qry->num_rows > 0){
        foreach($qry->fetch_array() as $k => $v){
            if(!is_numeric($k)){
                $$k = $v;
            }
        }
    }
}
?>
<style>
    .form-group.note-form-group.note-group-select-from-files {
        display: none;
    }
</style>
<section class="py-4">
    <div class="container">
        <div class="card rounded-0 shadow">
            <div class="card-header">
                <h5 class="card-title"><?= !isset($id) ? "Add New Topic" : "Update Topic Details" ?></h5>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <form action="" id="post-form">
                        <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">
                        <div class="form-group">
                            <label for="title" class="control-label">Title</label>
                            <input type="text" class="form-control rounded-0" name="title" id="title" value="<?= isset($title) ? $title : "" ?>">
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 px-0">
                            <label for="category_type" class="control-label">Category Type</label>
                            <div class="btn-group btn-group-toggle mb-2" data-toggle="buttons">
                                <label class="btn btn-outline-secondary active">
                                    <input type="radio" name="category_type" id="category_type_existing" value="existing" checked> Choose Existing
                                </label>
                                <label class="btn btn-outline-secondary">
                                    <input type="radio" name="category_type" id="category_type_new" value="new"> Create New
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 px-0" id="existing_category_div">
                            <label for="category_id" class="control-label">Select Category</label>
                            <select class="form-control rounded-0" name="category_id" id="category_id">
                                <option value="" disabled <?= !isset($category_id) ? "selected" : '' ?>></option>
                                <?php 
                                $category = $conn->query("SELECT * FROM `category_list` where delete_flag = 0 and `status` = 1 ".(isset($category_id) && $category_id > 0 ? " or id = '{$category_id}' " : "")."  order by `name` asc");
                                while($row = $category->fetch_array()):
                                ?>
                                <option value="<?= $row['id'] ?>" <?= isset($category_id) && $category_id == $row['id'] ? "selected" : "" ?>><?= $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 px-0" id="new_category_div" style="display: none;">
                            <label for="new_category" class="control-label">New Category Name</label>
                            <input type="text" class="form-control rounded-0" name="new_category" id="new_category" placeholder="Enter new category name">
                            <small class="text-muted">Category will be created and automatically selected</small>
                        </div>
                        <div class="form-group">
                            <label for="content" class="control-label">Content</label>
                            <textarea type="text" class="form-control rounded-0" name="content" id="content"><?= isset($content) ? $content : "" ?></textarea>
                        </div>
                        <div class="form-group">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="status" name='status' value="1" <?= isset($status) && $status == 1 ? 'checked' : '' ?>>
                                <label for="status">
                                </label>
                            </div>
                            <label for="status" class="control-label">Published</label>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer py-1 text-center">
                <button class="btn btn-flat btn-sm btn-primary bg-gradient-primary rounded-0" form="post-form"><i class="fa fa-save"></i> Save</button>
                <a class="btn btn-flat btn-sm btn-light bg-gradient-light border rounded-0" href="./?p=posts"><i class="fa fa-angle-left"></i> Cancel</a>
            </div>
        </div>
    </div>
</section>
<script>
    $(function(){
        // Handle category type toggle
        $('input[name="category_type"]').change(function(){
            if($(this).val() == 'existing') {
                $('#existing_category_div').show();
                $('#new_category_div').hide();
                $('#category_id').prop('required', true);
                $('#new_category').prop('required', false);
            } else {
                $('#existing_category_div').hide();
                $('#new_category_div').show();
                $('#category_id').prop('required', false);
                $('#new_category').prop('required', true);
            }
        });
        
        $('#category_id').select2({
            placeholder:"Please Select Category Here",
            width:'100%',
            containerCssClass:'form-control rounded-0'
        })
        $('#content').summernote({
            height:"20em",
            placeholder:"Write your content here",
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                [ 'insert', [ 'picture' ] ],
                [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
            ]
        })
        $('#post-form').submit(function(e){
            e.preventDefault()
            var _this = $(this)
            var el = $('<div>')
                el.addClass('alert alert-danger err_msg')
                el.hide()
            $('.err_msg').remove()
            
            // Validate category selection
            var categoryType = $('input[name="category_type"]:checked').val();
            if(categoryType == 'existing' && $('#category_id').val() == '') {
                el.text('Please select a category');
                _this.prepend(el);
                el.show('slow');
                $('html, body').scrollTop(0);
                return false;
            }
            if(categoryType == 'new' && $('#new_category').val().trim() == '') {
                el.text('Please enter a new category name');
                _this.prepend(el);
                el.show('slow');
                $('html, body').scrollTop(0);
                return false;
            }
            
            if(_this[0].checkValidity() == false){
                _this[0].reportValidity();
                return false;
            }
            
            // If creating new category, handle that first
            if(categoryType == 'new') {
                var newCategoryName = $('#new_category').val().trim();
                
                // Create new category first
                $.ajax({
                    url: _base_url_ + "classes/Master.php?f=save_category",
                    method: 'POST',
                    data: {
                        name: newCategoryName,
                        description: 'User created category for post',
                        status: 1
                    },
                    dataType: 'json',
                    success: function(catResp) {
                        if(catResp.status == 'success') {
                            // Set the new category ID and submit the post
                            $('#category_id').val(catResp.id || 'new');
                            submitPost(_this, el);
                        } else {
                            el.text(catResp.msg || 'Failed to create new category');
                            _this.prepend(el);
                            el.show('slow');
                            $('html, body').scrollTop(0);
                            end_loader();
                        }
                    },
                    error: function() {
                        el.text('Error creating new category');
                        _this.prepend(el);
                        el.show('slow');
                        $('html, body').scrollTop(0);
                        end_loader();
                    }
                });
            } else {
                submitPost(_this, el);
            }
        })
        
        function submitPost(form, errorEl) {
            start_loader()
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_post",
                method:'POST',
                type:'POST',
                data:new FormData(form[0]),
                dataType:'json',
                cache:false,
                processData:false,
                contentType: false,
                error:err=>{
                    console.log(err)
                    alert('An error occurred')
                    end_loader()
                },
                success:function(resp){
                    if(resp.status == 'success'){
                    location.replace('./?p=posts/view_post&id='+resp.pid)
                    }else if(!!resp.msg){
                        errorEl.html(resp.msg)
                        errorEl.show('slow')
                        form.prepend(errorEl)
                        $('html, body').scrollTop(0)
                    }else{
                        alert('An error occurred')
                        console.log(resp)
                    }
                    end_loader()
                }
            })
        }
    })
</script>