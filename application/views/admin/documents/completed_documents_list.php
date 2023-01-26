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
                                    <h4 class="header-title m-b-30">Completed documents</h4>
                                </div>
                            </div>

                            <div class="row">
                                <?php if (!empty($userFolders) && sizeof($userFolders) > 0) : ?>
                                    <?php foreach ($userFolders as $idx => $folder) : ?>
                                        <div class="col-lg-3 col-xl-2">
                                            <div class="file-man-box">
                                                <!-- <a href="<?= base_url('admin/upload_documents/delete_folder/' . ($folder->folder_id * 786)) ?>" title="delete" class="file-close folder-edit-icon">
                                <i class="fa fa-asterisk"></i>
                              </a>
                              <a href="<?= base_url('admin/upload_documents/delete_folder/' . ($folder->folder_id * 786)) ?>" title="delete" class="file-close">
                                <i class="fa fa-times-circle"></i>
                              </a> -->
                                                <div class="file-img-box">
                                                    <a class="folder-icon" href="<?= base_url('admin/upload_documents/list_files/' . ($folder->folder_id * 786)) ?>"><i class="fa fa-folder" aria-hidden="true"></i></a>
                                                </div>
                                                <!-- <a href="#" class="file-download"><i title="signature" class="fa fa-pencil"></i></a> -->
                                                <div class="file-man-title">

                                                    <p class="mb-0"><small><?= $folder->company_name ?></small></p>
                                                    <p class="mb-0"><small><?= date('Y-m-d', strtotime($folder->date_created)) ?></small></p>
                                                    <!-- <div class="folder-action-buttons">
                                    <a href="<?= base_url('admin/upload_documents/update_folder/' . ($folder->folder_id * 786)) ?>" class='update-button'>Update</a> | <a class='delete-button' onClick="return confirm('Are you sure you want to delete this')" href="<?= base_url('admin/upload_documents/delete_folder/' . ($folder->folder_id * 786)) ?>">Delete</a>
                                    </div> -->
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
    </section>
</div>