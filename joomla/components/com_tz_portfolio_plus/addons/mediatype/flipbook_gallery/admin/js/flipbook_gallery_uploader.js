jQuery(function($){
    /*
     * For the sake keeping the code clean and the examples simple this file
     * contains only the plugin configuration & callbacks.
     *
     * UI functions ui_* can be located in: style_ui.js
     */
    FlipbookGallery.initUploader();
    $('#flipbook_gallery_uploader').dmUploader({ //
        url: FlipbookGallery.ajaxUrl,
        maxFileSize: FlipbookGallery.maxFileSize*1000000, // 3 Megs
        allowedTypes: 'image/*',
        extFilter: FlipbookGallery.extFilter,
        onDragEnter: function(){
            // Happens when dragging something over the DnD area
            this.addClass('active');
        },
        onDragLeave: function(){
            // Happens when dragging something OUT of the DnD area
            this.removeClass('active');
        },
        onInit: function(){
            // Plugin is ready to use
            FlipbookGallery.ui_add_log('Penguin initialized :)', 'info');
        },
        onComplete: function(){
            // All files in the queue are processed (success or error)
            FlipbookGallery.ui_add_log('All pending tranfers finished');
        },
        onNewFile: function(id, file){
            // When a new file is added using the file selector or the DnD area
            FlipbookGallery.ui_add_log('New file added #' + id);
            FlipbookGallery.ui_multi_add_file(id, file);
        },
        onBeforeUpload: function(id){
            // about tho start uploading a file
            FlipbookGallery.ui_add_log('Starting the upload of #' + id);
            FlipbookGallery.ui_multi_update_file_status(id, 'uploading', 'Uploading...');
            FlipbookGallery.ui_multi_update_file_progress(id, 0, '', true);
        },
        onUploadCanceled: function(id) {
            // Happens when a file is directly canceled by the user.
            FlipbookGallery.ui_multi_update_file_status(id, 'warning', 'Canceled by User');
            FlipbookGallery.ui_multi_update_file_progress(id, 0, 'warning', false);
        },
        onUploadProgress: function(id, percent){
            // Updating file progress
            FlipbookGallery.ui_multi_update_file_progress(id, percent);
        },
        onUploadSuccess: function(id, data){
            // A file was successfully uploaded
            FlipbookGallery.ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
            FlipbookGallery.ui_add_log('Upload of file #' + id + ' COMPLETED', 'success');
            FlipbookGallery.ui_multi_update_file_data(id, data);
            FlipbookGallery.ui_multi_update_file_status(id, 'success', 'Upload Complete');
            FlipbookGallery.ui_multi_update_file_progress(id, 100, 'success', false);
        },
        onUploadError: function(id, xhr, status, message){
            FlipbookGallery.ui_multi_update_file_status(id, 'danger', message);
            FlipbookGallery.ui_multi_update_file_progress(id, 0, 'danger', false);
        },
        onFallbackMode: function(){
            // When the browser doesn't support this plugin :(
            FlipbookGallery.ui_add_log('Plugin cant be used here, running Fallback callback', 'danger');
        },
        onFileSizeError: function(file){
            FlipbookGallery.ui_add_log('File \'' + file.name + '\' cannot be added: size excess limit', 'danger');
        }
        ,
        onFileExtError: function(file){
            FlipbookGallery.ui_add_log('File \'' + file.name + '\' cannot be added: extension invalid', 'danger');
        }
    });
});