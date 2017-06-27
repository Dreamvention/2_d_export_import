<?php
/*
*  location: admin/view
*/
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="form-inline pull-right">
                <button type="submit" form="form-editor-history" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <h1><?php echo $heading_title; ?> <?php echo $version; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if (!empty($error['warning'])) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['warning']; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if (!empty($success)) { ?>
        <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-editor-history" class="form-horizontal">
                    <?php echo $tabs; ?>
                    <div class="form-group">
                        <label class="control-label col-sm-2"><span data-toggle="tooltip" title="<?php echo $help_limit; ?>"><?php echo $entry_limit; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="<?php echo $codename; ?>_setting[limit]" value="<?php echo $setting['limit']; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2"><span data-toggle="tooltip" title="<?php echo $help_limit_step; ?>"><?php echo $entry_limit_step; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="<?php echo $codename; ?>_setting[limit_step]" value="<?php echo $setting['limit_step']; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2"><span data-toggle="tooltip" title="<?php echo $help_truncate_table; ?>"><?php echo $entry_truncate_table; ?></span></label>
                        <div class="col-sm-10">
                        <input type="hidden" name="<?php echo $codename; ?>_setting[truncate_table]" value="0" />
                            <input type="checkbox" class="form-control switcher" data-label-text="<?php echo $text_enabled; ?>"  name="<?php echo $codename; ?>_setting[truncate_table]" <?php if($setting['truncate_table']) { echo "checked='checked'";}; ?> value="1"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".switcher").bootstrapSwitch({
            'onColor': 'success',
            'onText': '<?php echo $text_yes; ?>',
            'offText': '<?php echo $text_no; ?>',
        });
    });
</script>
<?php echo $footer; ?>