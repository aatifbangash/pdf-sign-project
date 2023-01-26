<style>
  @media (min-width: 768px) {
    .modal-xl {
      width: 90% !important;
      max-width: 1200px !important;
    }
  }
</style>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/custom.css">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="content">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="card-box">
              <div class="row">
                <div class="col-lg-6 col-xl-6">
                  <!-- For Messages -->
                  <?php $this->load->view('admin/includes/_messages.php') ?>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-12">
                  <h4 class="header-title m-b-30 float-left">Files</h4>
                  <a href="#" onclick="window.history.back();" class="btn btn-warning float-right add-new-file-btn">Back</a>
                </div>
              </div>
              <div class="row">
                <?php if (!empty($pdfFiles) && sizeof($pdfFiles) > 0) : ?>
                  <?php foreach ($pdfFiles as $idx => $file) : ?>
                    <div class="col-lg-3 col-xl-2">
                      <div class="file-man-box">
                        <a onclick="return confirm('are you sure to delete?')" href="<?= base_url('admin/sign_documents/delete_file/' . ($file->file_id * 786)) ?>" title="delete" class="file-close">
                          <i class="fa fa-times-circle"></i>
                        </a>
                        <div class="file-img-box pdf-file-img-box">
                          <!-- <a class="folder-icon li-modal" data-filename="<?= $file->file_name ?>" title="<?= $file->file_name ?>" href="<?= base_url('admin/upload_documents/view_file/' . ($file->file_id * 786)) ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a> -->

                          <!-- <a class="folder-icon " title="<?= $file->file_name ?>" target="_blank" href="<?= base_url('admin/upload_documents/view_file/' . $file->file_id) ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a> -->

                          <a class="folder-icon " title="<?= $file->file_name ?>" target="_blank" href="<?= base_url('assets/pdf_uploads/' . $file->file_name) ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>

                        </div>
                        <div class="file-man-title">
                          <p class="mb-0 css-text-trucate"><small title="<?= $file->file_name ?>"><?= $file->file_name ?></small></p>
                          <p class="mb-0"><small><?= $file->created_at ?></small></p>
                          <a target="_blank" href="<?= base_url('admin/upload_documents/view_file/' . $file->file_id) ?>" title="Set Signature Position.">
                            <i class="fa fa-strikethrough"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php else : ?>
                  <div class="col-lg-3 col-xl-2">
                    <p>No uploads found.</p>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <!-- end col -->
        </div>
        <!-- end row -->
      </div>
      <!-- container -->
    </div>
    <div id="theModal" class="modal fade text-center" style="z-index: 99999999999;">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">PDF Viewer</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<script>
  $('.li-modal').on('click', function(e) {
    e.preventDefault();
    var filename = $(this).data('filename');
    var filePath = '<?= base_url("admin/upload_documents/view_file/"); ?>' + filename;
    $('.modal-body').html('<iframe allowfullscreen frameborder="0" class="col-lg-12 col-md-12 col-sm-12" height="480" src="' + filePath + '" frameborder="0" ></iframe>');
    $('#theModal').modal('show');
  });
</script>