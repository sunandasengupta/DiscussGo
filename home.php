
<style>
    .carousel-item>img{
        object-fit:fill !important;
    }
    #carouselExampleControls .carousel-inner{
        height:280px !important;
    }
    #search-field .form-control.rounded-pill{
        border-top-right-radius:0 !important;
        border-bottom-right-radius:0 !important;
        border-right:none !important
    }
    #search-field .form-control:focus{
        box-shadow:none !important;
    }
    #search-field .form-control:focus + .input-group-append .input-group-text{
        border-color: #86b7fe !important
    }
    #search-field .input-group-text.rounded-pill{
        border-top-left-radius:0 !important;
        border-bottom-left-radius:0 !important;
        border-right:left !important
    }
    .post-item{
        transition:all .2s ease-in-out;
    }
    .post-item:hover{
        transform:scale(1.02);
    }
</style>
<section class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- DiscussGO Hero Section -->
                <div class="bg-dark text-center py-5 mb-4" style="background-color: var(--odfs-bg-main) !important;">
                    <h1 class="display-1 fw-bold mb-0" style="color: var(--odfs-accent); font-family: 'Arial Black', sans-serif; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">DiscussGO</h1>
                    <p class="lead mt-3" style="color: var(--odfs-text-secondary);">Share your thoughts, engage with the community</p>
                </div>
                <!-- Original carousel commented out
                <div id="carouselExampleControls" class="carousel slide bg-dark" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php 
                            // Hardcoded carousel images
                            $carousel_images = array(
                                'uploads/banner/1_wp2.jpg',
                                'uploads/banner/sec_ban.webp'
                            );
                            $_i = 0;
                            foreach($carousel_images as $img_path):
                                $_i++;
                        ?>
                        <div class="carousel-item h-100 <?php echo $_i == 1 ? "active" : '' ?>">
                            <img src="<?php echo base_url . $img_path ?>" class="d-block w-100  h-100" alt="Banner <?php echo $_i ?>">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    </div>
                -->
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                <div class="input-group input-group-lg" id="search-field">
                    <input type="search" class="form-control form-control-lg  rounded-pill" aria-label="Search Post Input" id="search" placeholder="Search post here">
                    <div class="input-group-append">
                        <span class="input-group-text rounded-pill bg-transparent"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-cols-xl-4 row-cols-md-3 row-cols-sm-1 gx-2 gy-2">
            <?php 
            $posts = $conn->query("SELECT p.*, c.name as `category` FROM `post_list` p inner join category_list c on p.category_id = c.id where p.status = 1  and p.`delete_flag` = 0 order by abs(unix_timestamp(p.date_created)) desc");
            while($row = $posts->fetch_assoc()):
            ?>
            <div class="col post-item">
                <a href="./?p=posts/view_post&id=<?= $row['id'] ?>" class="card rounded-0 shadow text-decoration-none text-reset">
                    <div class="card-body">
                        <div class="mb-2 text-right">
                                <small class="badge badge-light border text-dark rounded-pill px-3"><i class="far fa-circle"></i> <?= $row['category'] ?></small>
                        </div>
                        <h3 class="card-title w-100 font-weight-bold"><?= $row['title'] ?></h3>
                        <div class="card-text truncate-3 text-muted text-sm"><?= strip_tags($row['content']) ?></div>
                        <div class="mb-2 text-right">
                            <small class="text-muted"><i><?= date("Y-m-d h:i A", strtotime($row['date_created'])) ?></i></small>
                        </div>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
        
        <!-- Feature Cards Section -->
        <div class="row mt-5 pt-4">
            <div class="col-md-4 mb-4">
                <div class="card h-100 rounded-0 shadow" style="background-color: var(--odfs-bg-elevated); border-color: var(--odfs-border);">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-desktop fa-3x" style="color: var(--odfs-accent);"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3" style="color: var(--odfs-text-primary);">Easy-to-use interface</h4>
                        <p class="card-text" style="color: var(--odfs-text-secondary);">Navigate effortlessly through our intuitive design. Create, read, and engage with discussions in just a few clicks.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 rounded-0 shadow" style="background-color: var(--odfs-bg-elevated); border-color: var(--odfs-border);">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-folder-open fa-3x" style="color: var(--odfs-accent);"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3" style="color: var(--odfs-text-primary);">Topic-wise discussion categories</h4>
                        <p class="card-text" style="color: var(--odfs-text-secondary);">Find your interests instantly with organized categories. From technology to lifestyle, discover conversations that matter to you.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 rounded-0 shadow" style="background-color: var(--odfs-bg-elevated); border-color: var(--odfs-border);">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-comments fa-3x" style="color: var(--odfs-accent);"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3" style="color: var(--odfs-text-primary);">Real-time responses and engagement</h4>
                        <p class="card-text" style="color: var(--odfs-text-secondary);">Connect instantly with community members. Share ideas, get feedback, and build meaningful relationships through active discussions.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Community Card -->
        <div class="row justify-content-center">
            <div class="col-md-8 mb-4">
                <div class="card rounded-0 shadow" style="background-color: var(--odfs-bg-elevated); border-color: var(--odfs-border);">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-users fa-3x" style="color: var(--odfs-accent);"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3" style="color: var(--odfs-text-primary);">Respectful and inclusive community</h4>
                        <p class="card-text" style="color: var(--odfs-text-secondary);">Join a safe space where diverse voices are valued and respected. We foster meaningful dialogues, promote understanding, and ensure everyone feels welcome to share their perspectives.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(function(){
        $('#search').on('input', function(){
            var _search = $(this).val().toLowerCase()
            $('.post-item').each(function(){
                var _text = $(this).text().toLowerCase()
                _text = _text.trim()
                if(_text.includes(_search) === false){
                    $(this).toggle(false)
                }else{
                    $(this).toggle(true)
                }
            })
        })
    })
</script>