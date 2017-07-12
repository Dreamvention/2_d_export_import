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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-excel" class="form-horizontal">
                    <?php echo $tabs; ?>
                    <div class="form-group required">
                        <label class="control-label col-sm-2"><?php echo $entry_language; ?></label>
                        <div class="col-sm-10">
                            <select class="form-control" name="language_id">
                                <?php foreach($languages as $language) { ?>
                                <option value="<?php echo $language['language_id'] ?>"><?php echo $language['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <input type="file" name="import" style="display: none;" accept="application/zip,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                    <input type="hidden" name="recipient" value=""/>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <td class="text-left">
                                    <?php echo $column_name; ?>
                                </td>
                                <td class="text-left">
                                    <?php echo $column_description; ?>
                                </td>
                                <td class="text-center col-sm-3">
                                    <?php echo $column_action; ?>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($modules as $key => $module) { ?>
                            <tr>
                                <td class="text-left">
                                    <?php echo $module['title']; ?>
                                </td>
                                <td class="text-left">
                                    <?php echo $module['description']; ?>
                                </td>
                                <td class="text-center">
                                    <a id="button-export" class="btn btn-default" data-value="<?php echo $key; ?>"><i class="fa fa-download"></i> <?php echo $button_export; ?></a>
                                    <a id="button-import" class="btn btn-default" data-value="<?php echo $key; ?>"><i class="fa fa-upload"></i> <?php echo $button_import; ?></a>
                                    <a id="button-setting" class="btn btn-default" data-value="<?php echo $key; ?>"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></a>
                                </td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                    <ei_progress_modal></ei_progress_modal>
                    <ei_setting_modal></ei_setting_modal>
                </form>
            </div>
        </div>
    </div>
</div>
<?php foreach ($riot_tags as $riot_tag) { ?>
    <script src="<?php echo $riot_tag; ?>" type="riot/tag"></script>
<?php } ?>
<script type="text/javascript">

    riot.mixin({ store : d_export_import.createStore(<?php echo json_encode($json); ?>) });

    riot.mount('*');

    $(document).ready(function () {

        $(document).on('click', 'a#button-export', function(){
            d_export_import.updateState({mode : 'export', source:$(this).data('value')});
            d_export_import.initStart();
            d_export_import.export();
            
            $('#modal-progress').modal({
                backdrop: 'static',
                keyboard: false,
                show:true
            });
        });

        $(document).on('click', 'a#button-import', function(){
            $('input[name=recipient]').val($(this).data('value'));
            $('input[type=file][name=import]').val('');
            $('input[type=file][name=import]').click();
        });

        $(document).on('click', 'a#button-setting', function(){
            d_export_import.updateState({ 'filter_active' : $(this).data('value')});

            $('#modal-setting').modal({
                backdrop: 'static',
                keyboard: false,
                show:true
            });
        });

        $("input:file").change(function (){
            d_export_import.updateState({mode : 'import', source:$(this).data('value')});
            d_export_import.initStart();
            d_export_import.import();

            $('#modal-progress').modal({
                backdrop: 'static',
                keyboard: false,
                show:true
            });
        });
    });
</script>
<?php echo $footer; ?>