<h1 class="mb-3" style="color: var(--odfs-text-primary);">Welcome, <?php echo $_settings->userdata('firstname')." ".$_settings->userdata('lastname') ?>!</h1>
<hr>

<!-- DiscussGO Hero Section (replaces admin carousel) -->
<div class="container my-4">
  <div class="text-center py-4 py-md-5 mb-3" style="background-color: var(--odfs-bg-elevated); border-radius: 0.25rem; border: 1px solid var(--odfs-border);">
    <h1 class="mb-2" style="color: var(--odfs-accent); font-family: 'Arial Black', sans-serif; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">DiscussGO</h1>
    <p class="mb-0" style="color: var(--odfs-text-secondary);">Admin Dashboard · Manage topics, categories, and community activity</p>
  </div>
</div>
<div class="row">
  <div class="col-12 col-sm-3 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-gradient-navy elevation-1"><i class="fas fa-th-list"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Category List</span>
          <span class="info-box-number">
            <?php 
              $category = $conn->query("SELECT * FROM category_list where delete_flag = 0 and `status` = 1")->num_rows;
              echo format_num($category);
            ?>
            <?php ?>
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-3 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-gradient-light border elevation-1"><i class="fas fa-users-cog"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Registered Users</span>
          <span class="info-box-number">
            <?php 						
              $user = $conn->query("SELECT * FROM users where `type` = 2 ")->num_rows;
              echo format_num($user);
            ?>
            <?php ?>
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-3 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-gradient-primary elevation-1"><i class="fas fa-blog"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Published Post</span>
          <span class="info-box-number">
            <?php 
              $posts = $conn->query("SELECT * FROM post_list where `status` = 1 and delete_flag = 0 ")->num_rows;
              echo format_num($posts);
            ?>
            <?php ?>
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-3 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-gradient-secondary elevation-1"><i class="fas fa-blog"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Unpublished Post</span>
          <span class="info-box-number">
            <?php 
              $posts = $conn->query("SELECT * FROM post_list where `status` = 0 and delete_flag = 0 ")->num_rows;
              echo format_num($posts);
            ?>
            <?php ?>
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
  </div>

<!-- Original admin carousel commented out
<div class="container">
  <?php 
    // Hardcoded carousel images
    $files = array(
      base_url.'uploads/banner/1_wp2.jpg',
      base_url.'uploads/banner/sec_ban.webp'
    );
  ?>
  <div id="tourCarousel"  class="carousel slide" data-ride="carousel" data-interval="3000">
      <div class="carousel-inner h-100">
          <?php foreach($files as $k => $img): ?>
          <div class="carousel-item  h-100 <?php echo $k == 0? 'active': '' ?>">
              <img class="d-block w-100  h-100" style="object-fit:contain" src="<?php echo $img ?>" alt="">
          </div>
          <?php endforeach; ?>
      </div>
      <a class="carousel-control-prev" href="#tourCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#tourCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
      </a>
  </div>
</div>
-->
