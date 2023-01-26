  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="card card-default">
        <div class="card-header">
          <div class="d-inline-block">
              <h3 class="card-title"> <i class="fa fa-plus"></i>
              &nbsp; Add Files </h3>
          </div>
          <!-- <div class="d-inline-block float-right">
             <a href="<?= base_url('admin/upload_documents/list'); ?>" class="btn btn-success"><i class="fa fa-list"></i> <?= trans('list_folders') ?></a>
          </div> -->
        </div>
        <div class="card-body">
   
           <!-- For Messages -->
            <?php $this->load->view('admin/includes/_messages.php') ?>

            <?php echo form_open_multipart( base_url('admin/upload_documents/add_files/' . $folderId)); ?>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <!-- /.card-header -->
                  <!-- form start -->
                  <div class="card-body">
                      <table class="table">
                      <thead>
                        <tr>
                          <th><?= trans('action') ?></th>
                          <th width="40%"><?= trans('pdf_file') ?></th>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                          <th>&nbsp;</th>
                        </tr>
                      </thead>
                      <tbody class="field_wrapper">
                          <tr class="item">
                            <td>
                              <a href="javascript:void(0);" class="add_button btn btn-sm btn-primary" title="Add field"><i class="fa fa-plus"></i></a>
                            </td>
                            <td>
                              <div class="form-group">
                                <input type="file" name="pdf_files[]" class="form-control calcEvent" id="" >
                              </div>
                            </td>
                          </tr>
                      </tbody>
                    </table>
                    <input type="submit" name="submit" value="Add" class="btn btn-primary pull-right">
                    </div>
                    <!-- /.card-body -->
                </div>
              </div>
            </div>
          <?php echo form_close(); ?>
        </div>  
      </div>
    </section> 
</div>
  <script type="text/javascript">
    $(function(){

      //---------------------------------------------------------------
      $('#customer').change(function(e){
        var id = $('#customer').val();
        var post_data = {
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        };
        $.ajax({
          type: 'POST',
          url: '<?= base_url("admin/invoices/customer_detail/"); ?>'+id,
          data: post_data,
          success: function(response){
            var data = JSON.parse(response);
            console.log(data.id);
            $('#firstname').val(data.firstname);
            $('#address').val(data.address);
            $('#email').val(data.email);
            $('#mobile_no').val(data.mobile_no);
          }
        });

      });

      //---------------------------------------------------------------

      var max_field = 10;
      var add_button = $('.add_button');
      var wrapper = $('.field_wrapper');
      var html_fields = '<tr class="item"><td> <a href="javascript:void(0);" class="remove_button btn btn-sm btn-danger" title="Remove field"><i class="fa fa-minus"></i></a> </td> <td> <div class="form-group"> <input type="file" name="pdf_files[]" class="form-control calcEvent" id="" placeholder="Description" > </div> </td> <td> <div class="form-group">&nbsp;</div> </td> <td> <div class="form-group"> &nbsp; </div> </td> <td> <div class="form-group"> &nbsp;</div> </td> <td> &nbsp;</td> </tr>';

      var x = 1; // 

      $(add_button).click(function(){
        if(x < max_field){
          x++;
          $(wrapper).append(html_fields);
        }

      });

      $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).closest('tr').remove(); //Remove field html
        x--; //Decrement field counter
      });

    });
  </script>