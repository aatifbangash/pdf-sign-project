<div class="datalist">
    <table id="example1" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th width="50"><?= trans('id') ?></th>
                <th><?= trans('user') ?></th>
                <th><?= trans('username') ?></th>
                <th><?= trans('email') ?></th>
                <th><?= trans('role') ?></th>
                <th width="100"><?= trans('status') ?></th>
                <th width="120"><?= trans('action') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($info as $row) : ?>
                <?php if ($row['admin_role_title'] == 'Super Admin') continue; ?>
                <tr>
                    <td>
                        <?= $row['user_id'] ?>
                    </td>
                    <td>
                        <h4 class="m0 mb5"><?= $row['firstname'] ?> <?= $row['lastname'] ?></h4>
                        <small class="text-muted"><?= $row['admin_role_title'] ?></small>
                    </td>
                    <td>
                        <?= $row['username'] ?>
                    </td>
                    <td>
                        <?= $row['email'] ?>
                    </td>
                    <td>
                        <button class="btn btn-xs btn-success"><?= $row['admin_role_title'] ?></button>
                    </td>
                    <td><input class='tgl tgl-ios tgl_checkbox' data-id="<?= $row['user_id'] ?>" id='cb_<?= $row['user_id'] ?>' type='checkbox' <?php echo ($row['is_active'] == 1) ? "checked" : ""; ?> />
                        <label class='tgl-btn' for='cb_<?= $row['user_id'] ?>'></label>
                    </td>
                    <td>
                        <a href="<?= base_url("admin/admin/edit/" . $row['user_id']); ?>" class="btn btn-warning btn-xs mr5">
                            <i class="fa fa-edit"></i>
                        </a>
                        <?php if ($row['is_supper'] != 1) { ?>
                            <a href="<?= base_url("admin/admin/delete/" . $row['user_id']); ?>" onclick="return confirm('are you sure to delete?')" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a>
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>